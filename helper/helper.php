<?php

use TheBachtiarz\Announcement\Interfaces\ConfigInterface;

/**
 * TheBachtiarz announcement config
 *
 * @param string|null $keyName config key name | null will return all
 * @return mixed|null
 */
function tbannconfig(?string $keyName = null): mixed
{
    $configName = ConfigInterface::ANNOUNCEMENT_CONFIG_NAME;

    return iconv_strlen($keyName)
        ? config("{$configName}.{$keyName}")
        : config("{$configName}");
}

/**
 * TheBachtiarz announcement route api file location
 *
 * @return string
 */
function tbannrouteapi(): string
{
    return base_path('vendor/thebachtiarz/laravel-announcement-v1/src/routes/announcement_api.php');
}
