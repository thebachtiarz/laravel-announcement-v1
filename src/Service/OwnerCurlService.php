<?php

namespace TheBachtiarz\Announcement\Service;

use TheBachtiarz\Announcement\Interfaces\{ConfigInterface, UrlDomainInterface};

class OwnerCurlService
{
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
        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => tbannconfig(ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME)
        ];

        return CurlService::setUrl(UrlDomainInterface::URL_DOMAIN_OWNER_INFO_NAME)->setData($_body)->post();
    }
}
