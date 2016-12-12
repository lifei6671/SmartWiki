<?php

namespace SmartWiki\Console\Commands;

use Illuminate\Console\Command;

/**
 * SmartWiki 安装命令行工具
 * 使用：php artisan smartwiki:install --dbHost=127.0.0.1 --dbName=smart_wiki --dbPort=3306 --dbUser=root --dbPassword=123456 --account=admin --password=123456 --email=longfei6671@163.com
 * Class InstallCommand
 * @package SmartWiki\Console\Commands
 */
class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smartwiki:install {--dbHost=127.0.0.1} {--dbName=smart_wiki} {--dbPort=3306} {--dbUser=root} {--dbPassword=?} {--account=?} {--password=?} {--email=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '命令行安装SmartWiki';

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
     * @return mixed
     */
    public function handle()
    {
        $dbHost = $this->option('dbHost');
        if(empty($dbHost)){
            $dbHost = env('DB_HOST');
        }

        $dbName = $this->option('dbName');
        if(empty($dbName)){
            $dbName = env('DB_DATABASE');
        }

        $dbPort = $this->option('dbPort');
        if(empty($dbPort)){
            $dbPort = env('DB_PORT');
        }

        $dbUser = $this->option('dbUser');
        $dbPassword = $this->option('dbPassword');
        $account = $this->option('account');
        $password = $this->option('password');
        $email = $this->option('email');

        system_install($dbHost,$dbName,$dbPort, $dbUser, $dbPassword,$account,$password,$email);
        $this->info('migrate success!');

        return true;
    }
}
