<?php

namespace TheBachtiarz\Announcement\Interfaces;

class UrlDomainInterface
{
    public const URL_DOMAIN_BASE_AVAILABLE = [
        true => self::URL_DOMAIN_BASE_SECURE,
        false => self::URL_DOMAIN_BASE_UNSECURE
    ];

    public const URL_DOMAIN_TRANSACTION_AVAILABLE = [
        self::URL_DOMAIN_OWNER_CREATE_NAME => self::URL_DOMAIN_OWNER_CREATE_PATH,
        self::URL_DOMAIN_OWNER_INFO_NAME => self::URL_DOMAIN_OWNER_INFO_PATH,
        self::URL_DOMAIN_ANNOUNCEMENT_LIST_NAME => self::URL_DOMAIN_ANNOUNCEMENT_LIST_PATH,
        self::URL_DOMAIN_ANNOUNCEMENT_DETAIL_NAME => self::URL_DOMAIN_ANNOUNCEMENT_DETAIL_PATH,
        self::URL_DOMAIN_ANNOUNCEMENT_CREATE_NAME => self::URL_DOMAIN_ANNOUNCEMENT_CREATE_PATH,
        self::URL_DOMAIN_ANNOUNCEMENT_UPDATE_NAME => self::URL_DOMAIN_ANNOUNCEMENT_UPDATE_PATH,
        self::URL_DOMAIN_ANNOUNCEMENT_DELETE_NAME => self::URL_DOMAIN_ANNOUNCEMENT_DELETE_PATH,
        self::URL_DOMAIN_ANNOUNCEMENT_RESTORE_NAME => self::URL_DOMAIN_ANNOUNCEMENT_RESTORE_PATH
    ];

    public const URL_DOMAIN_BASE_SECURE = "https://announcement.thebachtiarz.com";
    public const URL_DOMAIN_BASE_UNSECURE = "http://announcement.thebachtiarz.com";

    public const URL_DOMAIN_OWNER_CREATE_NAME = "owner-create";
    public const URL_DOMAIN_OWNER_INFO_NAME = "owner-info";
    public const URL_DOMAIN_ANNOUNCEMENT_LIST_NAME = "announcement-list";
    public const URL_DOMAIN_ANNOUNCEMENT_DETAIL_NAME = "announcement-detail";
    public const URL_DOMAIN_ANNOUNCEMENT_CREATE_NAME = "announcement-create";
    public const URL_DOMAIN_ANNOUNCEMENT_UPDATE_NAME = "announcement-update";
    public const URL_DOMAIN_ANNOUNCEMENT_DELETE_NAME = "announcement-delete";
    public const URL_DOMAIN_ANNOUNCEMENT_RESTORE_NAME = "announcement-restore";

    public const URL_DOMAIN_OWNER_CREATE_PATH = "owner/create";
    public const URL_DOMAIN_OWNER_INFO_PATH = "owner/show";
    public const URL_DOMAIN_ANNOUNCEMENT_LIST_PATH = "announcement/get";
    public const URL_DOMAIN_ANNOUNCEMENT_DETAIL_PATH = "announcement/show";
    public const URL_DOMAIN_ANNOUNCEMENT_CREATE_PATH = "announcement/create";
    public const URL_DOMAIN_ANNOUNCEMENT_UPDATE_PATH = "announcement/update";
    public const URL_DOMAIN_ANNOUNCEMENT_DELETE_PATH = "announcement/delete";
    public const URL_DOMAIN_ANNOUNCEMENT_RESTORE_PATH = "announcement/restore";
}
