<?php

namespace TheBachtiarz\Announcement\Service;

use TheBachtiarz\Announcement\Interfaces\ConfigInterface;
use TheBachtiarz\Toolkit\Cache\Service\Cache;
use TheBachtiarz\Toolkit\Helper\App\Converter\ArrayHelper;
use TheBachtiarz\Toolkit\Helper\App\Encryptor\EncryptorHelper;
use TheBachtiarz\Toolkit\Helper\App\Log\ErrorLogTrait;

class AnnouncementCacheService
{
    use ErrorLogTrait, ArrayHelper, EncryptorHelper;

    /**
     * Announcements
     *
     * @var array
     */
    protected static array $announcements;

    /**
     * Announcement
     *
     * @var array
     */
    protected static array $announcement;

    /**
     * Announcement code
     *
     * @var string|null
     */
    protected static ?string $announcementCode = null;

    // ? Public Methods
    /**
     * Get announcements data
     *
     * @param boolean $withDeleted return with deleted announcement(s)
     * @return array
     */
    public static function getAnnouncements(bool $withDeleted = false): array
    {
        try {
            if (Cache::has(ConfigInterface::ANNOUNCEMENT_CACHE_PREFIX_NAME)) {
                $_announcements = Cache::get(ConfigInterface::ANNOUNCEMENT_CACHE_PREFIX_NAME);
            } else {
                $_announcements = AnnouncementCurlService::list();
                throw_if(!$_announcements['status'], 'Exception', $_announcements['message']);

                $_announcements = self::announcementDataMap($_announcements['data']);

                Cache::setTemporary(
                    ConfigInterface::ANNOUNCEMENT_CACHE_PREFIX_NAME,
                    $_announcements,
                    ConfigInterface::ANNOUNCEMENT_CACHE_TTL_DEFAULT
                );
            }

            return self::announcementDeletedResolver($_announcements, $withDeleted);
        } catch (\Throwable $th) {
            self::logCatch($th);

            return [];
        }
    }

    /**
     * Create new announcement
     *
     * @return array
     */
    public static function createAnnouncement(): array
    {
        try {
            $_create = AnnouncementCurlService::create(self::encryptMessageResolver());
            throw_if(!$_create['status'], 'Exception', $_create['message']);

            self::createOrUpdateCacheData(self::announcementMap($_create['data']));

            return [
                'status' => $_create['status'],
                'message' => $_create['message']
            ];
        } catch (\Throwable $th) {
            self::logCatch($th);

            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Update announcement
     *
     * @return array
     */
    public static function updateAnnouncement(): array
    {
        try {
            $_update = AnnouncementCurlService::update(self::$announcementCode, self::encryptMessageResolver());
            throw_if(!$_update['status'], 'Exception', $_update['message']);

            self::createOrUpdateCacheData(self::announcementMap($_update['data']));

            return [
                'status' => $_update['status'],
                'message' => $_update['message']
            ];
        } catch (\Throwable $th) {
            self::logCatch($th);

            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Delete announcement
     *
     * @return array
     */
    public static function deleteAnnouncement(): array
    {
        try {
            $_delete = AnnouncementCurlService::delete(self::$announcementCode);
            throw_if(!$_delete['status'], 'Exception', $_delete['message']);

            self::createOrUpdateCacheData(self::announcementMap($_delete['data']));

            return [
                'status' => $_delete['status'],
                'message' => $_delete['message']
            ];
        } catch (\Throwable $th) {
            self::logCatch($th);

            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Restore deleted announcement
     *
     * @return array
     */
    public static function restoreAnnouncement(): array
    {
        try {
            $_restore = AnnouncementCurlService::restore(self::$announcementCode);
            throw_if(!$_restore['status'], 'Exception', $_restore['message']);

            self::createOrUpdateCacheData(self::announcementMap($_restore['data']));

            return [
                'status' => $_restore['status'],
                'message' => $_restore['message']
            ];
        } catch (\Throwable $th) {
            self::logCatch($th);

            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    // ? Private Methods
    /**
     * Message encryptor resolver
     *
     * @return string
     */
    private static function encryptMessageResolver(): string
    {
        return tbannconfig('encrypt_message')
            ? self::simpleEncrypt(self::$announcement)
            : self::jsonEncode(self::$announcement);
    }

    /**
     * Message decryptor resolver
     *
     * @param string $message
     * @return array
     */
    private static function decryptMessageResolver(string $message): array
    {
        return tbannconfig('encrypt_message')
            ? self::decrypt($message)
            : self::jsonDecode($message);
    }

    /**
     * Announcement deleted resolver.
     * Filter is deleted.
     *
     * @param array $announcements
     * @param boolean $withDeleted
     * @return array
     */
    private static function announcementDeletedResolver(array $announcements, bool $withDeleted = false): array
    {
        $_announcementFiltered = [];

        if (!$withDeleted) {
            foreach ($announcements as $key => $announcement)
                if (!$announcement['is_deleted'])
                    $_announcementFiltered[] = $announcement;

            $announcements = $_announcementFiltered;
        }

        return $announcements;
    }

    /**
     * Announcement map
     *
     * @param array $announcement
     * @return array
     */
    private static function announcementMap(array $announcement): array
    {
        try {
            $announcement['announcement'] = self::decryptMessageResolver($announcement['announcement']);

            return $announcement;
        } catch (\Throwable $th) {
            return [];
        }
    }

    /**
     * Announcement data map
     *
     * @param array $announcements
     * @return array
     */
    private static function announcementDataMap(array $announcements = []): array
    {
        try {
            $_newAnnouncementData = [];

            foreach ($announcements as $key => $announcement)
                $_newAnnouncementData[] = self::announcementMap($announcement);

            return $_newAnnouncementData;
        } catch (\Throwable $th) {
            return [];
        }
    }

    /**
     * Create or update cache data
     *
     * @param array $announcementValue
     * @return void
     */
    private static function createOrUpdateCacheData(array $announcementValue = []): void
    {
        try {
            $_announcements = self::getAnnouncements();

            if (count($_announcements)) {
                /**
                 * will create or update announcement data
                 */

                $_isNewAnnouncement = true;

                foreach ($_announcements as $key => $_announcement) {
                    /**
                     * check update announcement by code
                     */

                    if ($_announcement['code'] === $announcementValue['code']) {
                        $_announcements[$key] = $announcementValue;
                        $_isNewAnnouncement = false;
                        break;
                    }
                }

                if ($_isNewAnnouncement) {
                    /**
                     * will create new announcement
                     */

                    Cache::setTemporary(
                        ConfigInterface::ANNOUNCEMENT_CACHE_PREFIX_NAME,
                        array_merge($_announcements, [$announcementValue]),
                        ConfigInterface::ANNOUNCEMENT_CACHE_TTL_DEFAULT
                    );
                } else {
                    /**
                     * will update announcement by code
                     */

                    Cache::setTemporary(
                        ConfigInterface::ANNOUNCEMENT_CACHE_PREFIX_NAME,
                        $_announcements,
                        ConfigInterface::ANNOUNCEMENT_CACHE_TTL_DEFAULT
                    );
                }
            } else {
                /**
                 * will create fresh announcement data
                 */

                Cache::setTemporary(
                    ConfigInterface::ANNOUNCEMENT_CACHE_PREFIX_NAME,
                    [$announcementValue],
                    ConfigInterface::ANNOUNCEMENT_CACHE_TTL_DEFAULT
                );
            }
        } catch (\Throwable $th) {
        }
    }

    /**
     * Update is deleted announcement.
     * Re-arrange deleted announcement.
     *
     * @param string $type
     * @return void
     */
    private static function updateIsDeletedAnnouncement(string $type = "delete"): void
    {
        try {
            $_announcements = self::getAnnouncements();

            if (count($_announcements)) {
                foreach ($_announcements as $key => $_announcement) {
                    if ($_announcement['code'] === self::$announcementCode) {
                        $_announcements[$key]['is_deleted'] = $type === "delete";
                        break;
                    }
                }
            }

            Cache::setTemporary(
                ConfigInterface::ANNOUNCEMENT_CACHE_PREFIX_NAME,
                $_announcements,
                ConfigInterface::ANNOUNCEMENT_CACHE_TTL_DEFAULT
            );
        } catch (\Throwable $th) {
        }
    }

    // ? Setter Modules
    /**
     * Set announcements
     *
     * @param array $announcements announcements
     * @return self
     */
    public static function setAnnouncements(array $announcements): self
    {
        self::$announcements = $announcements;

        return new self;
    }

    /**
     * Set announcement
     *
     * @param array $announcement announcement
     * @return self
     */
    public static function setAnnouncement(array $announcement): self
    {
        self::$announcement = $announcement;

        return new self;
    }

    /**
     * Set announcement code
     *
     * @param string $announcementCode announcement code
     * @return self
     */
    public static function setAnnouncementCode(string $announcementCode): self
    {
        self::$announcementCode = $announcementCode;

        return new self;
    }
}
