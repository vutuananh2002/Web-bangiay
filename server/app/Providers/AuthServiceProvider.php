<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Banner;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Color;
use App\Models\Customer;
use App\Models\Image;
use App\Models\Manufacture;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Size;
use App\Models\Type;
use App\Models\User;
use App\Policies\Admin\AdminPolicy;
use App\Policies\Admin\AuthAdminPolicy;
use App\Policies\Admin\AuthorizationPolicy;
use App\Policies\Admin\ChangeUserInforPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ColorPolicy;
use App\Policies\Admin\CustomerPolicy;
use App\Policies\Admin\ImagePolicy;
use App\Policies\ManufacturePolicy;
use App\Policies\ProductPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\SizePolicy;
use App\Policies\TypePolicy;
use App\Policies\Admin\UserPolicy;
use App\Policies\BannerPolicy;
use App\Policies\CartPolicy;
use App\Policies\OrderPolicy;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Manufacture::class => ManufacturePolicy::class,
        Category::class => CategoryPolicy::class,
        Color::class => ColorPolicy::class,
        Image::class => ImagePolicy::class,
        Type::class => TypePolicy::class,
        Size::class => SizePolicy::class,
        Review::class => ReviewPolicy::class,
        Customer::class => CustomerPolicy::class,
        User::class => UserPolicy::class,
        Admin::class => AdminPolicy::class,
        Order::class => OrderPolicy::class,
        Cart::class => CartPolicy::class,
        Banner::class => BannerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('admin_register', [AuthAdminPolicy::class, 'adminRegister']);
        Gate::define('admin_refresh_token', [AuthAdminPolicy::class, 'adminToken']);
        Gate::define('admin_logout', [AuthAdminPolicy::class, 'adminLogout']);
        Gate::define('create_category', [CategoryPolicy::class, 'create']);
        Gate::define('update_category', [CategoryPolicy::class, 'update']);
        Gate::define('update_user', [UserPolicy::class, 'update']);
        Gate::define('update_customer', [CustomerPolicy::class, 'update']);
        Gate::define('create_image', [ImagePolicy::class, 'create']);
        Gate::define('manage_manufacture_logo', [ManufacturePolicy::class, 'manageLogo']);
        Gate::define('create_manufacture', [ManufacturePolicy::class, 'create']);
        Gate::define('update_manufacture', [ManufacturePolicy::class, 'update']);
        Gate::define('manage_product_image', [ProductPolicy::class, 'manageImage']);
        Gate::define('create_product', [ProductPolicy::class, 'create']);
        Gate::define('update_product', [ProductPolicy::class, 'update']);
        Gate::define('create_color', [ColorPolicy::class, 'create']);
        Gate::define('update_color', [ColorPolicy::class, 'update']);
        Gate::define('create_size', [SizePolicy::class, 'create']);
        Gate::define('update_size', [SizePolicy::class, 'update']);
        Gate::define('create_type', [TypePolicy::class, 'create']);
        Gate::define('update_type', [TypePolicy::class, 'update']);
        Gate::define('create_review', [ReviewPolicy::class, 'create']);
        Gate::define('update_review', [ReviewPolicy::class, 'update']);
        Gate::define('update_admin', [AdminPolicy::class, 'update']);
        Gate::define('view_authorization', [AuthorizationPolicy::class, 'viewAuthorization']);
        Gate::define('create_role', [AuthorizationPolicy::class, 'createRole']);
        Gate::define('create_permission', [AuthorizationPolicy::class, 'createPermission']);
        Gate::define('manage_role', [AuthorizationPolicy::class, 'manageRole']);
        Gate::define('manage_permission', [AuthorizationPolicy::class, 'managePermission']);
        Gate::define('view_cart', [CartPolicy::class, 'view']);
        Gate::define('add_product_to_cart', [CartPolicy::class, 'addProduct']);
        Gate::define('remove_product_from_cart', [CartPolicy::class, 'removeProduct']);
        Gate::define('checkout', [CartPolicy::class, 'checkout']);
        Gate::define('create_banner', [BannerPolicy::class, 'create']);
        Gate::define('update_banner', [BannerPolicy::class, 'update']);
        Gate::define('update_order', [OrderPolicy::class, 'update']);
        Gate::define('cancel_order', [OrderPolicy::class, 'cancelOrder']);

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->markdown('emails.EmailVerification', [
                    'url' => $url,
                    'username' => $notifiable->username,
                ]);
        });
    }
}
