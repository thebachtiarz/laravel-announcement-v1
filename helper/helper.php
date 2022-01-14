<?php

use TheBachtiarz\Announcement\Interfaces\ConfigInterface;

/**
 * thebachtiarz announcement config
 *
 * @param string|null $keyName config key name | null will return all
 * @return mixed|null
 */
function tbannconfig(?string $keyName = null)
{
    $configName = ConfigInterface::ANNOUNCEMENT_CONFIG_NAME;

    return iconv_strlen($keyName)
        ? config("{$configName}.{$keyName}")
        : config("{$configName}");
}

/**
 * thebachtiarz announcement route api file location
 *
 * @return string
 */
function tbannrouteapi(): string
{
    return base_path('vendor/thebachtiarz/laravel-announcement-v1/src/routes/announcement_api.php');
}
