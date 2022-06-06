<?php

namespace TheBachtiarz\Announcement\Interfaces;

interface ConfigInterface
{
    public const ANNOUNCEMENT_CONFIG_NAME = "thebachtiarz_announcement";

    public const ANNOUNCEMENT_CONFIG_PREFIX_NAME = "announcement";

    public const ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME = "owner_code";
    public const ANNOUNCEMENT_CONFIG_ANNOUNCEMENT_CODE_NAME = "announcement_code";
    public const ANNOUNCEMENT_CONFIG_ANNOUNCEMENT_DATA_NAME = "announcement_data";
    public const ANNOUNCEMENT_CONFIG_WITH_DELETED_NAME = "with_deleted";

    public const ANNOUNCEMENT_CACHE_PREFIX_NAME = "LaNcMrH";

    public const ANNOUNCEMENT_CACHE_TTL_DEFAULT = 3600;
}
