<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryProduct;
use App\Models\Customer;
use App\Models\Language;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Notifications\NewOrderNotification;
use App\Traits\AutoDataUpdateTrait;
use App\Traits\JSONFileTrait;
use Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

//Google Analytics
use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;
use App\User;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    use AutoDataUpdateTrait, JSONFileTrait;
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        return view('admin.pages.home');
    }

    public function dashboard()
    {
        $orders          = Order::orderBy('id','DESC')->get();
        $products        = Product::where('is_active',1)->get();
        $total_customers = User::where('user_type',0)->get()->count();

        //We will convert it in ExpiryReminder later
        $this->autoDataUpdate();

        $top_brands = OrderDetail::with('brand.brandTranslation','brand.brandTranslationEnglish')
                            ->select('brand_id', DB::raw('count(*) as total, sum(subtotal) as total_amount'))
                            ->orderBy('total_amount','DESC')
                            ->groupBy('brand_id')
                            ->get()
                            ->take(5);

        $top_categories = OrderDetail::with('category.catTranslation','category.categoryTranslationDefaultEnglish')
                            ->select('category_id', DB::raw('count(*) as total, sum(subtotal) as total_amount'))
                            ->orderBy('total_amount','DESC')
                            ->groupBy('category_id')
                            ->get()
                            ->take(5);

        $top_products = OrderDetail::with('product','orderProductTranslation','orderProductTranslationEnglish','baseImage')
                            ->select('product_id', DB::raw('sum(subtotal) as total_amount'))
                            ->orderBy('total_amount','DESC')
                            ->groupBy('product_id')
                            ->get()
                            ->take(6);

        $category_product =  CategoryProduct::get();
        $category_ids = [];
        foreach ($products as $key => $item) {
            foreach ($category_product as $key => $value) {
                if ($item->id==$value->product_id) {
                    $category_ids[$item->id] = $category_product[$key];
                    break;
                }
            }
        }

        $browsers = Analytics::fetchTopBrowsers(Period::days(7));
        $topVisitedPages = Analytics::fetchMostVisitedPages(Period::days(7))->take(10);
        $topReferrers = Analytics::fetchTopReferrers(Period::days(7))->take(10);
        $topUserTypes = Analytics::fetchUserTypes(Period::days(7))->take(10);
        // $topAnaluticsService = Analytics::getAnalyticsService();
        // dd($topAnaluticsService);


        return view('admin.pages.home',compact('orders','products','total_customers','top_brands','top_categories','top_products','category_ids',
                                                'browsers','topVisitedPages','topReferrers','topUserTypes'));
    }

    protected function readFileEnglish(){

        //******* Temporaray For Language Input */
        // $lang_en = $this->readFileEnglish();
        // $lang_other = $this->readFileothers('bn');
        // $this->writeFile($lang_en, $lang_other);
        // return 'ok';
        // sort($lang_en);
        // foreach ($lang_en as $key => $val) {
        //     echo $val."</br>";
        // }
        //******* Temporaray End*/

        $lang_en = [];
        $myfile = fopen("temporary/lang_en.txt", "r") or die("Unable to open file!");
        while(!feof($myfile)) {
            $stringRemoveCotation = fgets($myfile);
            $stringRemoveNewLine = str_replace("\n", '', $stringRemoveCotation);
            $lang_en[] = str_replace("'", '', $stringRemoveNewLine);
        }
        return $lang_en;
    }

    protected function readFileothers($locale){
        $lang_other = [];
        $myfile = fopen('temporary/lang_'.$locale.'.txt', 'r') or die("Unable to open file!");
        while(!feof($myfile)) {
            $stringRemoveCotation = fgets($myfile);
            $stringRemoveNewLine = str_replace("\n", '', $stringRemoveCotation);
            $lang_other[] = str_replace("'", '', $stringRemoveNewLine);
        }
        return $lang_other;
    }

    protected function writeFile($lang_en, $lang_other){
        $myfile_read = fopen("temporary/output_lang.txt", "w") or die("Unable to open file!");
        foreach ($lang_other as $key => $value) {
            if ($value==null){
                break;
            }else {
                $text = "'$lang_en[$key]'". '=>' ."'$value',\n";
                fwrite($myfile_read, $text);
            }
        }
    }


    public function chart()
    {
        $startDate = Carbon::now()->subYear();
        $endDate   = Carbon::now();
        // $result = Analytics::fetchVisitorsAndPageViews(Period::create($startDate, $endDate));
        $result = Analytics::fetchVisitorsAndPageViews(Period::days(7));

        return response()->json($result);
    }

    public function googleAnalytics()
    {
        $analytics = Analytics::fetchVisitorsAndPageViews(Period::days(1));
        dd($analytics);
    }

    public function logout()
    {
        Auth::logout();
            $message=array(
                'messege'=>'Successfully Logout',
                'alert-type'=>'success'
                 );

        Session::flush();

             return Redirect()->route('admin')->with($message);
    }




    protected function testingCode()
    {
        // $notification = auth()->user();

        // $notification = User::find(1);
        // $notification = $order->unreadNotifications;

        // auth()->user()->notify(new NewOrderNotification(1089));
        // return 1;
        // $notifications = DB::table('notifications')->where('read_at', null)->get();
        // $notifications = DB::table('notifications')->where('read_at', null)->update(['read_at'=> Carbon::now()]);
        // return $notifications;


        // auth()->user()->notify(new NewOrderNotification($reference_no));

        $test = DB::table('notifications')->where('read_at', null)->get();
        foreach($test as $notification){
            return json_decode($notification->data)->link;
        }
        return json_decode($test[0]->data)->link;

        $url = 'https://cartproshop.com/bug_update_files/app.zip';
        $array = @get_headers($url);
        $string = $array[0];
        if(!strpos($string, "200")) {
            return 'Specified URL does not exist';
        }
        else {
            return 'Specified URL Exists';
        }


        // S-1
        // Data Read
        $path = base_path('track/fetch-data-bug.json');
        $fetch_data_bug_data = null;
        if (File::exists($path)) {
            $json_file = File::get($path);
            $fetch_data_bug_data = json_decode($json_file);
        }
        // $track_files_arr   = $fetch_data_bug_data;
        $track_files_arr   = json_decode(json_encode($fetch_data_bug_data), FALSE);
        $base_url = 'https://cartproshop.com/bug_update_files/';


        // S-2
        $posts = [];
        foreach ($track_files_arr->files as $key => $value) {
            $remote_file_url  = $base_url.$value->file_name;
            // $remote_file_url  = $value->file_name;
            return $remote_file_url;
            $remote_file_name = pathinfo($remote_file_url)['basename'];

            $posts[] = Array (
                "sl" => $key+1,
                "title" => $remote_file_name,
            );
        }
        // $path = base_path('track/bug_track.json');
        // $json = json_encode($posts);
        // file_put_contents($path, $json); //generate json file



        // S-3
        $path_bug_track = base_path('track/bug_track.json');
        $bug_track = null;
        if (File::exists($path_bug_track)) {
            $json_file = File::get($path_bug_track);
            $bug_track = json_decode($json_file);
        }

        // S-4
        if (count($fetch_data_bug_data->files) == count($bug_track)) {
           return 'ok';
        }else{
            return 'not ok';
        }




        // Data Write
        $posts = Array (
            "0" => Array (
                "id" => "01",
                "title" => "Hello",
            ),
            "1" => Array (
                "id" => "02",
                "title" => "Yoyo",
            ),
            "2" => Array (
                "id" => "03",
                "title" => "I like Apples",
            )
        );

        $path = base_path('track/bug_track.json');

        $json = json_encode($posts);
        $bytes = file_put_contents($path, $json); //generate json file
        return "Here is the myfile data $bytes.";

        // $path = base_path('track/control.json');
        // $data = null;
        // if (File::exists($path)) {
        //     $json_file = File::get($path);
        //     $data = json_decode($json_file);
        // }
        // return $data->control[0];



        // $name = "Irfan Chowdhury";
        // $age  = 24;
        // $data = `<pre>Hello this is {$name} and Age is {$age}</pre>`;


        // return $data;


        // $filePath    = base_path('/'.'fahim');
        // $newFileName = base_path('/'.'irfan');

        // /* Rename File name */
        // if( !rename($filePath, $newFileName) ) {
        //     return "File can't be renamed!";
        // }
        // else {
        //     return "File has been renamed!";
        // }


        // ======== Test Start ========
        // $track_files_arr = [];
        // $track_file = File::exists('track/track_file.txt');
        // if ($track_file) {
        //     $track_files_arr = [];
        //     $myfile = fopen("track/track_file.txt", "r") or die("Unable to open file!");
        //     while(!feof($myfile)) {
        //         $get_data = fgets($myfile);
        //         $track_files_arr[] = str_replace("\r\n", '', $get_data);
        //     }
        // }

        // if ($track_files_arr) {
        //     foreach ($track_files_arr as $value) {
        //         // File transfer server to server
        //         $remote_file_url  = "http://peopleprohrm.com/auto_update_files/".$value;
        //         $remote_file_name = pathinfo($remote_file_url)['basename'];
        //         $local_file = base_path('/'.$remote_file_name);
        //         $copy = copy($remote_file_url, $local_file);
        //         if ($copy) {
        //             // ****** Unzip ********
        //             $zip = new ZipArchive;
        //             $file = base_path($remote_file_name);
        //             $res = $zip->open($file);
        //             if ($res === TRUE) {
        //                 $zip->extractTo(base_path('/'));
        //                 $zip->close();

        //                 // ****** Delete Zip File ******
        //                 File::delete($remote_file_name);
        //             }
        //         }
        //     }
        //     Artisan::call('migrate');
        //     return 'Congratulation !!! Successfully Migrated';
        // }else {
        //     return 'Something Wrong. Please try again later';
        // }








        // Full Code
        // ======== Test Start ========

        // ***** File transfer server to server ******

        // $remote_file_url  = 'http://peopleprohrm.com/auto_update_files/irfan.zip';

        // // $all_files = File::allFiles($remote_file_url);
        // // return $all_files;

        // $remote_file_name = pathinfo($remote_file_url)['basename'];

        // $local_file = base_path('/'.$remote_file_name);

        // $copy = copy( $remote_file_url, $local_file );

        // if( !$copy ) {
        //     return 'error';
        // }
        // else{

        //     // ****** Unzip ********
        //     $zip = new ZipArchive;
        //     $file = base_path($remote_file_name);
        //     $res = $zip->open($file);
        //     if ($res === TRUE) {
        //         $zip->extractTo(base_path('/'));
        //         $zip->close();

        //         // ****** Delete File ******
        //         File::delete($remote_file_name);
        //        return 'unzip success and delete the file';
        //     } else {
        //         return 'problem';
        //     }
        // }





        // Partially


    // =========== Unzip ======================

        // $zip = new ZipArchive;
        // $file = base_path('irfan.zip');
        // $res = $zip->open($file);
        // if ($res === TRUE) {
        //     $zip->extractTo(base_path('/'));
        //     $zip->close();
        //     // =========== Delete File ======================
        //     File::delete($file);
        //     return 'done';
        // } else {
        //     return 'not';
        // }


    // ============== File transfer server to server ===================

    //

       /* Source File URL */
        // // $remote_file_url = 'http://cartproshop.com/demo_old/public/Test/Test123.zip';
        // $remote_file_url = 'http://peopleprohrm.com/irfan.zip';
        // $remote_file_url = 'http://peopleprohrm.com/irfan.zip';
        // $remote_file_name = pathinfo($remote_file_url)['basename'];

        // /* New file name and path for this file */
        // $local_file = public_path('Test/MoveFolder/'.$remote_file_name);

        // /* Copy the file from source url to server */
        // $copy = copy( $remote_file_url, $local_file );

        // /* Add notice for success/failure */
        // if( !$copy ) {
        //     return 0;
        // }
        // else{
        //     return 1;
        // }

        // return 5;

        // ======== Test End ========
    }

}
