<?php

namespace Plugins\Foundry\User\Services;

use Foundry\Exceptions\APIException;
use Foundry\Requests\Response;
use Foundry\Services\Service as FoundryService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Plugins\Foundry\User\Http\Requests\User as Form;
use Plugins\Foundry\User\Models\User as Model;
use Plugins\Foundry\User\Models\user;

class UserService extends FoundryService
{

    /**
     * Log user in
     *
     * @param Form $form
     * @return array|mixed
     */
    static function login(Form $form)
    {

        $response = $form->inputs();

        if($response['status']) {

            $inputs = $response['data'];

            $user = User::query()->where('email',$inputs('email'))->first();

            if(!$user)
                return Response::errorResponse(APIException::USER_NOT_FOUND, 404);

            if(Auth::attempt(['email' => $inputs('email'),
                'password' => $inputs('password')])){

                /**@var $user User*/
                $user = Auth::user();

                $result['user'] = $user;
                $result['token'] = $user->createToken('app')->accessToken;

                return Response::response($result);

            }else{
                return Response::errorResponse(APIException::LOGIN_DENIED, 401);
            }

        }else{
            return $response;
        }

    }

    /**
     * Register User
     *
     * @param Form $form
     * @return array|mixed
     */
    static function register(Form $form)
    {

        $response = $form->inputs();

        if($response['status']){

            $inputs = $response['data'];

            /**
             * Register user
             */
            User::create([
                'first_name' => $inputs['first_name'],
                'last_name' => $inputs['last_name'],
                'email' => $inputs['email'],
                'password' => Hash::make($inputs['password']),
            ]);

            /**
             * Log user in
             */
            Auth::attempt([
                'email' => $inputs['email'],
                'password' => $inputs['password']
            ]);

            /**@var $user User*/
            $user = Auth::user();

            $result['user'] = $user;
            $result['token'] = $user->createToken('app')->accessToken;


            return Response::response($result);

        }else{
            return $response;
        }

    }

    /**
     * Change password
     *
     * @param Form $form
     * @return array|mixed
     */
    static function changePassword(Form $form)
    {
        $response = $form->inputs();

        if(!$response['status']){
            return $response;
        }else{

            $inputs = $response['data'];

            $user = Auth::user();

            /**
             * @var $user User
             */
            if(!Hash::check($inputs['current_password'], $user->getAuthPassword()))
                return Response::errorResponse(APIException::WRONG_CURRENT_PASSWORD,403);

            $user->password = Hash::make($inputs['password']);
            $user->setRememberToken(Str::random(60));

            if($user->save())
                return Response::response(['user' => $user]);

            return Response::errorResponse(APIException::UNKNOWN_ERROR, 403);
        }

    }

    /**
     * Reset Password
     *
     * @param Form $form
     * @return mixed
     */
    static function resetPassword(Form $form)
    {
        $response = $form->inputs();

        if(!$response['status']){
            return $response;
        }else {

            $inputs = $response['data'];

            $user = User::query()->where('email', $inputs('email'))->first();

            if($user){

                /**
                 * @var $user User
                 */
                $user->password = Hash::make($inputs['password']);

                $user->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

                return Response::response();
            }else{
                return Response::errorResponse(APIException::USER_NOT_FOUND, 404);
            }

        }
    }

    /**
     * Request link to reset password
     *
     * @param Form $form
     * @return mixed
     */
    static function forgotPassword(Form $form)
    {

        $response = $form->inputs();

        if(!$response['status']){
            return $response;
        }else{

            $inputs = $response['data'];

            $response = UserService::broker()->sendResetLink(
                $inputs('email')
            );

            if ($response == Password::RESET_LINK_SENT){
                return Response::response();
            }
            else{
                $user = User::query()->where('email', $inputs('email'))->first();

                return $user? Response::errorResponse(APIException::UNKNOWN_ERROR, 400)
                    : Response::errorResponse(APIException::USER_NOT_FOUND, 404);
            }
        }
    }

    /**
     * Create or Update resource
     *
     * @param Form $form
     * @param $id
     * @return array
     */
    static function insert(Form $form, $id)
    {
        $model = new Model();

        if($id){
            $model = Model::query()->where('id', $id)->first();
        }

        $resp = $form->inputs();

        if($resp['status'])
            $model->fill($resp['data']);
        else
            return $resp;

        if($model->save()){
            return Response::response();
        }else{
            return Response::errorResponse(APIException::UNKNOWN_ERROR, 500);
        }
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    static function broker()
    {
        return Password::broker();
    }

    /**
     * Get the specified resource or all.
     * @param $id
     * @return array
     */
    static function get($id)
    {
        // TODO: get user
    }

    /**
     * Delete the specified resource.
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        // TODO: delete user
    }
}
