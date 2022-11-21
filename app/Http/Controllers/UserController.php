<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Event;

use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('layouts.users', ['users' => $users]);
    }

    /**
     * Display the specified resource.
     * * @param  \App\Models\Event  $event
     * * @return \Illuminate\Http\Response
    **/
    public function show($id)
    {
        if (!Auth::check()) return redirect('/login');
        $user = User::find($id);
        $this->authorize('view', $user);
        return view('pages.userPage', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {   
        if (!Auth::check()) return redirect('/login');
        $user = User::find($request->input('userid'));
        $this->authorize('update', $user);

        if (!is_null($request->input('name'))) {
            $user->name = $request->input('name');
        }

        if (!is_null($request->input('email'))) {
            $user->email= $request->input('email');
        }

        if (!is_null($request->input('birthdate'))) {
            $user->birthdate = $request->input('birthdate');
        }

        if (!is_null($request->input('password'))) {
            $user->capacity = bcrypt($request->input('password'));
        }

        if (!is_null($request->input('gender'))) {
            $user->gender= $request->input('gender');
        }

        $user->save();
        return redirect('/user' . $user->userid);
    }



}
