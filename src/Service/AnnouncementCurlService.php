<?php

namespace TheBachtiarz\Announcement\Service;

use TheBachtiarz\Announcement\Interfaces\{ConfigInterface, UrlDomainInterface};
use TheBachtiarz\Announcement\Traits\CurlBodyResolverTrait;
use TheBachtiarz\Toolkit\Helper\App\Response\DataResponse;

class AnnouncementCurlService
{
    use CurlBodyResolverTrait, DataResponse;

    /**
     * get announcement(s) list
     *
     * @param boolean $withDeleted default: false
     * @return array
     */
    public static function list(bool $withDeleted = false): array
    {
        $ownerResolver = self::ownerCodeResolve();

        if (!$ownerResolver['status'])
            return self::errorResponse($ownerResolver['message']);

        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => $ownerResolver['data']
        ];

        if ($withDeleted)
            $_body = array_merge($_body, [ConfigInterface::ANNOUNCEMENT_CONFIG_WITH_DELETED_NAME => '1']);

        return CurlService::setUrl(UrlDomainInterface::URL_DOMAIN_ANNOUNCEMENT_LIST_NAME)->setData($_body)->post();
    }

    /**
     * get announcement detail announcement code
     *
     * @param string $announcementCode
     * @param boolean $withDeleted default: false
     * @return array
     */
    public static function detail(string $announcementCode, bool $withDeleted = false): array
    {
        $ownerResolver = self::ownerCodeResolve();

        if (!$ownerResolver['status'])
            return self::errorResponse($ownerResolver['message']);

        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => $ownerResolver['data'],
            ConfigInterface::ANNOUNCEMENT_CONFIG_ANNOUNCEMENT_CODE_NAME => $announcementCode
        ];

        if ($withDeleted)
            $_body = array_merge($_body, [ConfigInterface::ANNOUNCEMENT_CONFIG_WITH_DELETED_NAME => '1']);

        return CurlService::setUrl(UrlDomainInterface::URL_DOMAIN_ANNOUNCEMENT_DETAIL_NAME)->setData($_body)->post();
    }

    /**
     * create new announcement
     *
     * @param string $announcementData
     * @return array
     */
    public static function create(string $announcementData): array
    {
        $ownerResolver = self::ownerCodeResolve();

        if (!$ownerResolver['status'])
            return self::errorResponse($ownerResolver['message']);

        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => $ownerResolver['data'],
            ConfigInterface::ANNOUNCEMENT_CONFIG_ANNOUNCEMENT_DATA_NAME => $announcementData
        ];

        return CurlService::setUrl(UrlDomainInterface::URL_DOMAIN_ANNOUNCEMENT_CREATE_NAME)->setData($_body)->post();
    }

    /**
     * update announcement announcement code
     *
     * @param string $announcementCode
     * @param string $announcementData
     * @param boolean $withDeleted default: false
     * @return array
     */
    public static function update(string $announcementCode, string $announcementData, bool $withDeleted = false): array
    {
        $ownerResolver = self::ownerCodeResolve();

        if (!$ownerResolver['status'])
            return self::errorResponse($ownerResolver['message']);

        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => $ownerResolver['data'],
            ConfigInterface::ANNOUNCEMENT_CONFIG_ANNOUNCEMENT_CODE_NAME => $announcementCode,
            ConfigInterface::ANNOUNCEMENT_CONFIG_ANNOUNCEMENT_DATA_NAME => $announcementData
        ];

        if ($withDeleted)
            $_body = array_merge($_body, [ConfigInterface::ANNOUNCEMENT_CONFIG_WITH_DELETED_NAME => '1']);

        return CurlService::setUrl(UrlDomainInterface::URL_DOMAIN_ANNOUNCEMENT_UPDATE_NAME)->setData($_body)->post();
    }

    /**
     * delete announcement announcement code
     *
     * @param string $announcementCode
     * @return array
     */
    public static function delete(string $announcementCode): array
    {
        $ownerResolver = self::ownerCodeResolve();

        if (!$ownerResolver['status'])
            return self::errorResponse($ownerResolver['message']);

        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => $ownerResolver['data'],
            ConfigInterface::ANNOUNCEMENT_CONFIG_ANNOUNCEMENT_CODE_NAME => $announcementCode
        ];

        return CurlService::setUrl(UrlDomainInterface::URL_DOMAIN_ANNOUNCEMENT_DELETE_NAME)->setData($_body)->post();
    }

    /**
     * restore announcement announcement code
     *
     * @param string $announcementCode
     * @return array
     */
    public static function restore(string $announcementCode): array
    {
        $ownerResolver = self::ownerCodeResolve();

        if (!$ownerResolver['status'])
            return self::errorResponse($ownerResolver['message']);

        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => $ownerResolver['data'],
            ConfigInterface::ANNOUNCEMENT_CONFIG_ANNOUNCEMENT_CODE_NAME => $announcementCode
        ];

        return CurlService::setUrl(UrlDomainInterface::URL_DOMAIN_ANNOUNCEMENT_RESTORE_NAME)->setData($_body)->post();
    }
}
