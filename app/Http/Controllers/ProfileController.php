<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $allUsers = User::query()
            ->where('id', '!=', $user->id)
            ->orderBy('role', 'desc')
            ->paginate(10);

        return view('profile.index', ['user' => $user, 'allUsers' => $allUsers]);
    }

    public function editProfile(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedFields = Validator::make($request->all(), [
            'email' => 'email',
            'exp' => 'integer'
        ]);
        if ($validatedFields->fails()) {
            return redirect()->back()->withErrors($validatedFields)
                ->withInput();
        }
        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->exp = $request->exp;
        if ($request->jobgiver == 'on') {
            $request->jobgiver = 1;
        } else {
            $request->jobgiver = 0;
        }
        $user->jobgiver = $request->jobgiver;
        $user->bdate = $request->bdate;
        $user->login = $request->login;
        if ($request->jobgiver == 0)
            $request->company_name = null;
        $user->company_name = $request->company_name;
        $user->save();
        if ($request->get('new_password', null) != null && $request->get('confirmed_new_password', null) != null) {
            if ($request->new_password == $request->confirmed_new_password) {
                $password = md5($request->new_password);
                $user->password = $password;
                $user->save();

                return redirect()->back()->with(['success' => 'Данные успешно изменены, новый пароль сохранен']);
            }

            return redirect()->back()->withErrors('Пароли не совпадают, остальные данные были успешно сохранены');
        }

        return redirect()->back()->with(['success' => 'Данные успешно изменены']);
    }

    public function setAdmin(Request $request): \Illuminate\Http\JsonResponse
    {
        $currentUser = Auth::user();
        if ($currentUser->isAdmin()) {
            $user = User::query()
                ->find($request->get('id'));
            $user->role = 1;
            $user->save();

            return response()->json(['status' => 200]);
        }

        return response()->json(['status' => 401]);
    }

    public function disableAdmin(Request $request): \Illuminate\Http\JsonResponse
    {
        $currentUser = Auth::user();
        if ($currentUser->isAdmin()) {
            $user = User::query()
                ->find($request->get('id'));
            $user->role = 0;
            $user->save();
            return response()->json(['status' => 200]);
        }

        return response()->json(['status' => 401]);
    }
}
