<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ENVFilePutContent;
use App\Traits\JSONFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DeveloperSectionController extends Controller
{
    use ENVFilePutContent,JSONFileTrait;

    public function index()
    {
        if(env('PRODUCT_MODE')!=='DEVELOPER'){
            abort(404);
        }
        $general = $this->readJSONData('track/general.json');
        $control = $this->readJSONData('track/control.json');
        return view('admin.pages.developer_section.index',compact('general','control'));
    }

    public function autoUpdateSubmit(Request $request)
    {
        $general =[
            "product_mode"=> env('PRODUCT_MODE'),
            "version"     => $request->version,
            "bug_no"      => $request->bug_no,
            "minimum_required_version" => $request->minimum_required_version,
        ];
        $this->dataWriteInENVFile('PRODUCT_MODE',$request->product_mode);
        $this->dataWriteInENVFile('VERSION',$request->version);
        $this->dataWriteInENVFile('BUG_NO',$request->bug_no);

        $control =[
            'version_upgrade'=>[
                'latest_version_upgrade_enable'    => $request->latest_version_upgrade_enable ? true : false,
                'latest_version_db_migrate_enable' => $request->latest_version_db_migrate_enable ? true : false,
                'version_upgrade_base_url'         => $request->version_upgrade_base_url,
            ],
            'bug_update'=>[
                'bug_update_enable'     => $request->bug_update_enable ? true : false,
                'bug_db_migrate_enable' => $request->bug_db_migrate_enable ? true : false,
                'bug_update_base_url'   => $request->bug_update_base_url,
            ]
        ];


        // Write Array in JSON File
        $this->wrtieDataInJSON($general, 'track/general.json');
        $this->wrtieDataInJSON($control ,'track/control.json');


        $this->setSuccessMessage(__('Data Submited Successfully'));
        return redirect()->back();
    }
}
