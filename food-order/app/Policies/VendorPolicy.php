<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;

class VendorPolicy
{
    /**
     * Determine whether the user can view any vendors.
     */
    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view a vendor.
     */
    public function view(User $user, Vendor $vendor)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can create vendors.
     */
    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update a vendor.
     */
    public function update(User $user, Vendor $vendor)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete a vendor.
     */
    public function delete(User $user, Vendor $vendor)
    {
        return $user->role === 'admin';
    }
}
