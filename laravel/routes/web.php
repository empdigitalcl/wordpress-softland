<?php

Route::get('softland/sync/catalog', 'SoftlandController@syncProducts');
Route::any('woo/sync/nv', 'SoftlandController@postIngresaNotadeVenta');
Route::get('woo/sync/catalog/{take?}', 'SoftlandController@syncWCProducts');

