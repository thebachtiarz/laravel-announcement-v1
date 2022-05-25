<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API URL Production
    |--------------------------------------------------------------------------
    |
    | API URL Production site.
    |
    */
    'api_url_production' => "https://announcement.thebachtiarz.com",

    /*
    |--------------------------------------------------------------------------
    | API URL Sandbox
    |--------------------------------------------------------------------------
    |
    | API URL Sandbox site.
    |
    */
    'api_url_sandbox' => "http://appannouncement.test",

    /*
    |--------------------------------------------------------------------------
    | Url Prefix
    |--------------------------------------------------------------------------
    |
    | Set url prefix.
    |
    */
    'api_url_prefix' => "XfIrfTAnPNx",

    /*
    |--------------------------------------------------------------------------
    | Warehouse Mode
    |--------------------------------------------------------------------------
    |
    | Set mode of warehouse project.
    |
    */
    'is_production_mode' => true,


    /*
    |--------------------------------------------------------------------------
    | Announcement Owner Type
    |--------------------------------------------------------------------------
    |
    | Define this application is using multi owner(s).
    |
    */
    'is_multi_owner' => false,

    /*
    |--------------------------------------------------------------------------
    | Owner Announcement code
    |--------------------------------------------------------------------------
    |
    | Define owner code for announcement.
    |
    | ! this config is mutable !
    |
    */
    'owner_code' => "",

    /*
    |--------------------------------------------------------------------------
    | Encrypt Announcement Message
    |--------------------------------------------------------------------------
    |
    | Use encryption (Laravel's default encryption) for announcement message.
    |
    */
    'encrypt_message' => false,
];
