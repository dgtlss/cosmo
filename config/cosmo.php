<?php
/*
 * Cosmo v1.0.0 - A simple error handler for Laravel
 *
 * (c) Nathan Langer (dgtlss) <nathanlanger@googlemail.com> 2023-2024
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

return[

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | Send a notification when an error occurs to the users preferred method or methods.
    | When a notification has been sent for an error, this will be shown on the error page.
    |
    */

    'notifications' => true,

    /*
    |--------------------------------------------------------------------------
    | Guarding
    |--------------------------------------------------------------------------
    |
    | Setup guarding to specify which users should receive notifications, and which should not.
    | If you want to use multiple guards, separate them with a comma.
    | Or if you want to specify a specific user group by defining a boolean.
    |
    */

    'guarding' => [
        'users' => '', // comma seperated user email addresses
        'roles' => '' // is_admin, is_superadmin, is_systemadmin, etc.
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Methods
    |--------------------------------------------------------------------------
    |
    | The methods to use when sending a notification.
    | Options: "mail", "slack", "discord", "telegram", "pushover", "twilio", "teams"
    | If you want to use multiple methods, separate them with a comma.
    |
    */

    'notification_methods' => [''],

    /* 
    |--------------------------------------------------------------------------
    | Notification Credentials
    |--------------------------------------------------------------------------
    |
    | The credentials to use when sending a notification.
    | If you want to use multiple methods, fill in the credentials for each method.
    | Cosmo will check if the credentials are filled in before sending a notification.
    |
    */

    'notification_credentials' => [
        'mail' => [
            'from' => '',
            'from_name' => '',
            'to' => '',
            'smtp_host' => '',
            'smtp_port' => '',
            'smtp_username' => '',
            'smtp_password' => '',
            'smtp_encryption' => ''
        ],
        'slack' => [
            'webhook' => '',
            'channel' => ''
        ],
        'discord' => [
            'webhook' => '',
            'username' => '',
            'avatar' => ''
        ],
        'telegram' => [
            'bot_token' => '',
            'chat_id' => '',
            'parse_mode' => ''
        ],
        'pushover' => [
            'token' => '',
            'user' => '',
            'priority' => '',
            'sound' => '',
            'url' => '',
            'url_title' => ''
        ],
        'twilio' => [
            'sid' => '',
            'token' => '',
            'from' => '',
            'to' => ''
        ],
        'teams' => [
            'webhook' => '',
        ]
    ],
];