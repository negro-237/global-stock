<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class LoginController extends BaseController
{

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

            $user = Auth::user();
            $user['token'] = $user->createToken('global-control')->plainTextToken;

            if($user->is_first_connexion) {
                return $this->sendResponse($user, 'first');
            }

            $user['roles'] = $user->roles->pluck('name');

            return $this->sendResponse($user, 'User login successfully.');
        }
        else {
            return $this->sendError('Identifiants invalides.', ['error' => 'Identifiants invalides']);
        }
    }

    public function logout() {
        auth()->user()->tokens()->delete();
        return $this->sendResponse([], 'Log out successfully.');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user->assignRole('user');
        $user['password'] = $request->password;

        // Send notification to user to notify account creation and include password
        $user->notify(new \App\Notifications\UserAccountCreatedNotification($user));

        return $this->sendResponse($success, 'User register successfully.');
    }

    public function initPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->is_first_connexion = false;
        $user->save();

        //$success['token'] =  $user->createToken('global-control')->plainTextToken;
        $success['name'] =  $user->name;
        $success['email'] =  $user->email;
        $success['roles'] =  $user->roles->pluck('name');

        return $this->sendResponse($success, 'User login successfully.');
    }
}
