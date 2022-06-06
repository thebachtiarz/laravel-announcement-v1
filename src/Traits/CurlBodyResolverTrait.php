<?php

namespace TheBachtiarz\Announcement\Traits;

use TheBachtiarz\Announcement\Interfaces\ConfigInterface;

/**
 * Owner Code Resolver Trait
 */
trait CurlBodyResolverTrait
{
    /**
     * Owner code
     *
     * @var string|null
     */
    protected static ?string $ownerCode = null;

    // ? Private Methods
    /**
     * Resolve owner code for body curl process
     *
     * @return array
     */
    private static function ownerCodeResolve(): array
    {
        $result = ['status' => false, 'data' => null, 'message' => ''];

        try {
            throw_if(tbannconfig('is_multi_owner') && !self::$ownerCode, 'Exception', "Owner code required");

            $ownerCode = tbannconfig('is_multi_owner')
                ? self::$ownerCode
                : tbannconfig(ConfigInterface::ANNOUNCEMENT_CONFIG_OWNER_CODE_NAME);

            $result['status'] = true;
            $result['data'] = $ownerCode;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
        } finally {
            return $result;
        }
    }

    // ? Setter Modules
    /**
     * Set owner code
     *
     * @param string $ownerCode owner code
     * @return self
     */
    public static function setOwnerCode(string $ownerCode): self
    {
        self::$ownerCode = $ownerCode;

        return new self;
    }
}
