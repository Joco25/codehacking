<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UsersEditRequest;
use App\Http\Requests;
Use App\User;
use App\Role;
use App\Photo;


class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $users= User::all();


        return view('admin.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $roles = Role::lists('name', 'id')->all();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        //
        //return $request->all();

        if(trim($request->password)== ''){

            $input = $request->except('password');


        }else{
            $input = $request->all();
        }

        
         

         if($file = $request->file('photo_id')){
            
            $name = time() . $file->getClientOriginalName();

            $file->move('images', $name);

            $photo = Photo::create(['file'=>$name]);

            $input['photo_id'] = $photo->id;


         }
         $input['password'] = bcrypt($request->password);

         User::create($input);

         Session::flash('created_user', 'The user has been created');

         return redirect('/admin/users');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return view('admin.users.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $roles = Role::lists('name', 'id')->all();
        $user = User::findOrFail($id);


        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsersEditRequest $request, $id)
    {
        //Password Handling incase of an empty field
        if(trim($request->password)== ''){

            $input = $request->except('password');


        }else{
            $input = $request->all();
        }



        
        $user = User::findOrFail($id);

        //Saving user photo
        if($file = $request->file('photo_id')){

            $name = time() . $file->getClientOriginalName();

            $file->move('images', $name);

            $photo= Photo::create(['file'=>$name]);

            $input['photo_id'] = $photo->id;

        }
        //encrypt password functionality
         $input['password'] = bcrypt($request->password);

        $user->update($input);

        Session::flash('updated_user', 'The user has been Updated');

        return redirect('/admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::findOrFail($id);
        
        unlink(public_path(). $user->photo->file);

        $user->delete();

        Session::flash('deleted_user', 'The user has been deleted');

       return redirect('/admin/users');
    }
}
