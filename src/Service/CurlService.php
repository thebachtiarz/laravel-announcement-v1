<?php

namespace TheBachtiarz\Announcement\Service;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\{Http as CURL, Log};
use TheBachtiarz\Announcement\Interfaces\UrlDomainInterface;
use TheBachtiarz\Toolkit\Helper\App\Converter\ArrayHelper;

class CurlService
{
    use ArrayHelper;

    /**
     * url domain curl
     *
     * @var string
     */
    protected static string $url = "";

    /**
     * data body curl
     *
     * @var array
     */
    protected static array $data = [];

    // ? Public Methods
    /**
     * curl post
     *
     * @return array
     */
    public static function post(): array
    {
        $result = ['status' => false, 'data' => null];

        try {
            $_post = self::curl()->post(self::urlResolver(), self::dataResolver());

            $_result = self::jsonDecode($_post);

            /**
             * if there is validation errors
             */
            throw_if(in_array("errors", array_keys($_result)), 'Exception', $_result['message']);

            /**
             * if return status is not success
             */
            throw_if($_result['status'] !== "success", 'Exception', $_result['message']);

            $result['data'] = $_result['response_data'];
            $result['status'] = $_result['status'] === "success";
            $result['message'] = $_result['message'];
        } catch (\Throwable $th) {
            Log::channel('error')->warning($th->getMessage());

            $result['message'] = $th->getMessage();
        } finally {
            return $result;
        }
    }

    // ? Private Methods
    /**
     * create pre curl custom
     *
     * @return PendingRequest
     */
    private static function curl(): PendingRequest
    {
        return CURL::withHeaders([
            'Accept' => 'application/json'
        ]);
    }

    /**
     * base domain resolver
     *
     * @param boolean $secure
     * @return string
     */
    private static function baseDomainResolver(bool $secure = true): string
    {
        return UrlDomainInterface::URL_DOMAIN_BASE_AVAILABLE[$secure];
    }

    /**
     * url end point resolver
     *
     * @return string
     */
    private static function urlResolver(): string
    {
        $_baseDomain = self::baseDomainResolver(tbannconfig('secure_url'));

        $_prefix = tbannconfig('domain_prefix');

        $_endPoint = UrlDomainInterface::URL_DOMAIN_TRANSACTION_AVAILABLE[self::$url];

        return "{$_baseDomain}{$_prefix}/{$_endPoint}";
    }

    /**
     * data form resolver
     *
     * @return array
     */
    private static function dataResolver(): array
    {
        return self::$data;
    }

    // ? Setter Modules
    /**
     * Set url domain curl
     *
     * @param string $url url domain curl
     * @return self
     */
    public static function setUrl(string $url): self
    {
        self::$url = $url;

        return new self;
    }

    /**
     * Set data body curl
     *
     * @param array $data data body curl
     * @return self
     */
    public static function setData(array $data): self
    {
        self::$data = $data;

        return new self;
    }
}
