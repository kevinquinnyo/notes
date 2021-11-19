<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:create_api_token {email} {token_name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new API token for a user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        $tokenName = $this->argument('token_name') ?? sprintf('%s API Token', $email);

        if (!$user) {
            $this->error(sprintf(
                'User with email "%s" not found. Try %s/register',
                $email,
                env('APP_URL', 'localhost'),
            ));

            return Command::FAILURE;
        }

        try {
            $token = $user->createToken($tokenName);

            $this->info(sprintf(
                'API token (%s) created for user %s: %s',
                $tokenName,
                $email,
                $token->plainTextToken
            ));

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error(sprintf('Unable to generate token: %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
