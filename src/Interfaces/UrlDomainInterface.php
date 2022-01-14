<?php

namespace TheBachtiarz\Announcement\Interfaces;

class UrlDomainInterface
{
    public const URL_DOMAIN_BASE_AVAILABLE = [
        true => self::URL_DOMAIN_BASE_SECURE,
        false => self::URL_DOMAIN_BASE_UNSECURE
    ];

    public const URL_DOMAIN_TRANSACTION_AVAILABLE = [
        'owner-create',
        'owner-info',
        'announcement-list',
        'announcement-detail',
        'announcement-create',
        'announcement-update',
        'announcement-delete',
        'announcement-restore'
    ];

    public const URL_DOMAIN_BASE_SECURE = "https://announcement.thebachtiarz.com/";
    public const URL_DOMAIN_BASE_UNSECURE = "http://announcement.thebachtiarz.com/";

    public const URL_DOMAIN_OWNER_CREATE = "owner/create";
    public const URL_DOMAIN_OWNER_INFO = "owner/show";
    public const URL_DOMAIN_ANNOUNCEMENT_LIST = "announcement/get";
    public const URL_DOMAIN_ANNOUNCEMENT_DETAIL = "announcement/show";
    public const URL_DOMAIN_ANNOUNCEMENT_CREATE = "announcement/create";
    public const URL_DOMAIN_ANNOUNCEMENT_UPDATE = "announcement/update";
    public const URL_DOMAIN_ANNOUNCEMENT_DELETE = "announcement/delete";
    public const URL_DOMAIN_ANNOUNCEMENT_RESTORE = "announcement/restore";
}
