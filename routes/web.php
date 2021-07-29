<?php
Route::prefix('admin')
->namespace('Admin')
->middleware('auth')
->group(function() {
    Route::get('/situacoes', 'AnswerController@categories')->name('situacoes.index');
    Route::get('/situacoes/{id}/respostas', 'AnswerController@index')->name('situacoes.respostas.index');
    Route::get('/situacoes/{id}/respostas/create', 'AnswerController@create')->name('situacoes.respostas.create');
    Route::post('/situacoes/{url}/respostas', 'AnswerController@store')->name('situacoes.respostas.store');
    Route::put('/situacoes/{id}/respostas/{answerId}', 'AnswerController@update')->name('situacoes.respostas.update');
    Route::get('/situacoes/{id}/answer/{answerId}/edit', 'AnswerController@edit')->name('situacoes.respostas.edit');
   
   
    Route::get('/analises', 'AnalyzeController@index')->name('analyzes.index');
    Route::get('/analises/create', 'AnalyzeController@create')->name('analyzes.create');
    Route::post('/analises', 'AnalyzeController@store')->name('analyzes.store');
    Route::get('/analises/{id}/processar', 'AnalyzeController@processar')->name('analyzes.processar');
    Route::get('/analises/{id}/rotina','AnalyzeController@rotina')->name('analyzes.rotina');

    Route::any('analises/search', 'AnalyzeController@search')->name('analyzes.search');
   
});

 


Auth::routes();

Route::get('/', 'Admin\AnalyzeController@index')->middleware('auth');
