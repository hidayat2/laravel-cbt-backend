<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;


class UserController extends Controller
{
    public function index(Request $request)
    {
        // $users = \App\Model\User::all();
        //$users = \App\Models\User::paginate(10);
        $users = DB::table('users')
        ->when($request->input('name'), function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->input('name') . '%');
        })
        ->orderBy('id', 'desc')
        ->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        return view('pages.users.create');

    }

    // public function store(Request $request)
    public function store(StoreUserRequest $request)
    {
        // $request->validate([
        //     'name' => 'required|max:100|min:3',
        //     'email' => 'required|email|unique:users,email',
        //     'phone' => 'required|numeric',
        //     'roles' => 'required|in:ADMIN,STAFF,USER',
        //     'password' => 'required|min:8'
        // ]);
    //dd($request->all());
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        \App\Models\User::create($data);
        return redirect()->route('user.index')->with('success','User Successfully created');
    }

    public function edit($id)
    {
        $user = \App\Models\User::findOrFail($id);
        return view('pages.users.edit', compact('user'));

    }

    public function update(UpdateUserRequest $request, User $user)
    {
        // $data = $request->all();
        // $user = \App\Models\User::findOrFail($id);
        $data = $request->validated();
        $user->update($data);
        return redirect()->route('user.index')->with('success', 'User Successfully Update');

    }

    public function destroy(User $user)
    {
       $user->delete();
        return redirect()->route('user.index')->with('success', 'User Successfully Delete');

    }
}
