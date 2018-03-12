<?php

Route::get('/search.html', ['as' => 'blog.search.index', 'uses' => 'Home\SearchController@index']);