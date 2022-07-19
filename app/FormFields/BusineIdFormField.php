<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class BusineIdFormField extends AbstractHandler
{
    protected $codename = 'busine_id';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('vendor.voyager.formfields.busine-id', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}