<?php

namespace Admin\ResourceTools\Metadata;

use InWeb\Admin\App\Fields\Text;
use InWeb\Admin\App\Fields\Textarea;
use InWeb\Admin\App\Http\Requests\AdminRequest;
use InWeb\Admin\App\Resources\Resource;

class MetadataResource extends Resource
{
    public $component = 'metadata-tool';
    public static $model = \App\Models\Metadata::class;

    public function name()
    {
        return __('Мета данные');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param AdminRequest $request
     * @return array
     */
    public function fields(AdminRequest $request)
    {
        return [
            Text::make('Title', 'title'),
            Textarea::make('Description', 'description'),
        ];
    }
}
