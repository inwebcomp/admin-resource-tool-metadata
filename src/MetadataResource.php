<?php

namespace Admin\ResourceTools\Metadata;

use InWeb\Admin\App\AuthorizedToSee;
use InWeb\Admin\App\Fields\Text;
use InWeb\Admin\App\Fields\Textarea;
use InWeb\Admin\App\Http\Requests\AdminRequest;
use InWeb\Admin\App\Resources\Resource;
use InWeb\Admin\App\ResourceTool;

class MetadataResource extends Resource
{
    public        $component = 'metadata-tool';
    public static $model     = \InWeb\Metadata\Models\Metadata::class;

    public function name()
    {
        return __('Мета данные');
    }

    public function fields(AdminRequest $request): array
    {
        return [
            Text::make(__('Заголовок'), 'title'),
            Textarea::make(__('Описание'), 'description'),
            Textarea::make(__('Ключевые слова'), 'keywords'),
        ];
    }
}
