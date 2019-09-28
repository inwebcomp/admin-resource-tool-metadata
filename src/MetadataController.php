<?php

namespace Admin\ResourceTools\Metadata;

use App\Traits\WithMetadata;
use Carbon\Carbon;
use DB;
use App\Models\Param;
use InWeb\Admin\App\Http\Controllers\Controller;
use InWeb\Admin\App\Http\Requests\ResourceDetailRequest;
use InWeb\Admin\App\Http\Requests\ResourceUpdateRequest;

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
        /** @var WithMetadata $object */
        $object = $request->findModelOrFail();

        $resource = new MetadataResource($object->metadata);

        $resource::validateForUpdate($request);

        $model = DB::transaction(function () use ($request, $resource, $object) {
            if (!$object->metadata) {
                $model = new \App\Models\Metadata();
                $model->associateWith($object);
            } else {
                $model = $object->metadata()->lockForUpdate()->first();
            }

            if ($this->modelHasBeenUpdatedSinceRetrieval($request, $model)) {
                return response('', 409)->throwResponse();
            }

            [$model, $callbacks] = $resource::fillForUpdate($request, $model);

            return tap(tap($model)->save(), function ($model) use ($request, $callbacks) {
                collect($callbacks)->each->__invoke();
            });
        });

        return $model;
    }

    /**
     * Determine if the model has been updated since it was retrieved.
     *
     * @param ResourceUpdateRequest $request
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    protected function modelHasBeenUpdatedSinceRetrieval(ResourceUpdateRequest $request, $model)
    {
        return false;

        $column = $model->getUpdatedAtColumn();

        if (!$model->{$column}) {
            return false;
        }

        return $request->input('_retrieved_at') && $model->usesTimestamps() && $model->{$column}->gt(
            Carbon::createFromTimestamp($request->input('_retrieved_at'))
        );
    }
}
