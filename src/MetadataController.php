<?php

namespace Admin\ResourceTools\Metadata;

use DB;
use InWeb\Admin\App\Http\Controllers\Controller;
use InWeb\Admin\App\Http\Requests\ResourceDetailRequest;
use InWeb\Admin\App\Http\Requests\ResourceUpdateRequest;
use InWeb\Base\Entity;
use InWeb\Metadata\WithMetadata;

class MetadataController extends Controller
{
    /**
     * @param ResourceDetailRequest $request
     * @return \Illuminate\Support\Collection
     */
    public function show(ResourceDetailRequest $request)
    {
        /** @var WithMetadata $model */
        $model = $request->findModelOrFail();

        $resource = new MetadataResource($model->metadata);

        return $resource->resolveEditFields($request);
    }

    /**
     * @param ResourceUpdateRequest $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function update(ResourceUpdateRequest $request)
    {
        /** @var WithMetadata|Entity $object */
        $object = $request->findModelOrFail();

        $resource = new MetadataResource($object->metadata);

        $resource::validateForUpdate($request);

        $model = DB::transaction(function () use ($request, $resource, $object) {
            if (!$object->metadata) {
                $model = new \InWeb\Metadata\Models\Metadata();
                $model->metadatable()->associate($object);
            } else {
                $model = $object->metadata()->lockForUpdate()->first();
            }

            [$model, $callbacks] = $resource::fillForUpdate($request, $model);

            return tap(tap($model)->save(), function ($model) use ($request, $callbacks) {
                collect($callbacks)->each->__invoke();
            });
        });

        return $model;
    }
}
