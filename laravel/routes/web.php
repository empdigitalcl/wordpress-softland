<?php

Route::get('softland/sync/catalog', 'SoftlandController@syncProducts');
Route::get('woo/sync/catalog/{take?}', 'SoftlandController@syncWCProducts');

