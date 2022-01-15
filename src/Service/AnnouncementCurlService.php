<?php

namespace TheBachtiarz\Announcement\Service;

use TheBachtiarz\Announcement\Interfaces\{ConfigInterface, UrlDomainInterface};

class AnnouncementCurlService
{
    /**
     * get announcement(s) list
     *
     * @param boolean $withDeleted default: false
     * @return array
     */
    public static function list(bool $withDeleted = false): array
    {
        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => tbannconfig(ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME)
        ];

        if ($withDeleted)
            $_body = array_merge($_body, ['with_deleted' => '1']);

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
        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => tbannconfig(ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME),
            'announcement_code' => $announcementCode
        ];

        if ($withDeleted)
            $_body = array_merge($_body, ['with_deleted' => '1']);

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
        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => tbannconfig(ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME),
            'announcement_data' => $announcementData
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
        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => tbannconfig(ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME),
            'announcement_code' => $announcementCode,
            'announcement_data' => $announcementData
        ];

        if ($withDeleted)
            $_body = array_merge($_body, ['with_deleted' => '1']);

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
        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => tbannconfig(ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME),
            'announcement_code' => $announcementCode
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
        $_body = [
            ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME => tbannconfig(ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME),
            'announcement_code' => $announcementCode
        ];

        return CurlService::setUrl(UrlDomainInterface::URL_DOMAIN_ANNOUNCEMENT_RESTORE_NAME)->setData($_body)->post();
    }
}
