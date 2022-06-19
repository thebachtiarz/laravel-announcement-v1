<?php

namespace TheBachtiarz\Announcement\Service;

use Illuminate\Support\Facades\Auth;
use TheBachtiarz\Announcement\Interfaces\ConfigInterface;
use TheBachtiarz\Toolkit\Cache\Service\Cache;
use TheBachtiarz\Toolkit\Helper\App\Encryptor\EncryptorHelper;
use TheBachtiarz\Toolkit\Helper\App\Log\ErrorLogTrait;
use TheBachtiarz\Toolkit\Helper\App\Response\DataResponse;

class AnnouncementService
{
    use DataResponse, ErrorLogTrait, EncryptorHelper;

    /**
     * Announcement code
     *
     * @var string
     */
    private static string $code;

    /**
     * Announcement message
     *
     * @var string
     */
    private static string $message;

    // ? Public Methods
    /**
     * Get all public announcement(s).
     *
     * @return array
     */
    public static function getPublicAnnouncement(): array
    {
        try {
            $_announcementData = self::getAnnouncementData();

            if (count($_announcementData)) {
                $_announcementFiltered = [];

                foreach ($_announcementData as $key => $_announcement) {
                    if (!$_announcement['is_deleted']) {
                        unset(
                            $_announcement['code'],
                            $_announcement['is_deleted'],
                            $_announcement['deleted']
                        );

                        $_announcementFiltered[] = $_announcement;
                    }
                }

                $_announcementData = $_announcementFiltered;
            }

            throw_if(!count($_announcementData), 'Exception', "There is no announcement(s)");

            return self::responseData(
                $_announcementData,
                sprintf("Announcement(s): %s found.", count($_announcementData)),
                200
            );
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * Get only owner announcement(s).
     * Require authentication user.
     *
     * @return array
     */
    public static function getPrivateAnnouncement(): array
    {
        try {
            throw_if(!Auth::hasUser(), 'Exception', "There is no session");

            $_announcementData = self::getAnnouncementData();

            if (count($_announcementData)) {
                $_ownAnnouncementCodes = self::getOwnerCodeAuth();

                $_myOwnAnnouncements = [];

                foreach ($_announcementData as $key => $_announcement)
                    if (in_array($_announcement['code'], $_ownAnnouncementCodes))
                        $_myOwnAnnouncements[] = $_announcement;

                $_announcementData = $_myOwnAnnouncements;
            }

            throw_if(!count($_announcementData), 'Exception', "There is no announcement(s)");

            return self::responseData(
                $_announcementData,
                sprintf("Announcement(s): %s found.", count($_announcementData)),
                200
            );
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * Create a new announcement
     *
     * @return array
     */
    public static function create(): array
    {
        try {
            throw_if(!Auth::hasUser(), 'Exception', "There is no session");

            $_newAnnouncement = AnnouncementCurlService::create(self::encryptMessage());

            throw_if(!$_newAnnouncement['status'], 'Exception', $_newAnnouncement['message']);

            self::setAnnouncementCodeAuth($_newAnnouncement['data']['code']);

            self::announcementCacheMutator(self::announcementDecryptMap($_newAnnouncement['data']));

            return self::responseData(
                self::announcementDecryptMap($_newAnnouncement['data']),
                $_newAnnouncement['message'],
                201
            );
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * Update existing announcement
     *
     * @return array
     */
    public static function update(): array
    {
        try {
            throw_if(!Auth::hasUser(), 'Exception', "There is no session");

            throw_if(!in_array(self::$code, self::getOwnerCodeAuth()), 'Exception', "Wrong authority announcement");

            $_updateAnnouncement = AnnouncementCurlService::update(self::$code, self::encryptMessage());

            throw_if(!$_updateAnnouncement['status'], 'Exception', $_updateAnnouncement['message']);

            self::announcementCacheMutator(self::announcementDecryptMap($_updateAnnouncement['data']));

            return self::responseData(
                self::announcementDecryptMap($_updateAnnouncement['data']),
                $_updateAnnouncement['message'],
                201
            );
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * Delete temporary existing announcement
     *
     * @return array
     */
    public static function delete(): array
    {
        try {
            throw_if(!Auth::hasUser(), 'Exception', "There is no session");

            throw_if(!in_array(self::$code, self::getOwnerCodeAuth()), 'Exception', "Wrong authority announcement");

            $_deleteAnnouncement = AnnouncementCurlService::delete(self::$code);

            throw_if(!$_deleteAnnouncement['status'], 'Exception', $_deleteAnnouncement['message']);

            self::announcementCacheMutator(self::announcementDecryptMap($_deleteAnnouncement['data']), true);

            return self::responseData(
                self::announcementDecryptMap($_deleteAnnouncement['data']),
                $_deleteAnnouncement['message'],
                201
            );
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    /**
     * Restore deleted announcement
     *
     * @return array
     */
    public static function restore(): array
    {
        try {
            throw_if(!Auth::hasUser(), 'Exception', "There is no session");

            throw_if(!in_array(self::$code, self::getOwnerCodeAuth()), 'Exception', "Wrong authority announcement");

            $_restoreAnnouncement = AnnouncementCurlService::restore(self::$code);

            throw_if(!$_restoreAnnouncement['status'], 'Exception', $_restoreAnnouncement['message']);

            self::announcementCacheMutator(self::announcementDecryptMap($_restoreAnnouncement['data']), true, false);

            return self::responseData(
                self::announcementDecryptMap($_restoreAnnouncement['data']),
                $_restoreAnnouncement['message'],
                201
            );
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    // ? Private Methods
    /**
     * Get announcement global data.
     * From cache or curl.
     *
     * @return array
     * @throws \Throwable
     */
    private static function getAnnouncementData(): array
    {
        try {
            $_announcementData = [];

            if (Cache::has(ConfigInterface::ANNOUNCEMENT_CACHE_PREFIX_NAME)) {
                /**
                 * Get announcement data from cache
                 */
                $_announcementCache = Cache::get(ConfigInterface::ANNOUNCEMENT_CACHE_PREFIX_NAME);

                $_announcementData = self::decrypt($_announcementCache);
            } else {
                /**
                 * Get announcement data from curl
                 */
                $_announcementCurl = AnnouncementCurlService::list();

                throw_if(!$_announcementCurl['status'], 'Exception', $_announcementCurl['message']);

                $_announcementData = self::announcementDataDecryptMap($_announcementCurl['data']);

                self::setCacheData($_announcementData);
            }

            return $_announcementData;
        } catch (\Throwable $th) {
            self::logCatch($th);

            throw $th;
        }
    }

    /**
     * Announcement cache mutator.
     * Used when create new, update, or soft delete announcement.
     *
     * @param array $announcementData Announcement data
     * @param boolean $softDelete Set true if want to use soft delete feature -- default: false
     * @param boolean $markAsDelete Set [true => delete] [false => restore] -- default: true
     * @return boolean
     */
    private static function announcementCacheMutator(
        array $announcementData = [],
        bool $softDelete = false,
        bool $markAsDelete = true
    ): bool {
        try {
            throw_if(empty($announcementData), 'Exception', "There is no announcement data");

            $_announcements = self::getAnnouncementData();

            if (count($_announcements)) {
                /**
                 * If @var $softDelete is true, then find announcement by code.
                 * If found, then set is_delete to true and update cache data.
                 *
                 * If @var $announcementData is not empty, get code from it.
                 * If empty, then get from global @var $code.
                 */
                if ($softDelete) {
                    foreach ($_announcements as $key => $_announcement) {
                        /**
                         * If ($_announcement['code'] === $_announcementCode)
                         */
                        $_announcementCode = @$announcementData['code'] ?: static::$code;

                        if (!strcmp($_announcement['code'], $_announcementCode)) {
                            $_announcements[$key]['is_deleted'] = $markAsDelete;
                            break;
                        }
                    }

                    /**
                     * Update existing announcement data cache.
                     * With new result announcement data.
                     */
                    self::setCacheData($_announcements);

                    /**
                     * Stop the process here
                     */
                    return true;
                }

                /**
                 * Flag for notice is @var $announcementData are new or not.
                 * First assume then announcement is new.
                 */
                $_isNewAnnouncement = true;

                /**
                 * Find announcement by code, if exist then update announcement.
                 * And set @var $_isNewAnnouncement to false.
                 */
                foreach ($_announcements as $key => $_announcement) {
                    /**
                     * If ($_announcement['code'] === $announcementData['code'])
                     */
                    if (!strcmp($_announcement['code'], $announcementData['code'])) {
                        $_announcements[$key] = $announcementData;
                        $_isNewAnnouncement = false;
                        break;
                    }
                }

                /**
                 * If find announcement by code is not exist, then assume is new announcement.
                 * Push a new announcement into cache.
                 */
                if ($_isNewAnnouncement)
                    $_announcements = array_merge($_announcements, [$announcementData]);

                /**
                 * Update existing announcement data cache.
                 * With new result announcement data.
                 */
                self::setCacheData($_announcements);
            } else {
                if ($softDelete) return false;

                /**
                 * This process will create fresh announcement data.
                 * And create new cache data.
                 */
                self::setCacheData([$announcementData]);
            }

            /**
             * Process finish
             */
            return true;
        } catch (\Throwable $th) {
            self::logCatch($th);
        }
    }

    /**
     * Announcement data decrypt map
     *
     * @param array $announcementata
     * @return array
     */
    private static function announcementDataDecryptMap(array $announcementata = []): array
    {
        try {
            $_newAnnouncementData = [];

            foreach ($announcementata as $key => $announcement)
                $_newAnnouncementData[] = self::announcementDecryptMap($announcement);

            return $_newAnnouncementData;
        } catch (\Throwable $th) {
            return [];
        }
    }

    /**
     * Announcement decrypt map
     *
     * @param array $announcement
     * @return array
     */
    private static function announcementDecryptMap(array $announcement): array
    {
        try {
            $announcement['announcement'] = self::decryptMessage($announcement['announcement']);
            $announcement['created'] = self::dateConvert($announcement['created']);
            $announcement['updated'] = self::dateConvert($announcement['updated']);
            $announcement['deleted'] = self::dateConvert($announcement['deleted']);

            return $announcement;
        } catch (\Throwable $th) {
            return [];
        }
    }

    /**
     * Message encryptor
     *
     * @return string
     */
    private static function encryptMessage(): string
    {
        return tbannconfig('encrypt_message')
            ? self::simpleEncrypt(self::$message)
            : self::jsonEncode(self::$message);
    }

    /**
     * Message decryptor
     *
     * @param string $message
     * @return mixed
     */
    private static function decryptMessage(string $message): mixed
    {
        return tbannconfig('encrypt_message')
            ? self::decrypt($message)
            : self::jsonDecode($message);
    }

    /**
     * Set announcement data to cache
     *
     * @param array $announcementdata
     * @return void
     */
    private static function setCacheData(array $announcementdata): void
    {
        try {
            $_announcementData = self::simpleEncrypt($announcementdata);

            Cache::setTemporary(
                ConfigInterface::ANNOUNCEMENT_CACHE_PREFIX_NAME,
                $_announcementData,
                ConfigInterface::ANNOUNCEMENT_CACHE_TTL_DEFAULT
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Get own announcement(s) code by auth code
     *
     * @return array
     */
    private static function getOwnerCodeAuth(): array
    {
        try {
            throw_if(!Auth::hasUser(), 'Exception', "Session not found!");

            throw_if(!Cache::has(ConfigInterface::ANNOUNCEMENT_CACHE_AUTHENTICATOR_NAME), 'Exception', "");

            /**
             * Get decrypt cache auth data
             *
             * @var array [user, code]
             */
            $_authCacheData = self::decrypt(Cache::get(ConfigInterface::ANNOUNCEMENT_CACHE_AUTHENTICATOR_NAME));

            $_userCode = Auth::user()->code;
            $_ownAuthCacheData = [];

            foreach ($_authCacheData as $key => $_authCache)
                if (!strcmp($_authCache['user'], $_userCode))
                    $_ownAuthCacheData[] = $_authCache['code'];

            return $_ownAuthCacheData;
        } catch (\Throwable $th) {
            return [];
        }
    }

    /**
     * Set announcement(s) code authenticator
     *
     * @param string $announcementCode
     * @return void
     */
    private static function setAnnouncementCodeAuth(string $announcementCode): void
    {
        try {
            throw_if(!Auth::hasUser(), 'Exception', "Session not found!");

            $_isAuthCacheDataExist = Cache::has(ConfigInterface::ANNOUNCEMENT_CACHE_AUTHENTICATOR_NAME);

            $_authCacheData = $_isAuthCacheDataExist
                ? self::decrypt(Cache::get(ConfigInterface::ANNOUNCEMENT_CACHE_AUTHENTICATOR_NAME))
                : [];

            $_authCacheData[] = [
                'user' => Auth::user()->code,
                'code' => $announcementCode
            ];

            Cache::set(
                ConfigInterface::ANNOUNCEMENT_CACHE_AUTHENTICATOR_NAME,
                self::simpleEncrypt($_authCacheData)
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Date convert
     *
     * @param string|null $date
     * @return string|null
     */
    private static function dateConvert(?string $date = null): string
    {
        try {
            throw_if(!$date, 'Exception', 'Date is null');

            return self::humanDateTime($date);
        } catch (\Throwable $th) {
            return '';
        }
    }

    // ? Setter Modules
    /**
     * Set announcement code
     *
     * @param string $code Announcement code
     * @return static
     */
    public static function setCode(string $code): static
    {
        self::$code = $code;

        return new static;
    }

    /**
     * Set announcement message
     *
     * @param string $message Announcement message
     * @return static
     */
    public static function setMessage(string $message): static
    {
        self::$message = $message;

        return new static;
    }
}
