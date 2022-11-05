<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserServiceInterface;
use App\Utilities\Common;
use App\Utilities\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    private $userService;

    public function __construct(UserServiceInterface $userService){
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //GET METHOD
    public function index(Request $request)
    {
        $users = $this->userService->searchAndPaginate('name',$request->get('search'),4);
        return view('admin.user.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //GET METHOD
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //POST METHOD
    public function store(Request $request)
    {
        if($this->userService->checkExistAccount($request->input('email'),$request->input('level'))){
            return back()->with('notification','ERROR : Account is Exist !!!');
        }

        if($request->get('password') != $request->get('password_confirmation')){
            return back()->with('notification','ERROR : Confirm password is not match');
        }
        $data = $request->all();
        $data['password'] = bcrypt($request->get('password'));

        $path = '';
        if($request->level == Constant::user_level_client || $request->level == Constant::user_level_sealed){
            $path = 'front/img/user';
        } else $path = 'dashboard/assets/images/avatars';
        //handle Avatar
        if($request->hasFile('image')){
            $data['avatar'] = Common::uploadFile($request->file('image'),$path);
        }


        $user = $this->userService->create($data);

        return redirect('admin/user/'.$user->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    //GET METHOD
    public function show(User $user)
    {
        //Handle set URL other admin id
        if($user->level == Constant::user_level_client || $user->id == Auth::id() || $user->level == Constant::user_level_sealed){

            return view('admin.user.show',compact('user'));
        }
        return back()->with('notification','ERROR : Permission denied');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //Handle set URL other admin id
        if($user->level == Constant::user_level_client || $user->id == Auth::id() || $user->level == Constant::user_level_sealed){

            return view('admin.user.edit',compact('user'));
        }
        return back()->with('notification','ERROR : Permission denied');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        //path for level admin
        $data = $request->all();
        $path = 'dashboard/assets/images/avatars';
        if($request->hasFile('image')){
            //Handle Avatar
            //add new file
            $data['avatar'] = Common::uploadFile($request->file('image'),$path);
            //delete old file
            $file_name_old = $request->get('image_old');
            if($file_name_old != ''){
                unlink($path . $file_name_old);
            }

        }
//
        $this->userService->update($data,$user->id);

        return redirect('/admin/user/'.$user->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
