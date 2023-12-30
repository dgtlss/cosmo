<?php

namespace Dgtlss\Cosmo\Commands;

/*
|--------------------------------------------------------------------------
| Cosmo Prune Command
|--------------------------------------------------------------------------
|
| This command will prune Cosmo errors from the database.
| The hours, only & flag options are optional and not required for this command to work.
| The hours option will prune all errors between the current date / time and the amount of hours prior to that.
| The only option will only prune the errors with the given status for example:
| --only=404 will only prune errors with the status 404.
| The flag option will only prune the errors with the given flag for example:
| --flag=resolved will only prune errors with the flag important.
| The notify option will send a notification to the user when the command has finished.
| For this option to work, please make sure that the notifications option is set to true in the config file
| and that you have set up the notification methods and credentials in the config file.
|
*/

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Dgtlss\Cosmo\Models\CosmoError;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Dgtlss\Cosmo\Mail\PruneCompletedMail;
use Carbon\Carbon;

Class PruneCosmo extends Command
{
    
    protected $signature = 'cosmo:prune {--hours=} {--only=} {--flag=} {--notify}';
    protected $description = 'Prune Cosmo errors.';

    public function handle()
    {
        $hours = $this->option('hours');
        $only = $this->option('only');
        $flag = $this->option('flag');

        $this->info('Searching for Cosmo errors...');
        $errors = CosmoError::query(function ($query) use ($hours, $only, $flag) {
            if ($hours) {
                $query->where('created_at', '<=', now()->subHours($hours));
            }else{
                $query->where('created_at', '<=', now()->subHours(24));
            }
            if ($only) {
                $query->where('status', $only);
            }
            if ($flag) {
                $query->where('flag', $flag);
            }
        })->get();

        // Count the total amount of errors to be pruned
        $totalErrors = $errors->count();

        // If there are errors to be pruned, prune them
        if ($totalErrors > 0) {
            $this->info('Pruning Cosmo errors...');
            $errors->each(function ($error) {
                $error->delete();
            });
            $this->info('Cosmo errors pruned successfully!');
        }else{
            $this->info('No Cosmo errors to prune.');
        }

        if($this->option('notify')){
            $this->info('Sending notification...');
            $this->notify($totalErrors);
        }

        $this->info('All Errors Pruned Successfully!');
    }

    private function notify($totalErrors)
    {
        $this->info($totalErrors . ' Cosmo errors have been pruned, sending notifications...');
        $notificationMethods = config('cosmo.notification_methods');
        $notificationCredentials = config('cosmo.notification_credentials');
        if(!empty($notificationMethods)){
            // The user has setup their notification options in the config file!
            foreach($notificationMethods as $method){
                // check that the user has filled all fields for the notification method
                if(!empty($notificationCredentials[$method])){
                    if($method == 'mail'){
                        // The user has set up mail notifications
                        $email = $notificationCredentials[$method];
                        Mail::to($email)->send(new PruneCompletedMail($totalErrors));

                        if(Mail::failures()){
                            $this->error('Mail notification failed to send!');
                        }else{
                            $this->info('Mail notification sent successfully!');
                        }
                    }
                    if($method == 'slack'){
                        // The user has set up slack notifications
                        $slackwebhook = $notificationCredentials[$method]['webhook'];
                        $slackchannel = $notificationCredentials[$method]['channel'];
                        $data = [
                            'text' => 'Cosmo has pruned ' . $totalErrors . ' errors from the database.'
                        ];

                        $slackMessage = Http::post($slackwebhook, $data);

                        if($slackMessage->successful()){
                            $this->info('Slack notification sent successfully!');
                        }else{
                            $this->error('Slack notification failed to send!');
                        }
                    }
                    if($method == 'discord'){
                        // The user has set up discord notifications
                        $discordwebhook = $notificationCredentials[$method]['webhook'];
                        $data = [
                            'content' => 'Cosmo has pruned ' . $totalErrors . ' errors from the database.'
                        ];

                        $discordMessage = Http::post($discordwebhook, $data);

                        if($discordMessage->successful()){
                            $this->info('Discord notification sent successfully!');
                        }else{
                            $this->error('Discord notification failed to send!');
                        }
                    }
                    if($method == 'telegram'){
                        // The user has set up telegram notifications
                        $telegrambot = $notificationCredentials[$method]['bot_token'];
                        $telegramchat = $notificationCredentials[$method]['chat_id'];
                        $data = [
                            'chat_id' => $telegramchat,
                            'text' => 'Cosmo has pruned ' . $totalErrors . ' errors from the database.'
                        ];

                        $telegramMessage = Http::post('https://api.telegram.org/bot' . $telegrambot . '/sendMessage', $data);

                        if($telegramMessage->successful()){
                            $this->info('Telegram notification sent successfully!');
                        }else{
                            $this->error('Telegram notification failed to send!');
                        }
                    }
                    if($method == 'pushover'){
                        // We need to look at the docs properly for this one
                    }
                    if($method == 'twilio'){
                        // We need to look at the docs properly for this one
                    }
                    if($method == 'teams'){
                        // The user has set up teams notifications
                        $teamswebhook = $notificationCredentials[$method]['webhook'];
                        $data = [
                            'text' => 'Cosmo has pruned ' . $totalErrors . ' errors from the database.'
                        ];

                        $teamsMessage = Http::post($teamswebhook, $data);

                        if($teamsMessage->successful()){
                            $this->info('Teams notification sent successfully!');
                        }else{
                            $this->error('Teams notification failed to send!');
                        }
                    }
                }
            }
        }   
    }
}