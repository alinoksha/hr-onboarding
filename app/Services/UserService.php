<?php

namespace App\Services;

use App\Mail\PasswordSetup;
use App\Mail\ResetPassword;
use App\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RonasIT\Support\Services\EntityService;
use Illuminate\Support\Facades\Password;

/**
 * @property UserRepository $repository
 */
class UserService extends EntityService
{
    protected MediaService $mediaService;

    public function __construct()
    {
        $this->mediaService = app(MediaService::class);

        $this->setRepository(UserRepository::class);
    }

    public function login(array $data): ?string
    {
        if (!Auth::attempt($data)) {
           return null;
        }

        $tokenName = request()->ip();

        return request()->user()->createToken($tokenName)->plainTextToken;
    }

    public function search(array $filters): LengthAwarePaginator
    {
        $relations = Arr::get($filters, 'with', []);
        $onlyTrashed = $filters['only_trashed'] ?? false;
        $filters['company_id'] = request()->user()->company_id;

        $users = $this->repository
            ->onlyTrashed($onlyTrashed)
            ->with($relations)
            ->searchQuery($filters)
            ->filterByList('role_id', 'roles_ids')
            ->filterByQuery(['email', 'first_name', 'last_name', 'position']);

        if ($filters['company_id']) {
            $users = $users->filterBy('company_id');
        }

        return $users->getSearchResults();
    }

    public function create(array $data): Model
    {
        $data['role_id'] = $data['role_id'] ?? Role::EMPLOYEE;
        list($password, $hash) = $this->generatePassword();
        $data['password'] = $hash;
        $user = $this->repository->create($data);

        if (!empty($data['scripts'])) {
            $insertData = array_map(function ($scriptId) use ($user) {
                return [
                    'script_id' => $scriptId,
                    'user_id' => $user->id
                ];
            }, $data['scripts']);

            DB::table('script_user')->insert($insertData);
        }

        Mail::to($user)->send(new PasswordSetup($password));

        return $user;
    }

    public function resetPassword(int $userId): void
    {
        list($password, $hash) = $this->generatePassword();

        $user = $this->update($userId, ['password' => $hash]);

        Mail::to($user)->send(new PasswordSetup($password));
    }

    protected function generatePassword(): array
    {
        $password = Str::random(8);

        return [$password, Hash::make($password)];
    }

    public function update(int $where, array $data): Model
    {
        $originUser = $this->first($where);
        $updatedUser = $this->repository->update($where, $data);

        if ($updatedUser->wasChanged('avatar_id')) {
            $this->mediaService->delete($originUser->avatar_id);
        }

        if (Arr::has($data, ['scripts'])) {
            $updatedUser->scripts()->sync($data['scripts']);
        }

        return $updatedUser;
    }

    public function profile(array $filters): Model
    {
        $relations = Arr::get($filters, 'with', []);

        return $this->with($relations)->find(request()->user()->id);
    }

    public function forgotPassword(string $email)
    {
        $user = $this->first(['email' => $email]);

        $hash = Password::createToken($user);

        Mail::to($user)->send(new ResetPassword($hash));
    }

    public function restorePassword(array $data)
    {
        $user = $this->first(['email' => $data['email']]);

        $this->update($user['id'], ['password' => Hash::make($data['password'])]);

        Password::deleteToken($user);
    }
}
