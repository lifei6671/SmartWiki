<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/1/17 0017
 * Time: 17:21
 */

namespace SmartWiki\Http\Controllers;

use Illuminate\Http\Response;
use SmartWiki\Models\Project;
use QrCode;

class QrCodeController extends Controller
{
    public function index()
    {
        $projectId = intval($this->request->get('id'));

        if($projectId <= 0){
            abort(404);
        }
        $path = public_path('uploads/qrcode/') . $projectId . '.png';
        if(file_exists($path) && is_readable($path)){
            $png = file_get_contents($path);
        }else {
            $project = Project::getProjectFromCache($projectId);
            if (empty($project)) {
                abort(404);
            }
            $url = route('home.show', ['id' => $projectId]);

            $png = QrCode::format('png')->margin(1)->size(160)->generate($url);
          // var_dump(dirname($path));exit;

            @mkdir(dirname($path),0766,true);

            @file_put_contents($path,$png);
        }

        return (new Response($png, 200))
            ->header('Content-Type', 'image/png');
    }
}