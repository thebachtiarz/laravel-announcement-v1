<?php

namespace TheBachtiarz\Announcement\Interfaces;

class UrlDomainInterface
{
    public const URL_DOMAIN_BASE_AVAILABLE = [
        true => self::URL_DOMAIN_BASE_SECURE,
        false => self::URL_DOMAIN_BASE_UNSECURE
    ];

    public const URL_DOMAIN_BASE_SECURE = "https://announcement.thebachtiarz.com/";
    public const URL_DOMAIN_BASE_UNSECURE = "http://announcement.thebachtiarz.com/";
}
