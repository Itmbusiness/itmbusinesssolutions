<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        return view('pages.admin.users', [
            'users' => User::query()
                ->when($request->filled('q'), function ($query) use ($request) {
                    $term = '%'.$request->string('q').'%';
                    $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term));
                })
                ->withCount('leads')
                ->orderBy('name')
                ->paginate(30)
                ->withQueryString(),
            'roles' => UserRole::cases(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::enum(UserRole::class)],
        ]);

        // Never let the last administrator demote themselves out of the admin.
        if ($user->isAdmin() && $validated['role'] !== UserRole::Admin->value) {
            $remaining = User::where('role', UserRole::Admin)->whereKeyNot($user->getKey())->count();

            if ($remaining === 0) {
                return back()->withErrors(['role' => 'There must be at least one administrator.']);
            }
        }

        $user->update($validated);

        return back()->with('status', "{$user->name} is now a {$user->role->label()}.");
    }
}
