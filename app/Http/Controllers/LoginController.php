<?php

namespace App\Http\Controllers;

use App\Models\User;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Auth;
use DB;
use Exception;
use Hash;
use Session;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('reports.index');
        } else {
            return view('login.index');
        }
    }

    public function actionLogin(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $check = User::where(['username' => $request->username])->first();

                if ($check) {
                    if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'is_active' => 1])) {
                        return redirect()->route('reports.index');
                    }
                }

                throw new Exception('Wrong username or password');
            } catch (Exception $e) {
                return redirect()->route('login.index')->with(['error' => $e->getMessage()]);
            }
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login.index')->with(['success' => 'Logout successfully']);
    }

    public function register()
    {
        return view('login.register');
    }

    public function saveRegister(Request $request)
    {
        if ($request->isMethod('post')) {
            DB::beginTransaction();
            try {
                if ($request->password !== $request->confirm_password) {
                    throw new Excpetion('Confirm password incorrect.');
                }

                $users = new User;
                $users->name = $request->fullname;
                $users->email = $request->email;
                $users->username = $request->username;
                $users->user_level_id = 2;
                $users->is_active = 1;
                $users->password = bcrypt($request->confirm_password);
                $users->created_at = Carbon::now();
                $users->save();

                DB::commit();

                return redirect()->route('login.index')->with(['success' => 'Register users successfully']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->route('register.index')->with(['error' => $e->getMessage()]);
            }
        }
    }
}