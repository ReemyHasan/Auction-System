<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Auction extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $guarded = [
        "id",
        "created_at",
        "updated_at",
    ];
    protected $with = [
        "product"
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, "product_id");
    }
    public function bids()
    {
        return $this->hasMany(CustomerBid::class, "auction_id")->orderBy("created_at", "desc");
    }

    public function scopeFilter($query)
    {
        if (!empty(request('name'))) {
            $product = Product::where("name", "like", "%" . request()->get("name") . "%")->first();
            $query->where("product_id", "like", "%" . $product->id . "%");
        }
        if (!empty(request("category_id"))) {
            $query->join("products", "products.id", "=", "auctions.product_id")
                ->join("product_category", "products.id", "=", "product_category.product_id")
                ->where("category_id", "like", "%" . request()->get("category_id") . "%");
        }
        if (!empty(request("start_time"))) {
            $query->where("start_time", "like", "%" . request()->get("start_time") . "%");
        }
        if (request()->has("my_auctions")) {
            $query->join("products", "products.id", "=", "product_id")
                ->where('vendor_id', '=', Auth::user()->id)
                ->select("auctions.*");
        }
    }
    public function getAuctionCustomers()
    {
        return $this->hasMany(CustomerBid::class, "auction_id")
            ->join("users", "users.id", "=", "customer_id")
            ->select('users.*')->distinct()->orderBy("name", "asc");
    }
    public function setStatus()
    {
        if ($this->start_time > Carbon::now())
            return 0;
        else if (
            $this->closing_time < Carbon::now() ||
            (!empty($this->hasMany(CustomerBid::class, "auction_id")->orderBy("price", "desc")->first())
                && $this->hasMany(CustomerBid::class, "auction_id")->orderBy("price", "desc")->first()->price >= $this->closing_price)
        )
            return 2;
        else
            return 1;
    }
    public function auctionWinner()
    {
        if ($this->status == 2 && !empty($this->hasMany(CustomerBid::class, "auction_id")->orderBy("price", "desc")->first())) {
            $winner = $this->hasMany(CustomerBid::class, "auction_id")->orderBy("price", "desc")->first()->customer_id;
            $winner = User::where("id", $winner)->first();
            return $winner;
        } else
            return false;
    }

    public function interactions(): MorphMany
    {
        return $this->morphMany(Interaction::class, 'interactionable');
    }
}
