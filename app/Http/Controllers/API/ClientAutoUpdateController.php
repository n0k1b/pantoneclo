<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\ENVFilePutContent;
use App\Traits\JSONFileTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use ZipArchive;

class ClientAutoUpdateController extends Controller
{
    use ENVFilePutContent, JSONFileTrait;

    // Client
    public function index(){
        return view('admin.pages.version_upgrade.index');
    }
    
    // Client
    public function bugUpdatePage(){
        return view('admin.pages.bug_update.index');
    }


    // Action on Client Server
    public function versionUpgrade(Request $request){
        return $this->actionTransfer($request,'version_upgrade');
    }


    // Action apply on Client Server
    public function bugUpdate(Request $request){
        return $this->actionTransfer($request,'bug_update');
    }

    protected function actionTransfer($request, $action_type)
    {
        $track_files_arr   = json_decode(json_encode($request->data), FALSE);
        $track_general_arr = json_decode(json_encode($request->general), FALSE);

        if($action_type =='version_upgrade'){
            $base_url = 'https://cartproshop.com/version_upgrade_files/'; //$this->version_upgrade_base_url;
        }else if($action_type == 'bug_update') {
            $base_url = 'https://cartproshop.com/bug_update_files/'; //$this->bug_update_base_url;
        }

        // Chack all Before Execute
        if ($track_files_arr && $track_general_arr) {
            foreach ($track_files_arr->files as $value) {
                $remote_file_url  = $base_url.$value->file_name;
                $array = @get_headers($remote_file_url);
                $string = $array[0];
                if(!strpos($string, "200")) {
                    return response()->json(['error' => ['Something problem. Please contact with support team.']],404);
                }
            }
        }


        // Start Execute
        try{
            if ($track_files_arr && $track_general_arr) {
                foreach ($track_files_arr->files as $value) {
                    $remote_file_url  = $base_url.$value->file_name;
                    $remote_file_name = pathinfo($remote_file_url)['basename'];
                    $local_file = base_path('/'.$remote_file_name);
                    $copy = copy($remote_file_url, $local_file);
                    if ($copy) {
                        // ****** Unzip ********
                        $zip = new ZipArchive;
                        $file = base_path($remote_file_name);
                        $res = $zip->open($file);
                        if ($res === TRUE) {
                            $zip->extractTo(base_path('/'));
                            $zip->close();

                            // ****** Delete Zip File ******
                            File::delete($remote_file_name);
                        }
                    }
                }

                if($action_type =='version_upgrade'){
                    $this->dataWriteInENVFile('VERSION',$track_general_arr->general->demo_version);
                }else if($action_type == 'bug_update') {
                    $this->dataWriteInENVFile('BUG_NO',$track_general_arr->general->demo_bug_no);
                }

                if (($action_type =='version_upgrade' && $track_general_arr->general->latest_version_db_migrate_enable==true) || ($action_type == 'bug_update' && $track_general_arr->general->bug_db_migrate_enable==true) ){
                    Artisan::call('migrate');
                }
                Artisan::call('optimize:clear');
                return response()->json('success');
            }
        }
        catch(Exception $e) {
            return response()->json(['error' => [$e->getMessage()]],404);
        }
    }
}

