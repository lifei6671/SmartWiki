<?php

namespace SmartWiki\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SmartWiki\Document;
use SmartWiki\Member;
use SmartWiki\Observers\DocumentObservers;
use SmartWiki\Observers\MemberObservers;
use SmartWiki\Observers\ProjectObservers;
use SmartWiki\Observers\WikiConfigObservers;
use SmartWiki\Project;
use SmartWiki\WikiConfig;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'SmartWiki\Events\SomeEvent' => [
            'SmartWiki\Listeners\EventListener',
        ],
    ];

    public function __construct(\Illuminate\Contracts\Foundation\Application $app)
    {
        parent::__construct($app);

    }

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        Member::observe(new MemberObservers());

        Project::observe(new ProjectObservers());

        //注册文档观察者
        Document::observe(new DocumentObservers());

        //注册配置观察者
        WikiConfig::observe(new WikiConfigObservers());
    }
}
