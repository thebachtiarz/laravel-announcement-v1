<?php

use Illuminate\Support\Facades\Route;
use TheBachtiarz\Announcement\Controllers\API\AnnouncementControler;

/**
 * Route group announcement
 * Route :: base_url/{{app_prefix}}/announcement/---
 */
Route::prefix('announcement')->group(function () {

    /**
     * Route group owner
     * Route :: base_url/{{app_prefix}}/announcement/owner/---
     */
    Route::prefix('owner')->controller(AnnouncementControler::class)->group(function () {

        /**
         * Route for get owner announcement information
         * Method :: GET
         * Route :: base_url/{{app_prefix}}/announcement/owner/info
         */
        Route::get('info', 'ownerInfo');
    });
});
