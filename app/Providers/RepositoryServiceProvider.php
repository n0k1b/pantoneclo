<?php

namespace App\Providers;

use App\Contracts\Attribute\AttributeContract;
use App\Contracts\Attribute\AttributeTranslationContract;
use App\Contracts\AttributeSet\AttributeSetContract;
use App\Contracts\AttributeSet\AttributeSetTranslationContract;
use App\Contracts\AttributeValue\AttributeValueContract;
use App\Contracts\AttributeValue\AttributeValueTranslationContract;
use App\Contracts\Brand\BrandContract;
use App\Contracts\Brand\BrandTranslationContract;
use App\Contracts\Category\CategoryContract;
use App\Contracts\Category\CategoryProductContract;
use App\Contracts\Category\CategoryTranslationContract;
use App\Contracts\Country\CountryContract;
use App\Contracts\Coupon\CouponContract;
use App\Contracts\Coupon\CouponTranslationContract;
use App\Contracts\Currency\CurrencyContract;
use App\Contracts\FlashSale\FlashSaleContract;
use App\Contracts\FlashSale\FlashSaleProductContract;
use App\Contracts\FlashSale\FlashSaleTranslationContract;
use App\Contracts\Menu\MenuContract;
use App\Contracts\Menu\MenuTranslationContract;
use App\Contracts\Order\OrderContract;
use App\Contracts\Order\OrderDetailsContract;
use App\Contracts\Page\PageContract;
use App\Contracts\Page\PageTranslationContract;
use App\Contracts\Product\ProductAttributeValueContract;
use App\Contracts\Product\ProductContract;
use App\Contracts\Product\ProductImageContract;
use App\Contracts\Product\ProductTagContract;
use App\Contracts\Product\ProductTranslationContract;
use App\Contracts\Review\ReviewContract;
use App\Contracts\Role\RoleContract;
use App\Contracts\Slider\SliderContract;
use App\Contracts\Slider\SliderTranslationContract;
use App\Contracts\Tag\TagContract;
use App\Contracts\Tag\TagTranslationContract;
use App\Contracts\Tax\TaxContract;
use App\Contracts\Tax\TaxTranslationContract;
use App\Repositories\Attribute\AttributeRepository;
use App\Repositories\Attribute\AttributeTranslationRepository;
use App\Repositories\AttributeSet\AttributeSetRepository;
use App\Repositories\AttributeSet\AttributeSetTranslationRepository;
use App\Repositories\AttributeValue\AttributeValueRepository;
use App\Repositories\AttributeValue\AttributeValueTranslationRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Brand\BrandRepository;
use App\Repositories\Brand\BrandTranslationRepository;
use App\Repositories\Category\CategoryProductRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryTranslationRepository;
use App\Repositories\Country\CountryRepository;
use App\Repositories\Coupon\CouponRepository;
use App\Repositories\Coupon\CouponTranslationRepository;
use App\Repositories\Currency\CurrencyRepository;
use App\Repositories\FlashSale\FlashSaleProductRepository;
use App\Repositories\FlashSale\FlashSaleRepository;
use App\Repositories\FlashSale\FlashSaleTranslationRepository;
use App\Repositories\Menu\MenuRepository;
use App\Repositories\Menu\MenuTranslationRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Page\PageRepository;
use App\Repositories\Page\PageTranslationRepository;
use App\Repositories\Product\ProductAttributeValueRepository;
use App\Repositories\Product\ProductImageRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductTagRepository;
use App\Repositories\Product\ProductTranslationRepository;
use App\Repositories\Review\ReviewRepository;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Slider\SliderRepository;
use App\Repositories\Slider\SliderTranslationRepository;
use App\Repositories\Tag\TagRepository;
use App\Repositories\Tag\TagTranslationRepository;
use App\Repositories\Tax\TaxRepository;
use App\Repositories\Tax\TaxTranslationRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // $this->app->bind(
        //     \App\Interfaces\CategoryInterface::class,
        //     \App\Repositories\CategoryRepository::class
        // );

        //Category
        $this->app->bind(CategoryContract::class, CategoryRepository::class);
        $this->app->bind(CategoryTranslationContract::class, CategoryTranslationRepository::class);
        $this->app->bind(CategoryProductContract::class, CategoryProductRepository::class);

        //Brand
        $this->app->bind(BrandContract::class, BrandRepository::class);
        $this->app->bind(BrandTranslationContract::class, BrandTranslationRepository::class);

        //Attribute Set
        $this->app->bind(AttributeSetContract::class, AttributeSetRepository::class);
        $this->app->bind(AttributeSetTranslationContract::class, AttributeSetTranslationRepository::class);

        //Tag Set
        $this->app->bind(TagContract::class, TagRepository::class);
        $this->app->bind(TagTranslationContract::class, TagTranslationRepository::class);

        //Currency
        $this->app->bind(CurrencyContract::class, CurrencyRepository::class);

        //County
        $this->app->bind(CountryContract::class, CountryRepository::class);

        //Slider
        $this->app->bind(SliderContract::class, SliderRepository::class);
        $this->app->bind(SliderTranslationContract::class, SliderTranslationRepository::class);

        //Page
        $this->app->bind(PageContract::class, PageRepository::class);
        $this->app->bind(PageTranslationContract::class, PageTranslationRepository::class);

        //Attribute
        $this->app->bind(AttributeContract::class, AttributeRepository::class);
        $this->app->bind(AttributeTranslationContract::class, AttributeTranslationRepository::class);

        //Attribute Value
        $this->app->bind(AttributeValueContract::class, AttributeValueRepository::class);
        $this->app->bind(AttributeValueTranslationContract::class, AttributeValueTranslationRepository::class);

        //Tax
        $this->app->bind(TaxContract::class, TaxRepository::class);
        $this->app->bind(TaxTranslationContract::class, TaxTranslationRepository::class);

        //Review
        $this->app->bind(ReviewContract::class, ReviewRepository::class);

        //Order
        $this->app->bind(OrderContract::class, OrderRepository::class);

        //Order Details
        $this->app->bind(OrderDetailsContract::class, OrderRepository::class);

        //Flash Sale
        $this->app->bind(FlashSaleContract::class, FlashSaleRepository::class);
        $this->app->bind(FlashSaleTranslationContract::class, FlashSaleTranslationRepository::class);
        $this->app->bind(FlashSaleProductContract::class, FlashSaleProductRepository::class);

        // Coupon
        $this->app->bind(CouponContract::class, CouponRepository::class);
        $this->app->bind(CouponTranslationContract::class, CouponTranslationRepository::class);

        // Menu
        $this->app->bind(MenuContract::class, MenuRepository::class);
        $this->app->bind(MenuTranslationContract::class, MenuTranslationRepository::class);

        // Role
        $this->app->bind(RoleContract::class, RoleRepository::class);

        // Products
        $this->app->bind(ProductContract::class, ProductRepository::class);
        $this->app->bind(ProductTranslationContract::class, ProductTranslationRepository::class);
        $this->app->bind(ProductAttributeValueContract::class, ProductAttributeValueRepository::class);
        $this->app->bind(ProductImageContract::class, ProductImageRepository::class);
        $this->app->bind(ProductTagContract::class, ProductTagRepository::class);

    }
}

?>
