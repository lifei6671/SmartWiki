<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2016/11/10 0010
 * Time: 18:37
 */

namespace SmartWiki\Observers;

use Carbon\Carbon;
use SmartWiki\Models\Document;
use SmartWiki\Models\DocumentHistory;
use SmartWiki\Models\Project;
use Cache;

/**
 * 文档更改的观察者
 * Class DocumentObservers
 * @package SmartWiki\Observers
 */
class DocumentObservers
{
    public function creating(Document $document)
    {
        $document->create_time =  date('Y-m-d H:i:s');
    }

    public function created(Document $document)
    {
        $project = Project::find($document->project_id);
        if($project){
            $project->doc_count = Document::where('project_id','=',$document->project_id)->count();
            $project->save();
        }

        $key = 'document.doc_id.'.$document->doc_id;
        $expiresAt = Carbon::now()->addHour(12);

        Cache::put($key,$document,$expiresAt);
    }

    public function updating(Document $document)
    {
        $document->modify_time =  date('Y-m-d H:i:s');

        Cache::forever('document.'.$document->doc_id,$document);
    }

    /**
     * 当文档更新后执行
     * @param Document $document
     */
    public function updated(Document $document)
    {
        $create_at = $document->modify_at;

        if(empty($create_at)){
            $create_at = 0;
        }
        $enableHistory = wiki_config('ENABLED_HISTORY');

        $document = Cache::pull('document.'.$document->doc_id);

        if($enableHistory && $document instanceof Document){
            $history = new DocumentHistory();
            $history->doc_id = $document->doc_id;
            $history->doc_name = $document->doc_name;
            $history->parent_id = $document->parent_id;
            $history->doc_content = $document->doc_content;
            $history->modify_at = $document->modify_at;
            $history->modify_time = $document->modify_time;
            $history->version = $document->version;
            $history->create_time = date('Y-m-d H:i:s');
            $history->create_at = $create_at;
            $history->save();
        }
        $key = 'document.doc_id.'.$document->doc_id;

        $expiresAt = Carbon::now()->addHour(12);

        Cache::put($key,$document,$expiresAt);
    }

    /**
     * 当文档被删除事删除保存的文档历史
     * @param Document $document
     */
    public function deleted(Document $document)
    {
        $project = Project::find($document->project_id);
        if($project){
            $project->doc_count = Document::where('project_id','=',$document->project_id)->count();
            $project->save();
        }
        
        DocumentHistory::where('doc_id','=',$document->doc_id)->delete();
        $key = 'document.doc_id.'.$document->doc_id;
        Cache::forget($key);
    }
}