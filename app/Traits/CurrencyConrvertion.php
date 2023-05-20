<?php
namespace App\Traits;

use App\Models\CurrencyRate;
use App\Models\SettingCurrency;
use Illuminate\Support\Facades\Session;

trait CurrencyConrvertion{

    use ENVFilePutContent;

    public function CurrencyConvert($main_amount){
        $currency_code = Session::get('currency_code');
        $currencyRate  = CurrencyRate::where('currency_code',$currency_code)
                    ->select('currency_code','currency_rate')
                    ->first();
        return $main_amount * $currencyRate->currency_rate;
    }


    public function CurrencySymbol($currency_code){
        // $currency_code = Session::get('currency_code');
        $currencyRate  = CurrencyRate::where('currency_code',$currency_code)
                        ->select('currency_code','currency_symbol')
                        ->first();
        return $currencyRate->currency_symbol;
    }


    public function ChangeCurrencyRate($currency_code){
        // $currency_code = Session::get('currency_code');
        $currencyRate  = CurrencyRate::where('currency_code',$currency_code)
                        ->select('currency_code','currency_symbol','currency_rate')
                        ->first();
        return $currencyRate->currency_rate;
    }




    public function defaultCurrencySetting(){
        // Session::forget('currency_code');

        if (!Session::has('currency_code')) {
            return 1;
            $settingCurrency = SettingCurrency::latest()->first();
            Session::put('currency_code', $settingCurrency->default_currency_code);

            $this->dataWriteInENVFile('USER_CHANGE_CURRENCY_SYMBOL',env('DEFAULT_CURRENCY_SYMBOL'));
            $this->dataWriteInENVFile('USER_CHANGE_CURRENCY_RATE', env('DEFAULT_CURRENCY_RATE'));
        }

    }

}
