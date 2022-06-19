<?php

namespace TheBachtiarz\Announcement\Http\Controllers\API;

use Illuminate\Http\Response;
use TheBachtiarz\Announcement\Http\Controllers\Controller;
use TheBachtiarz\Announcement\Http\Request\{CodeRequest, FullRequest, MessageRequest};
use TheBachtiarz\Announcement\Service\AnnouncementService;
use TheBachtiarz\Toolkit\Helper\App\Response\DataResponse;

class AnnouncementControler extends Controller
{
    use DataResponse;

    /**
     * Get public announcement(s)
     *
     * @return Response
     */
    public function getAnnouncements()
    {
        $_process = AnnouncementService::getPublicAnnouncement();

        return self::responseApiRest($_process);
    }

    /**
     * Get private owner announcement(s)
     *
     * @return Response
     */
    public function getOwnAnnouncements()
    {
        $_process = AnnouncementService::getPrivateAnnouncement();

        return self::responseApiRest($_process);
    }

    /**
     * Create new announcement
     *
     * @param MessageRequest $request
     * @return Response
     */
    public function createAnnouncement(MessageRequest $request)
    {
        $_process = AnnouncementService::setMessage($request->message)->create();

        return self::responseApiRest($_process);
    }

    /**
     * Update announcement
     *
     * @param FullRequest $request
     * @return Response
     */
    public function updateAnnouncement(FullRequest $request)
    {
        $_process = AnnouncementService::setCode($request->code)->setMessage($request->message)->update();

        return self::responseApiRest($_process);
    }

    /**
     * Delete announcement
     *
     * @param CodeRequest $request
     * @return Response
     */
    public function deleteAnnouncement(CodeRequest $request)
    {
        $_process = AnnouncementService::setCode($request->code)->delete();

        return self::responseApiRest($_process);
    }

    /**
     * Restore announcement
     *
     * @param CodeRequest $request
     * @return Response
     */
    public function restoreAnnouncement(CodeRequest $request)
    {
        $_process = AnnouncementService::setCode($request->code)->restore();

        return self::responseApiRest($_process);
    }
}
