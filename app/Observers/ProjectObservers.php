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
use SmartWiki\Models\Document;
use SmartWiki\Models\Project;

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

        $document = new Document();
        $document->doc_name = '空白文档';
        $document->create_at = $project->create_at;
        $document->create_time = $project->create_time;
        $document->doc_sort = 0;
        $document->parent_id = 0;
        $document->project_id = $project->project_id;
        $document->save();
    }

    public function deleted(Project $project)
    {
        $key = 'project.id.' . $project->project_id;
        Cache::forget($key);
    }
}
