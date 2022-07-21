<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class UserIdFormField extends AbstractHandler
{
    protected $codename = 'user_id';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('vendor.voyager.formfields.user-id', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}