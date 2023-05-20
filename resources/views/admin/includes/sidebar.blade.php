<!-- Sidebar-->
<nav class="side-navbar">
    <span class="brand-big" id="site_logo_main">
        @if(isset($setting_store->admin_logo) && Illuminate\Support\Facades\File::exists(public_path($setting_store->admin_logo)))
            <img src="{{asset('public/'.$setting_store->admin_logo)}}" width="150">
            &nbsp; &nbsp;
        @else
        <img src="https://dummyimage.com/150x150/e5e8ec/e5e8ec&text=Dashboard Logo" width="150">
            &nbsp; &nbsp;
        @endif
    </span>
    <!-- Sidebar Navigation Menus-->
    <ul id="side-main-menu" class="side-menu list-unstyled">
        <li class="{{Request::is('admin/dashboard') ? 'active' : ''}}"><a href="{{url('/admin/dashboard')}}"> <i class="dripicons-meter"></i><span>{{__('file.Dashboard') }}</span></a></li>
        @can('product')
            <li class="has-dropdown"><a href="#product" aria-expanded=" {{  Request::is('admin/categories') ||
                                                                        Request::is('admin/brands') ||
                                                                        Request::is('admin/brands/brand/*') ||
                                                                        Request::is('admin/attribute-sets') ||
                                                                        Request::is('admin/attributes') ||
                                                                        Request::is('admin/attributes/create') ||
                                                                        Request::is('admin/attributes/edit/*') ||
                                                                        Request::is('admin/tags') ||
                                                                        Request::is('admin/products') ||
                                                                        Request::is('admin/products/create') ||
                                                                        Request::is('admin/products/edit/*') ||
                                                                        Request::is('admin/review')

                                                                        ? 'true':'false' }}" data-toggle="collapse"> <i class="fa fa-cube"></i><span>{{__('file.Products')}}</span></a>

                <ul id="product" class="collapse list-unstyled  {{  Request::is('admin/categories') ||
                                                                Request::is('admin/brands') ||
                                                                Request::is('admin/brands/brand/*') ||
                                                                Request::is('admin/attribute-sets') ||
                                                                Request::is('admin/attributes') ||
                                                                Request::is('admin/attributes/create') ||
                                                                Request::is('admin/attributes/edit/*') ||
                                                                Request::is('admin/tags') ||
                                                                Request::is('admin/products') ||
                                                                Request::is('admin/products/create') ||
                                                                Request::is('admin/products/edit/*') ||
                                                                Request::is('admin/review')
                                                                ? 'show':'' }}">
                @can('category')
                    <li id="category-menu" class="{{Request::is('admin/categories') ? 'active' : ''}}"><a href="{{route('admin.category')}}">{{__('file.Category')}}</a></li>
                @endcan
                @can('brand')
                    <li id="brand-list-menu" class="{{(Request::is('admin/brands') || Request::is('admin/brands/brand/*')) ? 'active' : ''}}"><a href="{{route('admin.brand')}}">{{__('file.Brand')}}</a></li>
                @endcan
                @can('attribute_set')
                    <li id="brand-list-menu" class="{{Request::is('admin/attribute-sets') ? 'active' : ''}}"><a href="{{route('admin.attribute_set.index')}}">{{__('file.Attribute Set')}}</a></li>
                @endcan
                @can('attribute')
                    <li id="brand-list-menu" class="{{Request::is('admin/attributes') || Request::is('admin/attributes/create') || Request::is('admin/attributes/edit/*') ? 'active' : ''}}"><a href="{{route('admin.attribute.index')}}">{{__('file.Attributes')}}</a></li>
                @endcan
                @can('tag')
                    <li id="brand-list-menu" class="{{Request::is('admin/tags') ? 'active' : ''}}"><a href="{{route('admin.tag.index')}}">{{__('file.Tags')}}</a></li>
                @endcan
                @can('catalog')
                    <li id="brand-list-menu" class="{{Request::is('admin/products') || Request::is('admin/products/create') || Request::is('admin/products/edit/*') ? 'active' : ''}}"><a href="{{route('admin.products.index')}}">{{__('file.Catalog')}}</a></li>
                @endcan
                @can('review-view')
                    <li id="brand-list-menu" class="{{Request::is('admin/review') ? 'active' : ''}}"><a href="{{route('admin.review.index')}}">{{__('file.Reviews')}}</a></li>
                @endcan
            </ul>
        </li>
        @endcan

        @can('sale')
            <li><a href="#sale" aria-expanded=" {{  Request::is('admin/order') ||
                                                    Request::is('admin/order/details/*') ||
                                                    Request::is('admin/transaction')
                                                    ? 'true':'false' }}" data-toggle="collapse"> <i class="fa fa-dollar"></i><span>{{__('file.Sales')}}</span></a>

                <ul id="sale" class="collapse list-unstyled {{
                                                        Request::is('admin/order') ||
                                                        Request::is('admin/order/details/*') ||
                                                        Request::is('admin/transaction')
                                                        ? 'show':'' }}">
                @can('order-view')
                    <li id="sale-list-menu" class="{{Request::is('admin/order') || Request::is('admin/order/details') ? 'active' : ''}}"><a href="{{route('admin.order.index')}}">{{__('file.Orders')}}</a></li>
                @endcan
                @can('transaction-view')
                    <li id="sale-list-menu" class="{{Request::is('admin/transaction') ? 'active' : ''}}"><a href="{{route('admin.transaction.index')}}">{{__('file.Transactions')}}</a></li>
                @endcan
                </ul>
            </li>
        @endcan

        @can('flash_sale')
            <li class="{{Request::is('admin/flash-sales') || Request::is('admin/flash-sales/create') || Request::is('admin/flash-sales/edit/*') ? 'active' : ''}}"><a href="{{route('admin.flash_sale.index')}}"><i class="fa fa-bolt"></i><span>{{__('file.Flash Sales')}}</span></a></li>
        @endcan

        @can('coupon')
            <li class="{{Request::is('admin/coupons') || Request::is('admin/coupons/create') || Request::is('admin/coupons/edit/*') ? 'active' : ''}}"><a href="{{route('admin.coupon.index')}}"><i class="fa fa-tags"></i><span>{{__('file.Coupons')}}</span></a></li>
        @endcan

        @can('faq')
            <li><a href="#faq" aria-expanded="{{Request::is('admin/faq/*') ? 'true':'false' }}" data-toggle="collapse"> <i class="fa fa-sticky-note"></i><span>{{trans('file.FAQ Setting')}}</span></a>
                <ul id="faq" class="collapse list-unstyled {{Request::is('admin/faq/*') ? 'show':'' }}">
                    <li class="{{Route::current()->getName()=='admin.faq_type.index' ? 'active' : ''}}"><a href="{{route('admin.faq_type.index')}}">{{__('file.Type')}}</a></li>
                    <li class="{{Route::current()->getName()=='admin.faq.index' ? 'active' : ''}}"><a href="{{route('admin.faq.index')}}"><span>{{__('file.FAQ Set')}}</span></a></li>
                </ul>
            </li>
        @endcan


        @can('appearance')
            <li><a href="#menu" aria-expanded=" {{Request::is('admin/online-store/*') ? 'true':'false' }}"  data-toggle="collapse"> <i class="dripicons-store"></i><span>{{trans('file.Online Store')}}</span></a>
                <ul id="menu" class="collapse list-unstyled {{Request::is('admin/online-store/*') ? 'show':'' }}">
                    @can('page')
                        <li class="{{Route::current()->getName()=='admin.page.index' ? 'active' : ''}}"><a href="{{route('admin.page.index')}}">{{trans('file.Pages')}}</a></li>
                    @endcan
                    @can('menu')
                        <li class="{{Route::current()->getName()=='admin.menu' ? 'active' : ''}}"><a href="{{route('admin.menu')}}">{{trans('file.Menus')}}</a></li>
                    @endcan
                    @can('slider')
                        <li class="{{Route::current()->getName()=='admin.slider' ? 'active' : ''}}"><a href="{{route('admin.slider')}}"><span>{{__('file.Slider')}}</span></a></li>
                    @endcan
                    @can('store_front')
                        <li class="{{Route::current()->getName()=='admin.storefront' ? 'active' : ''}}"><a href="{{route('admin.storefront')}}">{{__('file.Store Front')}}</a></li>
                    @endcan
                </ul>
            </li>
        @endcan

        <li><a href="#report" aria-expanded=" {{Request::is('admin/reports/*') ? 'true':'false' }}" data-toggle="collapse"> <i class="dripicons-document-remove"></i><span>{{__('file.Reports')}}</span></a>
            <ul id="report" class="collapse list-unstyled {{Request::is('admin/reports/*') ? 'show':'' }}">
                <li class="{{Route::current()->getName()=='admin.reports.coupon' ? 'active' : ''}}"><a href="{{route('admin.reports.coupon')}}">@lang('file.Coupon Report')</a></li>
                <li class="{{Route::current()->getName()=='admin.reports.customer_orders' ? 'active' : ''}}"><a href="{{route('admin.reports.customer_orders')}}">@lang('file.Customer Order Report')</a></li>
                <li class="{{Route::current()->getName()=='admin.reports.product_stock_report' ? 'active' : ''}}"><a href="{{route('admin.reports.product_stock_report')}}">@lang('file.Product Stock Report')</a></li>
                <li class="{{Route::current()->getName()=='admin.reports.product_view_report' ? 'active' : ''}}"><a href="{{route('admin.reports.product_view_report')}}">@lang('file.Product View Report')</a></li>
                <li class="{{Route::current()->getName()=='admin.reports.sales_report' ? 'active' : ''}}"><a href="{{route('admin.reports.sales_report')}}">@lang('file.Sales Report')</a></li>
                <li class="{{Route::current()->getName()=='admin.reports.search_report' ? 'active' : ''}}"><a href="{{route('admin.reports.search_report')}}">@lang('file.Search Report')</a></li>
                <li class="{{Route::current()->getName()=='admin.reports.shipping_report' ? 'active' : ''}}"><a href="{{route('admin.reports.shipping_report')}}">@lang('file.Shipping Report')</a></li>
                <li class="{{Route::current()->getName()=='admin.reports.tax_report' ? 'active' : ''}}"><a href="{{route('admin.reports.tax_report')}}">@lang('file.Tax Report')</a></li>
                {{-- <li><a href="{{route('admin.reports.product_purchase_report')}}">@lang('file.Product Purchase Report')</a></li> --}}
            </ul>
        </li>

        @can('users_and_roles')
            <li><a href="#user" aria-expanded="{{Request::is('admin/user') || Request::is('admin/roles') ? 'true':'false' }}" data-toggle="collapse"> <i class="dripicons-user-group"></i><span>{{__('file.Users and Roles')}}</span></a>
                <ul id="user" class="collapse list-unstyled {{Request::is('admin/user') || Request::is('admin/roles')? 'show':'' }}">
                @can('user')
                    <li class="{{Route::current()->getName()=='admin.user' ? 'active' : ''}}" id="navigation-menu"><a href="{{route('admin.user')}}">{{__('file.Users')}}</a></li>
                @endcan
                @can('role')
                    <li class="{{Route::current()->getName()=='admin.role.index' || Request::is('admin/roles/role-permission/*') ? 'active' : ''}}"  id="navigation-menu"><a href="{{route('admin.role.index')}}">{{__('file.Roles')}}</a></li>
                @endcan
                </ul>
            </li>
        @endcan

        @can('localization')
            <li><a href="#localization" aria-expanded="{{Request::is('admin/localization/*') || Request::is('languages/*') ? 'true':'false' }}" data-toggle="collapse"> <i class="dripicons-web"></i><span>{{__('file.Localization')}}</span></a>
                <ul id="localization" class="collapse list-unstyled {{Request::is('admin/localization/*') || Request::is('languages/*') ? 'show':'' }}">
                    @can('tax')
                        <li class="{{Route::current()->getName()=='admin.tax.index' ? 'active' : ''}}"><a href="{{route('admin.tax.index')}}">{{__('file.Taxes')}}</a></li>
                    @endcan
                    <li class="{{Request::is('languages/*') || Request::is('languages/*')}}"><a href="{{route('languages.translations.index',Session::get('currentLocal'))}}">{{__('file.Translations')}}</a></li>

                    @can('currency-rate')
                        <li class="{{Route::current()->getName()=='admin.currency_rate.index' ? 'active' : ''}}"><a href="{{route('admin.currency_rate.index')}}">{{__('file.Currency Rates')}}</a></li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('site-setting')
            <li class="has-dropdown">
                <a href="#setting" aria-expanded="{{Request::is('admin/setting/*') ? 'true':'false' }}" data-toggle="collapse"> <i class="dripicons-toggles"></i><span>{{trans('file.Site Settings')}}</span></a>
                <ul id="setting" class="collapse list-unstyled {{Request::is('admin/setting/*')  ? 'show':'' }}">
                    @can('country')
                        <li id="setting-country" class="{{Request::is('admin/setting/countries') ? 'active' : ''}}"><a href="{{route('admin.country.index')}}">{{__('file.Country')}}</a></li>
                    @endcan
                    @can('currency')
                        <li id="setting-currency" class="{{Request::is('admin/setting/currencies') ? 'active' : ''}}"><a href="{{route('admin.currency.index')}}">{{__('file.Currency')}}</a></li>
                    @endcan
                        {{-- <li><a href="{{route('admin.shipping.location.index')}}">{{__('file.Shipping')}}</a></li> --}}
                    @can('setting')
                        <li id="setting-other-setting" class="{{Request::is('admin/setting/others') ? 'active' : ''}}"><a href="{{route('admin.setting.index')}}">{{__('file.Other Setting')}}</a></li>
                    @endcan
                    @can('language')
                        <li id="setting-language" class="{{Request::is('admin/setting/language') ? 'active' : ''}}"><a href="{{route('admin.setting.language')}}">{{__('file.Language')}}</a></li>
                    @endcan
                </ul>
            </li>
        @endcan

        @if (env('PRODUCT_MODE')==='DEVELOPER')
            <li class="{{Request::is('admin/developer-section/index') ? 'active' : ''}}"><a href="{{route('admin.developer.section.index')}}"><i class="fa fa-cogs"></i><span>{{__('file.Developer Section')}}</span></a></li>
        @endif
    </ul>
</nav>
<!-- Sidebar-->
