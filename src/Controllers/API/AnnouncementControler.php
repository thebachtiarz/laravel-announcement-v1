<?php

namespace TheBachtiarz\Announcement\Controllers\API;

use Illuminate\Http\Response;
use TheBachtiarz\Announcement\Controllers\Controller;
use TheBachtiarz\Announcement\Service\OwnerCurlService;

class AnnouncementControler extends Controller
{
    /**
     * get owner announcement information
     *
     * @return Response
     */
    public function ownerInfo()
    {
        return OwnerCurlService::info();
    }
}
