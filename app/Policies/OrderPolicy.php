<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view orders
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        // Customers can view their own orders
        if ($user->id === $order->user_id) {
            return true;
        }

        // Vendors can view orders for their shops
        if ($user->role === 'vendor' && $order->shop->user_id === $user->id) {
            return true;
        }

        // Admins can view all orders
        if ($user->role === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only customers can create orders
        return $user->role === 'user';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        // Customers can update their own pending orders
        if ($user->id === $order->user_id && $order->isPending()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update order status.
     */
    public function updateStatus(User $user, Order $order): bool
    {
        // Only vendors can update status for their shop's orders
        if ($user->role === 'vendor' && $order->shop->user_id === $user->id) {
            return true;
        }

        // Admins can update any order status
        if ($user->role === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        // Customers can delete their own cancelled orders
        if ($user->id === $order->user_id && $order->isCancelled()) {
            return true;
        }

        // Admins can delete any order
        if ($user->role === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Order $order): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        return false;
    }
}
