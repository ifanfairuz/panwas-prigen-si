<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Laravel\Fortify\Rules\Password;
use Laravel\Jetstream\Jetstream;

class UsersController extends Controller
{

    /**
     * users page
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('isAdministrator', Jetstream::newTeamModel());
        $users = User::all();
        return Inertia::render('Users/Index', [
            'datas' => $users
        ]);
    }

    /**
     * add users page
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function add(Request $request)
    {
        try {
            Gate::authorize('isAdministrator', Jetstream::newTeamModel());
            return Inertia::render('Users/Add');
        } catch (\Exception $e) {
            return redirect()->route('administration.users.index');
        }
    }

    /**
     * create user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        Gate::authorize('isAdministrator', Jetstream::newTeamModel());
        $creator = app(CreateNewUser::class);
        $creator->create($request->all());
        session()->flash('flash.banner', 'User Created!');
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('administration.users.index');
    }

    /**
     * edit user page
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        try {
            Gate::authorize('isAdministrator', Jetstream::newTeamModel());
            $user = User::findOrFail($id);
            return Inertia::render('Users/Edit', [
                'data' => $user->toArray()
            ]);
        } catch (\Exception $e) {
            return redirect()->route('administration.users.index');
        }
    }

    /**
     * update user
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        Gate::authorize('isAdministrator', Jetstream::newTeamModel());
        $user = User::findOrFail($id);
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable', 'string', 'current_password:web'],
            'password' => ['nullable', 'string', new Password, 'confirmed'],
        ], [
            'current_password.current_password' => __('The provided password does not match your current password.')
        ])->validateWithBag('updateUser');
        
        $update = [
            'name' => $input['name'],
            'email' => $input['email'],
        ];
        if (@$input['password'] && $input['password'] !== "") $update['password'] = Hash::make($input['password']);
        $user->forceFill($update)->save();
        return response('', 200);
    }

    /**
     * delete user
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $id)
    {
        Gate::authorize('isAdministrator', Jetstream::newTeamModel());
        $user = User::findOrFail($id);
        $deletor = app(DeleteUser::class, [app(DeleteTeam::class)]);
        $deletor->delete($user);
        session()->flash('flash.banner', 'User Deleted!');
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('administration.users.index');
    }
}
