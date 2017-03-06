<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/3/2 0002
 * Time: 9:45
 */

namespace SmartWiki\Console\Commands;


use Illuminate\Console\Command;

class VersionCommand extends Command
{
    protected $signature = 'smartwiki:version';

    protected $description = 'Print SmartWiki Version';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $this->info('SmartWiki ' . SmartWikiVersion);

        return true;
    }
}