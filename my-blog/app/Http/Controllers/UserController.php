<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersProfileUpdateRequest;
use App\Http\Requests\UsersStoreRequest;
use App\Http\Requests\UsersUpdateRequest;
use App\Models\User;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private $userModel;

    public function __construct(User $user)
    {
        $this->userModel = $user;
    }
    public function index(){
        $users = (new \App\Models\User)->getUsersByPaginate(20);
        return view('admin.user.index', compact('users'));
    }

    public function create(){
        return view('admin.user.create');
    }

    public function store(UsersStoreRequest $request){


        $this->userModel->create($request->validated(), [

            'password' => bcrypt($request->password),

        ]);

        return redirect()->back();
    }

    public function edit($id){
        $user = auth()->user();
        return view('admin.user.edit', compact('user'));
    }

    public function update(UsersUpdateRequest $request, $id){

        $this->userModel->create($request->validated(), [
            'password' => bcrypt($request->password),
        ]);

        $this->userModel->save();

        return redirect()->back();
    }

    public function destroy($id){
        if($this->userModel){
            $this->userModel->delete();
        }
        return redirect()->back();
    }

    public function profile(){
        $user = auth()->user();
        return view('admin.user.profile', compact('user'));
    }

    public function profile_update(UsersProfileUpdateRequest $request){


        $this->userModel->create($request->validated());

        if($request->hasFile('image')){

            $this->userModel->uploadFile($request->validated());
        }
        return redirect()->back();
    }
}
