<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CurrencyRate;
use Illuminate\Http\Request;

class CurrencyRateController extends Controller
{
    public function index()
    {
        if (auth()->user()->can('currency-rate-view'))
        {
            if (request()->ajax())
            {
                $currency_rates = CurrencyRate::orderBy('currency_name','ASC')->get();

                return datatables()->of($currency_rates)
                ->setRowId(function ($row){
                    return $row->id;
                })
                ->addColumn('currency_code', function ($row){
                    return $row->currency_code ?? "";
                })
                ->addColumn('currency_symbol', function ($row){
                    return $row->currency_symbol ?? "";
                })
                ->addColumn('currency_rate', function ($row){
                    return number_format((float)$row->currency_rate, env('FORMAT_NUMBER'), '.', '');
                })
                ->addColumn('action', function ($row)
                {
                    if (auth()->user()->can('currency-rate-edit')){
                        if ($row->default==1) {
                            $actionBtn = '<button disabled type="button" title="Can Not Edit" class="edit btn btn-info btn-sm" title="Edit" data-id="'.$row->id.'"><i class="dripicons-pencil"></i></button>
                            &nbsp; ';
                            $actionBtn .= '<span class="badge badge-light text-success">Default</span>';
                        }else{
                            $actionBtn = '<button type="button" title="Edit" class="edit btn btn-info btn-sm" title="Edit" data-id="'.$row->id.'"><i class="dripicons-pencil"></i></button>
                            &nbsp; ';
                        }
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action','currency_symbol'])
                ->make(true);
            }
            return view('admin.pages.currency_rate.index');
        }
        return abort('403', __('You are not authorized'));

    }

    public function edit(Request $request)
    {
        $data = CurrencyRate::find($request->currency_rate_id);

        return response()->json(['id'=>$data->id,'currency_symbol'=>$data->currency_symbol, 'currency_rate'=> number_format((float)$data->currency_rate, env('FORMAT_NUMBER'), '.', '')]);
    }

    public function update(Request $request)
    {
        if (auth()->user()->can('currency-rate-edit'))
        {
            if (request()->ajax()) {
                $data = CurrencyRate::find($request->id);
                $data->currency_rate   = number_format((float)$request->currency_rate, env('FORMAT_NUMBER'), '.', '');
                // $data->currency_symbol = $request->currency_symbol;
                $data->update();

                return response()->json(['success' => 'Data Updated Successfully']);
            }
        }

    }
}
