<?php

namespace Plugins\Foundry\User\Http\Requests;

use Foundry\Requests\Form as FormRequest;

class user extends FormRequest
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
         // TODO: Implement rules() method.
         return [
             //
         ];
     }

     /**
      * Determine if the user is authorized to make this request.
      *
      * @return bool
      */
     public function authorize()
     {
         // TODO: Implement authorize() method.
         return true;
     }

     /**
      * Get available fields based on the permissions of the currently logged in user.
      *
      * @return array
      */
     public function fields()
     {
         // TODO: Implement fields() method.
         return [
             //
         ];
     }

     /**
      * Get custom error messages for rules
      *
      * @return array
      */
     public function messages()
     {
         // TODO: Implement messages() method.
         return [
             //
         ];
     }

    public function getFormView()
    {
        // TODO: Implement getFormView() method.
    }
}
