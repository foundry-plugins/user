<?php

namespace Plugins\Foundry\User\Http\Requests;


class Register extends user
{
     public function __construct($inputs)
     {
        parent::__construct($inputs);
     }

     /**
      * Get the validation rules that apply to the request.
      *
      * @return array
      */
     public function rules()
     {
         return [
             'first_name' => 'required|string|max:255',
             'last_name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:6|confirmed',
         ];
     }

     /**
      * Determine if the user is authorized to make this request.
      *
      * @return bool
      */
     public function authorize()
     {
         return true;
     }

     /**
      * Get available fields based on the permissions of the currently logged in user.
      *
      * @return array
      */
     static function fields()
     {
         return [
             'first_name',
             'last_name',
             'email',
             'password',
             'password_confirmation'
         ];
     }

     /**
      * Get custom error messages for rules
      *
      * @return array
      */
     public function messages()
     {
         return [];
     }

    public function getFormView()
    {
        // TODO: Implement getFormView() method.
    }
}
