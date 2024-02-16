<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Auction;
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
        Gate::define("auctions.create", function (User $user, Product $product): bool {
            return (bool) (($user->type === 1 || $user->type === 3) && $product->vendor_id === $user->id);
        });

        Gate::define("auctions.addInteractions", function (User $user, Auction $auction): bool {
            $customers = $auction->getAuctionCustomers()->get();
            $userInteraction = $auction->interactions()->where("user_id", $user->id)->first();
            if ($userInteraction !== null) {
                return false;
            }
            foreach ($customers as $customer) {
                if ($customer->id === $user->id)
                    return true;
            }
            return false;
        });

        Gate::define("products.addInteractions", function (User $user, Product $product): bool {
            $userInteraction = $product->interactions()->where("user_id", $user->id)->first();
            if ($userInteraction !== null) {
                return false;
            }
            return (bool) (($user->type === 2 || $user->type === 3));
        });
    }
}
