<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\ShoppingList;
use App\Models\ShoppingListEntry;
use App\Policies\ProductPolicy;
use App\Policies\ProductUnitPolicy;
use App\Policies\ShoppingListEntryPolicy;
use App\Policies\ShoppingListPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ShoppingList::class => ShoppingListPolicy::class,
        ShoppingListEntry::class => ShoppingListEntryPolicy::class,
        Product::class => ProductPolicy::class,
        ProductUnit::class => ProductUnitPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
