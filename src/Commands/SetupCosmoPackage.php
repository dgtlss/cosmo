<?php

namespace Dgtlss\Cosmo\Commands;

use Illuminate\Console\Command;
use function Laravel\Prompts\text;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\info;
use function Laravel\Prompts\table;
use Illuminate\Support\Facades\Config;

Class SetupCosmoPackage extends Command
{
    protected $signature = 'cosmo:setup';
    protected $description = 'Setup Cosmo Package';

    public function handle()
    {
        info('👋 Welcome to the Cosmo setup wizard!');
        $wantNotifications = confirm('Do you want to receive notifications when an error occurs?');
        if($wantNotifications){
            $this->enableNotificationMethods();
            // Remove the blank entry from the notification_methods array in the config
            $methods = config('cosmo.notification_methods');
            array_shift($methods);
            config::write('cosmo.notification_methods', $methods);
        }else{
            config::write('cosmo.notifications', false);
            info('📨 No problem! You can always enable notifications later.');
        }

        $guarding = confirm('Do you want to setup guarding?');
        if($guarding){
            $this->setupGuarding();
        }else{
            info('🔒 No problem! You can always add users or roles to guarding later.');
        }

        // Finish the setup
        info('🎉 Great! Setup is now complete!');

    }

    private function enableNotificationMethods()
    {
        info('📨 Great! Let\'s setup your notification methods.');
        config::write('cosmo.notifications', true);
        $notificationMethod = select(
            'Which notification methods do you want to use?',
            ['Mail', 'Slack', 'Discord', 'Telegram', 'Twilio', 'Teams', 'Pushover', ],
            hint: 'You can add additional methods in the config file at any time.'
        );
        if($notificationMethod == 'Mail'){
            $this->setupMailNotifications();
        }
        if($notificationMethod == 'Slack'){
            $this->setupSlackNotifications();
        }
        if($notificationMethod == 'Discord'){
            $this->setupDiscordNotifications();
        }
        if($notificationMethod == 'Telegram'){
            $this->setupTelegramNotifications();
        }
        if($notificationMethod == 'Twilio'){
            $this->setupTwilioNotifications();
        }
        if($notificationMethod == 'Teams'){
            $this->setupTeamsNotifications();
        }
    }

    private function setupGuarding()
    {
        info('🔒 Great! Let\'s setup guarding.');
        $guardingUsers = confirm('Do you want to guard specific users?');
        if($guardingUsers){
            $users = text('Users', hint: 'Enter the email addresses of the users you want to guard, separated by a comma.');
            info('🔒 Great! Saving users!');
            config::write('cosmo.guarding.users', $users);
        }
        $guardingRoles = confirm('Do you want to guard specific roles?');
        if($guardingRoles){
            $roles = text('Roles', hint: 'Enter the roles you want to guard, separated by a comma. For example is_admin, is_superuser, etc.');
            info('🔒 Great! Saving roles!');
            config::write('cosmo.guarding.roles', $roles);
        }
    }

    private function checkAnotherNotificationMethod()
    {
        $anotherMethod = false;
        $anotherMethod = confirm('Do you want to add another notification method?');
        if($anotherMethod){
            $this->enableNotificationMethods();
        }
    }

    private function addMethodToArray($method)
    {
        $methods = config('cosmo.notification_methods');
        array_push($methods, $method);
        config::write('cosmo.notification_methods', $methods);
    }

    private function setupMailNotifications()
    {
        info('Setting up mail notifications...');
        $from = text('From', hint: 'The email address to send the notification from.');
        $fromName = text('From Name', hint: 'The name to send the notification from.');
        $to = text('To', hint: 'The email address to send the notification to.');
        $smtpHost = text('SMTP Host', hint: 'The SMTP host to use for sending the notification.');
        $smtpPort = text('SMTP Port', hint: 'The SMTP port to use for sending the notification.');
        $smtpUsername = text('SMTP Username', hint: 'The SMTP username to use for sending the notification.');
        $smtpPassword = text('SMTP Password', hint: 'The SMTP password to use for sending the notification.');
        $smtpEncryption = select(
            'SMTP Encryption',
            ['None', 'TLS', 'SSL'],
            hint: 'The SMTP encryption to use for sending the notification.'
        );

        // Put the results in an array
        $results = [
            'from' => $from,
            'from_name' => $fromName,
            'to' => $to,
            'smtp_host' => $smtpHost,
            'smtp_port' => $smtpPort,
            'smtp_username' => $smtpUsername,
            'smtp_password' => $smtpPassword,
            'smtp_encryption' => $smtpEncryption
        ];
        // Relay the information back to the user
        info('📨 Great! Here\'s what we\'ve got so far:');
        table(
            ['From', 'To', 'SMTP Host', 'SMTP Port', 'SMTP Username', 'SMTP Password', 'SMTP Encryption'],
            [$results]
        );

        // Ask the user to confirm the information
        $confirm = confirm('Is this correct?');

        if(!$confirm){
            info('No problem! Let\'s try again.');
            $this->setupMailNotifications();
        }else{
            info('Great! Saving your settings...');
            // Save the settings to the cosmo config file
            config::write('cosmo.notification_credentials.mail.from', $from);
            config::write('cosmo.notification_credentials.mail.to', $to);
            config::write('cosmo.notification_credentials.mail.smtp_host', $smtpHost);
            config::write('cosmo.notification_credentials.mail.smtp_port', $smtpPort);
            config::write('cosmo.notification_credentials.mail.smtp_username', $smtpUsername);
            config::write('cosmo.notification_credentials.mail.smtp_password', $smtpPassword);
            if($smtpEncryption != 'None'){
                config::write('cosmo.notification_credentials.mail.smtp_encryption', $smtpEncryption);
            }
            $this->addMethodToArray('mail');
            info('🎉 Mail configuration saved successfully!');
            // check if the user wants to add another method
            $this->checkAnotherNotificationMethod();

        }

    }

    private function setupSlackNotifications()
    {
        info('Setting up slack notifications...');
        $webhook = text('Webhook', hint: 'The webhook to use for sending the notification.');
        $channel = text('Channel', hint: 'The channel to use for sending the notification.');

        // Put the results in an array
        $results = [
            'webhook' => $webhook,
            'channel' => $channel
        ];
        // Relay the information back to the user
        info('📨 Great! Here\'s what we\'ve got so far:');
        table(
            ['Webhook', 'Channel'],
            [$results]
        );

        // Ask the user to confirm the information
        $confirm = confirm('Is this correct?');

        if(!$confirm){
            info('No problem! Let\'s try again.');
            $this->setupSlackNotifications();
        }else{
            info('Great! Saving your settings...');
            // Save the settings to the cosmo config file
            config::write('cosmo.notification_credentials.slack.webhook', $webhook);
            config::write('cosmo.notification_credentials.slack.channel', $channel);
            info('🎉 Slack configuration saved successfully!');
            $this->addMethodToArray('slack');
            // check if the user wants to add another method
            $this->checkAnotherNotificationMethod();
        }
    }

    private function setupDiscordNotifications()
    {
        info('Setting up discord notifications...');
        $webhook = text('Webhook', hint: 'The webhook to use for sending the notification.');
        $username = text('Username', hint: 'The username to use for sending the notification.');
        $avatar = text('Avatar', hint: 'The avatar to use for sending the notification.');

        // Put the results in an array
        $results = [
            'webhook' => $webhook,
            'username' => $username,
            'avatar' => $avatar
        ];
        // Relay the information back to the user
        info('📨 Great! Here\'s what we\'ve got so far:');
        table(
            ['Webhook', 'Username', 'Avatar'],
            [$results]
        );

        // Ask the user to confirm the information
        $confirm = confirm('Is this correct?');

        if(!$confirm){
            info('No problem! Let\'s try again.');
            $this->setupDiscordNotifications();
        }else{
            info('Great! Saving your settings...');
            // Save the settings to the cosmo config file
            config::write('cosmo.notification_credentials.discord.webhook', $webhook);
            config::write('cosmo.notification_credentials.discord.username', $username);
            config::write('cosmo.notification_credentials.discord.avatar', $avatar);
            info('🎉 Discord configuration saved successfully!');
            $this->addMethodToArray('discord');
            // check if the user wants to add another method
            $this->checkAnotherNotificationMethod();
        }
    }

    private function setupTelegramNotifications()
    {
        info('Setting up telegram notifications...');
        $botToken = text('Bot Token', hint: 'The bot token to use for sending the notification.');
        $chatId = text('Chat ID', hint: 'The chat ID to use for sending the notification.');
        $parseMode = select(
            'Parse Mode',
            ['Markdown', 'HTML'],
            hint: 'The parse mode to use for sending the notification.'
        );

        // Put the results in an array
        $results = [
            'bot_token' => $botToken,
            'chat_id' => $chatId,
            'parse_mode' => $parseMode
        ];
        // Relay the information back to the user
        info('📨 Great! Here\'s what we\'ve got so far:');
        table(
            ['Bot Token', 'Chat ID', 'Parse Mode'],
            [$results]
        );

        // Ask the user to confirm the information
        $confirm = confirm('Is this correct?');

        if(!$confirm){
            info('No problem! Let\'s try again.');
            $this->setupTelegramNotifications();
        }else{
            info('Great! Saving your settings...');
            // Save the settings to the cosmo config file
            config::write('cosmo.notification_credentials.telegram.bot_token', $botToken);
            config::write('cosmo.notification_credentials.telegram.chat_id', $chatId);
            config::write('cosmo.notification_credentials.telegram.parse_mode', $parseMode);
            info('🎉 Telegram configuration saved successfully!');
            $this->addMethodToArray('telegram');
            // check if the user wants to add another method
            $this->checkAnotherNotificationMethod();
        }
    }

    private function setupTwilioNotifications()
    {
        info('Setting up twilio notifications...');

        $sid = text('SID', hint: 'The SID to use for sending the notification.');
        $token = text('Token', hint: 'The token to use for sending the notification.');
        $from = text('From', hint: 'The from number to use for sending the notification.');
        $to = text('To', hint: 'The to number to use for sending the notification.');

        // Put the results in an array
        $results = [
            'sid' => $sid,
            'token' => $token,
            'from' => $from,
            'to' => $to
        ];
        // Relay the information back to the user
        info('📨 Great! Here\'s what we\'ve got so far:');

        table(
            ['SID', 'Token', 'From', 'To'],
            [$results]
        );
        // Ask the user to confirm the information
        $confirm = confirm('Is this correct?');
        if(!$confirm){
            info('No problem! Let\'s try again.');
            $this->setupTwilioNotifications();
        }else{
            info('Great! Saving your settings...');
            // Save the settings to the cosmo config file
            config::write('cosmo.notification_credentials.twilio.sid', $sid);
            config::write('cosmo.notification_credentials.twilio.token', $token);
            config::write('cosmo.notification_credentials.twilio.from', $from);
            config::write('cosmo.notification_credentials.twilio.to', $to);
            info('🎉 Twilio configuration saved successfully!');
            $this->addMethodToArray('twilio');
            // check if the user wants to add another method
            $this->checkAnotherNotificationMethod();
        }
    }

    private function setupTeamsNotifications()
    {
        info('Setting up teams notifications...');
        $webhook = text('Webhook', hint: 'The webhook to use for sending the notification.');
        
        // Put the results in an array
        $results = [
            'webhook' => $webhook
        ];
        // Relay the information back to the user
        info('📨 Great! Here\'s what we\'ve got so far:');
        table(
            ['Webhook'],
            [$results]
        );

        // Ask the user to confirm the information
        $confirm = confirm('Is this correct?');

        if(!$confirm){
            info('No problem! Let\'s try again.');
            $this->setupTeamsNotifications();
        }else{
            info('Great! Saving your settings...');
            // Save the settings to the cosmo config file
            config::write('cosmo.notification_credentials.teams.webhook', $webhook);
            info('🎉 Teams configuration saved successfully!');
            $this->addMethodToArray('teams');
            // check if the user wants to add another method
            $this->checkAnotherNotificationMethod();
        }
    }
}