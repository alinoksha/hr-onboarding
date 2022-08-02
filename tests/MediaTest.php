<?php

namespace Tests;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RonasIT\Support\Traits\FilesUploadTrait;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;

class MediaTest extends TestCase
{
    use FilesUploadTrait;

    const MEDIA_URL = '/api/media';

    protected $file;

    public function setUp(): void
    {
        parent::setUp();

        $this->file = UploadedFile::fake()->image('file.png', 600, 600);
    }

    public function testCreateAsAdmin()
    {
        $response = $this->actingAs($this->admin)->json('post', self::MEDIA_URL, ['file' => $this->file]);

        $response->assertOk();

        $responseData = $response->json();

        $this->assertEqualsFixture('create_media.json', Arr::except($responseData, ['name', 'link', 'source']));

        $this->assertDatabaseHas('media', [
            'id' => 5,
            'name' => $responseData['name'],
            'user_id' => $this->admin->id,
        ]);
    }

    public function testCreateAsManager()
    {
        $response = $this->actingAs($this->manager)->json('post', self::MEDIA_URL, ['file' => $this->file]);

        $response->assertOk();
    }

    public function testCreateAsEmployee()
    {
        $response = $this->actingAs($this->employee)->json('post', self::MEDIA_URL, ['file' => $this->file]);

        $response->assertOk();
    }

    public function testUploadImageMoreMaxSize()
    {
        $file = UploadedFile::fake()->image('avatar.jpg', 100, 100)->size(20480);

        $response = $this->actingAs($this->admin)->json('post', self::MEDIA_URL, ['file' => $file]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson(['message' => 'Maximum media size - 10485760.']);
    }

    public function testUploadVideoMoreMaxSize()
    {
        $file = UploadedFile::fake()->create('video.avi', 100, 100)->size(153600)->mimeType('video/x-msvideo');

        $response = $this->actingAs($this->admin)->json('post', self::MEDIA_URL, ['file' => $file]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson(['message' => 'Maximum media size - 104857600.']);
    }

    public function testUploadIncorrectType()
    {
        $file = UploadedFile::fake()->image('img.svg', 100, 100);

        $response = $this->actingAs($this->admin)->json('post', self::MEDIA_URL, ['file' => $file]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson(['message' => 'Media with type svg+xml can not been uploaded.']);
    }

    public function testUploadUpperCaseType()
    {
        $file = UploadedFile::fake()->image('img.PNG', 100, 100);

        $response = $this->actingAs($this->admin)->json('post', self::MEDIA_URL, ['file' => $file]);

        $response->assertOk();
    }

    public function testCreateCheckResponse()
    {
        $response = $this->actingAs($this->admin)->json('post', self::MEDIA_URL, ['file' => $this->file]);

        $responseData = $response->json();

        $this->assertDatabaseHas('media', [
            'id' => 5,
            'link' => $responseData['link']
        ]);

        Storage::assertExists($this->getFilePathFromUrl($responseData['link']));
    }

    public function testCreateNoAuth()
    {
        $response = $this->json('post', self::MEDIA_URL, ['file' => $this->file]);

        $response->assertUnauthorized();
    }

    public function testDelete()
    {
        Storage::putFileAs('/', new File($this->file), 'file.png');

        $response = $this->actingAs($this->admin)->json('delete', self::MEDIA_URL . '/1');

        $response->assertNoContent();

        $this->assertDatabaseMissing('media', [
            'id' => 1
        ]);

        Storage::assertMissing('file.png');
    }

    public function testDeleteAsNotOwner()
    {
        $response = $this->actingAs($this->admin)->json('delete', self::MEDIA_URL . '/4');

        $response->assertForbidden();
    }

    public function testDeleteInvalidId()
    {
        $response = $this->actingAs($this->admin)->json('delete', self::MEDIA_URL . '/dfsdfss');

        $response->assertNotFound();
    }

    public function testDeleteWithoutParam()
    {
        $response = $this->actingAs($this->admin)->json('delete', self::MEDIA_URL);

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testDeleteNotFound()
    {
        $response = $this->actingAs($this->admin)->json('delete', self::MEDIA_URL . '/0');

        $response->assertNotFound();
    }

    public function testGet()
    {
        $response = $this->actingAs($this->admin)->json('get', self::MEDIA_URL . '/1');

        $response->assertOk();

        $this->assertEqualsFixture('get_media.json', $response->json());
    }

    public function testGetNotFound()
    {
        $response = $this->actingAs($this->admin)->json('get', self::MEDIA_URL . '/0');

        $response->assertNotFound();
    }

    public function testGetWithoutParam()
    {
        $response = $this->actingAs($this->admin)->json('get', self::MEDIA_URL);

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testGetNotFoundContent()
    {
        $response = $this->actingAs($this->admin)->json('get', self::MEDIA_URL . '/0/content');

        $response->assertNotFound();
    }

    public function testGetContent()
    {
        Storage::putFileAs('/', new File($this->file), 'file.png');

        $response = $this->actingAs($this->admin)->json('get', self::MEDIA_URL . '/1/content');

        $response->assertRedirect('http://localhost/storage/file.png');
    }

    public function testGetPrivateContentNotAuth()
    {
        $response = $this->json('get', self::MEDIA_URL . '/2/content');

        $response->assertUnauthorized();
    }

    public function testIsPublicFieldFilled()
    {
        $response = $this->actingAs($this->admin)->json('post', self::MEDIA_URL, [
            'file' => $this->file,
            'is_public' => true
        ]);

        $responseData = $response->json();

        $this->assertDatabaseHas('media', [
            'id' => 5,
            'link' => $responseData['link'],
            'is_public' => true
        ]);

        Storage::assertExists($this->getFilePathFromUrl($responseData['link']));
    }

    public function testGetPublicContentNotAuth()
    {
        Storage::putFileAs('/', new File($this->file), 'file.png');

        $response = $this->json('get', self::MEDIA_URL . '/1/content');

        $response->assertRedirect('http://localhost/storage/file.png');
    }

    public function tearDown(): void
    {
        $this->clearUploadedFilesFolder();

        parent::tearDown();
    }
}
