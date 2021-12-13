<?php

namespace Admin\ResourceTools\Metadata;

use InWeb\Admin\App\AuthorizedToSee;
use InWeb\Admin\App\Fields\Text;
use InWeb\Admin\App\Fields\Textarea;
use InWeb\Admin\App\Http\Requests\AdminRequest;
use InWeb\Admin\App\Panel;
use InWeb\Admin\App\Resources\Resource;
use InWeb\Admin\App\ResourceTool;

class Metadata extends ResourceTool
{
    public $component = 'metadata-tool';

    public function name()
    {
        return __('Мета данные');
    }
}
