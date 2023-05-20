<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class FrontBaseController extends Controller
{
    public $DEFAULT_CURRENCY_CODE;
    public $CHANGE_CURRENCY_SYMBOL;
    public $CHANGE_CURRENCY_RATE;

    public function __construct(){
        if (!Session::has('currency_code') && !Session::has('currency_symbol') && !Session::has('currency_rate')) {
            $this->DEFAULT_CURRENCY_CODE = env('DEFAULT_CURRENCY_CODE') ?? 'USD';
            $this->CHANGE_CURRENCY_SYMBOL= env('DEFAULT_CURRENCY_SYMBOL') ?? '$';
            $this->CHANGE_CURRENCY_RATE  = env('DEFAULT_CURRENCY_RATE') ?? '1.0';

            Session::put('currency_code', $this->DEFAULT_CURRENCY_CODE);
        }
        $this->DEFAULT_CURRENCY_CODE  = Session::get('currency_code');
        $this->CHANGE_CURRENCY_SYMBOL = Session::get('currency_symbol');
        $this->CHANGE_CURRENCY_RATE   = Session::get('currency_rate');

        // View::share ([
        //     // 'DEFAULT_CURRENCY_CODE', $this->DEFAULT_CURRENCY_CODE,
        //     // 'CHANGE_CURRENCY_SYMBOL', $this->CHANGE_CURRENCY_SYMBOL,
        //     // 'CHANGE_CURRENCY_RATE', $this->CHANGE_CURRENCY_RATE,
        // ]);
        View::share (
            'DEFAULT_CURRENCY_CODE', $this->DEFAULT_CURRENCY_CODE,
            'CHANGE_CURRENCY_SYMBOL', $this->CHANGE_CURRENCY_SYMBOL,
            'CHANGE_CURRENCY_RATE', $this->CHANGE_CURRENCY_RATE,
        );
    }
}
