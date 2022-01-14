<?php

namespace TheBachtiarz\Announcement;

use Illuminate\Support\ServiceProvider;
use TheBachtiarz\Announcement\Interfaces\ConfigInterface;

class AnnouncementServiceProvider extends ServiceProvider
{
    /**
     * register module announcement
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * boot module announcement
     *
     * @return void
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/' . ConfigInterface::ANNOUNCEMENT_CONFIG_NAME . '.php' => config_path(ConfigInterface::ANNOUNCEMENT_CONFIG_NAME . '.php'),
            ], 'thebachtiarz-announcement-config');
        }
    }
}
