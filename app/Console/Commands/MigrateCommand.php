<?php

namespace SmartWiki\Console\Commands;

use Illuminate\Console\Command;

/**
 * SmartWiki 迁移命令
 *
 * 使用： php artisan smartwiki:migrate --dbHost=127.0.0.1 --dbName=smart_wiki --dbPort=3306 --dbUser=root --dbPassword=123456
 *
 * Class MigrateCommand
 * @package SmartWiki\Console\Commands
 */
class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smartwiki:migrate {--dbHost=127.0.0.1} {--dbName=smart_wiki} {--dbPort=3303} {--dbUser=root} {--dbPassword=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dbHost = $this->option('dbHost');
        $dbName = $this->option('dbName');
        $dbPort = $this->option('dbPort');
        $dbUser = $this->option('dbUser');
        $dbPassword = $this->option('dbPassword');

        $params = [
            'DB_HOST' => $dbHost,
            'DB_PORT' => $dbPort,
            'DB_DATABASE' => $dbName,
            'DB_USERNAME' => $dbUser,
            'DB_PASSWORD' => $dbPassword
        ];
        modify_env($params);

        $this->info('migrate success!');
        return true;
    }
}
