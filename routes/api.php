<?php

Route::group(['prefix' => 'resource-tool/metadata'], function () {
    Route::get('{resource}/{resourceId}', 'MetadataController@show');
    Route::put('{resource}/{resourceId}', 'MetadataController@update');
    Route::post('{resource}/{resourceId}', 'MetadataController@store');
});