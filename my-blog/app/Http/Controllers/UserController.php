<?php

namespace App\Http\Controllers;

use App\Models\User;
use Session;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::latest()->getUsersByPaginate(20);
        return view('admin.user.index', compact('users'));
    }

    public function create(){
        return view('admin.user.create');
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'description' => $request->description,
        ]);
        
        return redirect()->back();
    }

    public function edit($id){
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id){
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email, $user->id",
            'password' => 'sometimes|nullable|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->description = $request->description;
        $user->save();
        
        return redirect()->back();
    }

    public function destroy($id){
        if($user){
            $user->delete();            
        }
        return redirect()->back();
    }

    public function profile(){
        $user = auth()->user();
        return view('admin.user.profile', compact('user'));
    }

    public function profile_update(Request $request){
        $user = auth()->user();

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email, $user->id",
            'password' => 'sometimes|nullable|min:8',
            'image'=> 'sometimes|nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->description = $request->description;

        if($request->has('password') && $request->password !== null){
            $user->password = bcrypt($request->password);
        }

        if($request->hasFile('image')){
            
            $user->image = Storage::putFile('image', $request->file('image'));
        }
        $user->save();        
        return redirect()->back();
    }
}
