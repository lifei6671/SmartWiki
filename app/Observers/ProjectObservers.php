<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/11/10 0010
 * Time: 18:41
 */

namespace SmartWiki\Observers;

use Cache;
use Carbon\Carbon;
use SmartWiki\Project;

/**
 * 项目模型观察者
 * Class ProjectObservers
 * @package SmartWiki\Observers
 */
class ProjectObservers
{
    public function creating(Project $project)
    {
        $project->create_time = date('Y-m-d H:i:s');
    }

    public function updating(Project $project)
    {
        $project->modify_time = date('Y-m-d H:i:s');
        $key = 'project.id.' . $project->project_id;
        Cache::forget($key);
    }
    public function updated(Project $project)
    {
        $key = 'project.id.' . $project->project_id;

        $expiresAt = Carbon::now()->addHour(12);

        Cache::put($key,$project,$expiresAt);
    }

    public function created(Project $project)
    {
        $key = 'project.id.' . $project->project_id;

        $expiresAt = Carbon::now()->addHour(12);

        Cache::put($key,$project,$expiresAt);
    }

    public function deleted(Project $project)
    {
        $key = 'project.id.' . $project->project_id;
        Cache::forget($key);
    }
}
