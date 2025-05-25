<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserWord;
use App\Models\WordGroup;
use Couchbase\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $roles = ['user','admin','moderator'];
    protected $levels = ['beginner'=>'Начинающий','elementary'=>'Ниже среднего','intermediate'=>'Средний','upper-intermediate'=>'Выше среднего','advanced'=>'Продвинутый'];
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */



    public function index()
    {
        return view('admin.user.index', ['users' => User::all()]);
    }

    public function create()
    {
        return view('admin.user.create',['roles' => $this->roles]);
    }
    public function store(Request $req)
    {    $user = new User();
        $user->username = $req->input('username');
        $user->surname = $req->input('surname');
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->age = $req->input('age');
        $user->level = $req->input('level');
        $user->role = $req->input('role');
        $user->password = Hash::make($req->input('password'));
        $user->email_verified_at = ($req->input('email_verified_at')) ?? NULL;
        $user->save();
        return redirect('/admin/user')->with('status', 'Запись была добавлена');
    }



    public function edit(string $id)
    {
        $user = User::find($id);
        return view('admin.user.edit',['item' => $user,'roles' => $this->roles,'levels' => $this->levels]);
    }

    public function update(Request $req, string $id)
    {

        $user = User::findOrFail($id);
        $user->username = $req->input('username');
        $user->surname = $req->input('surname');
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->role = $req->input('role');
        $user->email_verified_at = ($req->input('email_verified_at')) ?? NULL;
        $user->save();
        return redirect('/admin/user')->with('status', 'Пользователь был обновлен');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    { dd($id);
        User::destroy($id);
        WordGroup:where('user_id', $id)->delete();
        return redirect('/admin/user')->with('status', 'Пользователь был удален');
    }



}
