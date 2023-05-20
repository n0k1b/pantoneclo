<div class="table-content table-responsive cart-table">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>@lang('file.Product')</th>
                <th class="text-center">@lang('file.Total')</th>
            </tr>
        </thead>
        <tbody class="cartTable">
            <div id="content">
                @forelse ($cart_content as $item)
                    <tr id="{{$item->rowId}}">
                        <td class="cart-product">
                            <div class="item-details">
                                <img class="lazy" data-src="{{asset('public/'.$item->options->image ?? null)}}" alt="...">
                                <div>
                                    <h3 class="h6">{{$item->name}}</h3>


                                    <div class="input-qty">
                                        <input type="text" readonly class="input-number" readonly value="{{$item->qty}}">
                                        X
                                        <span class="amount">&nbsp;
                                            @if(env('CURRENCY_FORMAT')=='suffix')
                                                <span>{{$item->price  * $CHANGE_CURRENCY_RATE}}</span> @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                            @else
                                                @include('frontend.includes.SHOW_CURRENCY_SYMBOL') <span>{{$item->price * $CHANGE_CURRENCY_RATE}}</span>
                                            @endif
                                        </span>
                                    </div>

                                    <!-- Attributes -->
                                    <br>
                                    <div class="mt-2 input-qty">
                                        @php
                                            if($item->options->attributes){
                                                $data = $item->options->attributes;
                                                $attributes = array();
                                                for($i=0; $i< count($data['name']); $i++){
                                                    $attributes[] = [
                                                            'name' => $data['name'][$i],
                                                            'value' => $data['value'][$i]
                                                        ];
                                                }
                                            }
                                        @endphp
                                        @if ($item->options->attributes)
                                            @foreach ($attributes as $attribute)
                                                <div class="row"><span><b>{{$attribute['name']}} :</b>{{$attribute['value']}}</span></div>
                                            @endforeach
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </td>
                        <td class="cart-product-subtotal"><span class="amount">
                            @if(env('CURRENCY_FORMAT')=='suffix')
                                <span class="subtotal_{{$item->rowId}}"> {{ number_format((float)$item->subtotal * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}</span> @include('frontend.includes.SHOW_CURRENCY_SYMBOL')</span>
                            @else
                                @include('frontend.includes.SHOW_CURRENCY_SYMBOL') <span class="subtotal_{{$item->rowId}}">{{ number_format((float)$item->subtotal * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                @endforelse
            </div>
        </tbody>
    </table>
</div>
