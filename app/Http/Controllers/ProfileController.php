<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Order;
// use App\Models\Address; // if/when you implement it
use App\Models\Wishlist; // fallback if you don't have user->wishlist() relation

class ProfileController extends Controller
{
    /**
     * Breeze: profile edit page
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Breeze: update profile
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Breeze: delete account
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /* ===============================
     |         MY ACCOUNT HUB
     * =============================== */

    /** /account -> redirect to Orders tab by default */
    public function account(): RedirectResponse
    {
        return Redirect::route('account.orders');
    }

    /** Orders tab */
    public function orders(): View
    {
        $orders = Order::with(['items.product.primaryImage'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('account.index', [
            'section' => 'orders',
            'orders'  => $orders,
        ]);
    }

    /** Wishlist tab */
    public function favorites(): View
    {
        $user = Auth::user();

        // Prefer a user->wishlist() relation if you have it
        if (method_exists($user, 'wishlist')) {
            $items = $user->wishlist()
                ->with(['product.primaryImage'])
                ->latest()
                ->get();
        } else {
            // Fallback to a direct model query
            $items = Wishlist::with(['product.primaryImage'])
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return view('account.index', [
            'section'        => 'favorites',
            'items'          => $items,
            'wishlist'       => $items,
            'wishlistItems'  => $items,
            'products'       => $items,
        ]);
    }

    /** Personal data tab (uses Breeze partials) */
    public function accountProfile(): View
    {
        return view('account.index', [
            'section' => 'profile',
            'user'    => Auth::user(),
        ]);
    }

    /** Change password tab (uses Breeze partials) */
    public function password(): View
    {
        return view('account.index', ['section' => 'password']);
    }

    /** Addresses tab (placeholder UI) */
    public function addresses(): View
    {
        // $addresses = Address::where('user_id', Auth::id())->get();
        return view('account.index', [
            'section'   => 'addresses',
            'addresses' => collect(), // replace with $addresses when implemented
        ]);
    }

    // (Optional) Stubs for address CRUD to pair with the UI if you added it
    public function addressesStore(Request $request): RedirectResponse
    {
        return back()->with('success', 'Address saved.');
    }

    public function addressesUpdate(Request $request, $addressId): RedirectResponse
    {
        return back()->with('success', 'Address updated.');
    }

    public function addressesDestroy($addressId): RedirectResponse
    {
        return back()->with('success', 'Address deleted.');
    }
}
