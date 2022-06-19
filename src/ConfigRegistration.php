<?php

namespace TheBachtiarz\Announcement;

use TheBachtiarz\Announcement\Interfaces\ConfigInterface;
use TheBachtiarz\Toolkit\ToolkitInterface;

class ConfigRegistration
{
    //

    // ? Public Methods
    /**
     * Config register process
     *
     * @return boolean
     */
    public function register(): bool
    {
        try {
            foreach ($this->configList() as $key => $config)
                config($config);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    // ? Private Methods
    /**
     * Register configuration list
     *
     * @return array
     */
    private function configList(): array
    {
        $registerConfig = [];

        // ! Keep cache
        $_keepCaches = tbtoolkitconfig('app_keep_cache_data');
        $registerConfig[] = [
            ToolkitInterface::TOOLKIT_CONFIG_NAME . '.app_keep_cache_data' => array_merge(
                $_keepCaches,
                [ConfigInterface::ANNOUNCEMENT_CACHE_AUTHENTICATOR_NAME]
            )
        ];

        return $registerConfig;
    }

    // ? Setter Modules
}
