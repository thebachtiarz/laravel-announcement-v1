<?php

use Illuminate\Support\Facades\Route;
use TheBachtiarz\Announcement\Controllers\API\AnnouncementControler;

/**
 * route group announcement
 * route :: base_url/thebachtiarz/announcement/---
 */
Route::prefix('announcement')->group(function () {
    /**
     * route group owner
     * route :: base_url/thebachtiarz/announcement/owner/---
     */
    Route::prefix('owner')->group(function () {
        /**
         * route for get owner announcement information
         * route :: base_url/thebachtiarz/announcement/owner/info
         */
        Route::get('info', [AnnouncementControler::class, 'ownerInfo']);
    });
});
