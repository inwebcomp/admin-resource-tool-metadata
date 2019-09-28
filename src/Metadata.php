<?php

namespace Admin\ResourceTools\Metadata;

use App\Models\Category;
use InWeb\Admin\App\ResourceTool;

class Metadata extends ResourceTool
{
    public $component = 'metadata-tool';

    public function name()
    {
        return __('Мета данные');
    }

    public static function rules()
    {
        return [
            'title' => 'required',
            'slug'  => '',
            'unit'  => '',
            'main'  => '',
            'object_id'  => 'required'
        ];
    }
}
