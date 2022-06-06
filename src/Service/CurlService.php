<?php

namespace TheBachtiarz\Announcement\Service;

use TheBachtiarz\Announcement\Interfaces\UrlDomainInterface;
use TheBachtiarz\Toolkit\Helper\Curl\CurlRestService;

class CurlService
{
    use CurlRestService;

    /**
     * Base domain resolver
     *
     * @override
     * @param boolean $productionMode
     * @return string
     */
    private static function baseDomainResolver(bool $productionMode = true): string
    {
        return $productionMode ? tbannconfig('api_url_production') : tbannconfig('api_url_sandbox');
    }

    /**
     * Url end point resolver
     *
     * @override
     * @return string
     */
    private static function urlResolver(): string
    {
        $_baseDomain = self::baseDomainResolver(tbannconfig('is_production_mode'));

        $_prefix = tbannconfig('api_url_prefix');

        $_endPoint = UrlDomainInterface::URL_DOMAIN_TRANSACTION_AVAILABLE[self::$url];

        return "{$_baseDomain}/{$_prefix}/{$_endPoint}";
    }
}
