<?php

namespace Plugins\Foundry\User\Http\Controllers;

use Foundry\Exceptions\APIException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Foundry\Requests\Response as FoundryResponse;
use Illuminate\Routing\Controller;
use Plugins\Foundry\User\Http\Requests\ForgotPassword;
use Plugins\Foundry\User\Http\Requests\Login;
use Plugins\Foundry\User\Http\Requests\Password;
use Plugins\Foundry\User\Http\Requests\Register;
use Plugins\Foundry\User\Http\Requests\ResetPassword;
use Plugins\Foundry\User\Services\UserService;


/**
 * Class UserController
 *
 *  @OA\Info(
 *      version="v1",
 *      title="Foundry Core API",
 *      description="",
 *      @OA\Contact(
 *          email="medard@co-foundry.co.za"
 *      )
 * )
 *  @OA\Server(
 *      url="https://co-foundry.co.za/api/v1",
 *      description="Foundry API Server"
 * )
 * @OA\SecurityScheme(
 *     type="oauth2",
 *     description="Use your username / password combo to obtain a token",
 *     name="Password Based",
 *     in="header",
 *     scheme="https",
 *     securityScheme="Password Based"
 * )
 *
 * @package Plugins\Foundry\User\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * Log user in
     *
     * @OA\Post(
     *      path="/login",
     *      operationId="login",
     *      tags={"User"},
     *      summary="Log user in",
     *      description="Log user in",
     *      @OA\Response(
     *          response=200,
     *          description="successful response"
     *       ),
     *     @OA\Parameter(
     *          name="email",
     *          description="E-mail address",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="password",
     *          description="Password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *  )
     *
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        $form = new Login($request->only(Login::fields()));

        return UserService::login($form);
    }


    /**
     * Register new User
     *
     * @OA\Post(
     *      path="/register",
     *      operationId="register",
     *      tags={"User"},
     *      summary="Register new User",
     *      description="Register new User",
     *      @OA\Response(
     *          response=200,
     *          description="successful response"
     *       ),
     *     @OA\Parameter(
     *          name="first_name",
     *          description="First name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="last_name",
     *          description="Last name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="email",
     *          description="E-mail address",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="password",
     *          description="Password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="password_confirmation",
     *          description="Password repeat",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *  )
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function register(Request $request)
    {
        $form = new Register($request->only(Register::fields()));

        return UserService::register($form);
    }


    /**
     * Authentication failed
     *
     * @post{
     * }
     * @return mixed
     */
    public function authenticate(){
        return FoundryResponse::errorResponse(APIException::INVALID_USER_TOKEN, 401);
    }

    /**
     * Change Password
     *
     * @OA\Post(
     *      path="/password",
     *      operationId="password",
     *      tags={"User"},
     *      summary="Change current password",
     *      description="Change Password",
     *      @OA\Response(
     *          response=200,
     *          description="successful response"
     *       ),
     *     @OA\Parameter(
     *          name="current_password",
     *          description="Current Password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="password",
     *          description="Password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="password_confirmation",
     *          description="Password repeat",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *  )
     * @param Request $request
     * @return mixed
     */
    public function changePassword(Request $request){

        $form = new Password($request->only(Password::fields()));

        return UserService::changePassword($form);
    }

    /**
     * Send link to user to reset password
     *
     * @param Request $request
     * @return mixed
     */
    public function forgotPassword(Request $request){

        $form = new ForgotPassword($request->only(ForgotPassword::fields()));

        return UserService::forgotPassword($form);
    }

    /**
     * Reset user password
     *
     * @param Request $request
     * @return mixed
     */
    public function resetPassword(Request $request){

        $form = new ResetPassword($request->only(ResetPassword::fields()));

        return UserService::resetPassword($form);

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('foundry_user::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('foundry_user::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('foundry_user::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
