<?php

use Illuminate\Support\Facades\Route;
use TheBachtiarz\Announcement\Controllers\API\AnnouncementControler;

/**
 * route group announcement
 * route :: base_url/{{app_prefix}}/announcement/---
 */
Route::prefix('announcement')->group(function () {

    /**
     * route group owner
     * route :: base_url/{{app_prefix}}/announcement/owner/---
     */
    Route::prefix('owner')->controller(AnnouncementControler::class)->group(function () {

        /**
         * route for get owner announcement information
         * method :: GET
         * route :: base_url/{{app_prefix}}/announcement/owner/info
         */
        Route::get('info', 'ownerInfo');
    });
});
