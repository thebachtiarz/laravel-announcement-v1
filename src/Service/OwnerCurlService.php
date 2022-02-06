<?php

namespace TheBachtiarz\Announcement\Service;

use TheBachtiarz\Announcement\Interfaces\{ConfigInterface, UrlDomainInterface};
use TheBachtiarz\Announcement\Traits\CurlBodyResolverTrait;
use TheBachtiarz\Toolkit\Helper\App\Response\DataResponse;

class OwnerCurlService
{
    use CurlBodyResolverTrait, DataResponse;

    /**
     * create new owner announcement
     *
     * @return array
     */
    public static function create(): array
    {
        return CurlService::setUrl(UrlDomainInterface::URL_DOMAIN_OWNER_CREATE_NAME)->post();
    }

    /**
     * get information about owner announcement
     *
     * @return array
     */
    public static function info(): array
    {
        $ownerResolver = self::ownerCodeResolve();

        if (!$ownerResolver['status'])
            return self::errorResponse($ownerResolver['message']);

        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => $ownerResolver['data']
        ];

        return CurlService::setUrl(UrlDomainInterface::URL_DOMAIN_OWNER_INFO_NAME)->setData($_body)->post();
    }
}
