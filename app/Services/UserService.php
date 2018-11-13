<?php

namespace Plugins\Foundry\User\Services;

use Foundry\Exceptions\APIException;
use Foundry\Requests\Response;
use Foundry\Services\Service as FoundryService;
use Plugins\Foundry\User\Http\Requests\User as Form;
use Plugins\Foundry\User\Models\User as Model;

class UserService extends FoundryService
{

    /**
     * Create or Update resource
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

        $model->fill($form->inputs());

        // TODO: Implement getTemplateContents() method.

        if($model->save()){
            return Response::response();
        }else{
            return Response::errorResponse(APIException::UNKNOWN_ERROR, 500);
        }
    }

    /**
     * Get the specified resource or all.
     * @param $id
     * @return array
     */
    static function get($id)
    {
        // TODO: Implement getTemplateContents() method.
    }

    /**
     * Delete the specified resource.
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        // TODO: Implement getTemplateContents() method.
    }
}
