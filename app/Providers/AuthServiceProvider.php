<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Category;
use App\Models\CustomerBid;
use App\Models\Product;
use App\Models\User;
use App\Policies\BidPolicy;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        CustomerBid::class => BidPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define("auctions.create", function (User $user,Product $product): bool {
            return (bool) (($user->type === 1 || $user->type === 3)  && $product->vendor_id === $user->id);
        });
    }
}
