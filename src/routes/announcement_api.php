<?php

use Illuminate\Support\Facades\Route;
use TheBachtiarz\Announcement\Http\Controllers\API\AnnouncementControler;

/**
 * Route group announcement
 * Route :: base_url/{{app_prefix}}/announcement/---
 */
Route::prefix('announcement')->controller(AnnouncementControler::class)->group(function () {

    /**
     * Route for get announcement(s)
     * Method :: GET
     * Route :: base_url/{{app_prefix}}/announcement/get
     */
    Route::get('get', 'getAnnouncements');

    Route::middleware('auth:sanctum')->group(function () {

        /**
         * Route for get own private announcement(s)
         * Method :: GET
         * Route :: base_url/{{app_prefix}}/announcement/get-own
         */
        Route::get('get-own', 'getOwnAnnouncements');

        /**
         * Route for create new announcement
         * Method :: POST
         * Route :: base_url/{{app_prefix}}/announcement/create
         */
        Route::post('create', 'createAnnouncement');

        /**
         * Route for update announcement
         * Method :: POST
         * Route :: base_url/{{app_prefix}}/announcement/update
         */
        Route::post('update', 'updateAnnouncement');

        /**
         * Route for delete announcement
         * Method :: POST
         * Route :: base_url/{{app_prefix}}/announcement/delete
         */
        Route::post('delete', 'deleteAnnouncement');

        /**
         * Route for restore announcement
         * Method :: POST
         * Route :: base_url/{{app_prefix}}/announcement/restore
         */
        Route::post('restore', 'restoreAnnouncement');
    });
});
