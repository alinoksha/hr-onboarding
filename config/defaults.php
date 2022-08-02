<?php

use App\Models\Role;

return [
    'permitted_image_extensions' => ['png', 'jpeg', 'jpg'],
    'permitted_video_extensions' => ['mp4', 'x-msvideo', 'mpeg', 'quicktime', '3gpp', 'x-ms-wmv', 'MP2T', 'x-mpegURL', 'x-flv'],
    'items_per_page' => 20,

    /*
    |--------------------------------------------------------------------------
    | Temp media link lifetime, minutes
    |--------------------------------------------------------------------------
    */
    'media_temp_link_lifetime' => 5,
    'access' => [
        'hr_onboarding' => [Role::EMPLOYEE],
        'hr_management' => [Role::ADMIN, Role::MANAGER, Role::SUPER_ADMIN],
    ],
    'app_name_header' => 'x-app-name'
];
