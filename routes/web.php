<?php

 Route::get('/admin/situacoes', 'Admin\AnswerController@categories')->name('situacoes.index');
 Route::get('/admin/situacoes/{id}/respostas', 'Admin\AnswerController@index')->name('situacoes.respostas.index');
 Route::get('/admin/situacoes/{id}/respostas/create', 'Admin\AnswerController@create')->name('situacoes.respostas.create');
 Route::post('/admin/situacoes/{url}/respostas', 'Admin\AnswerController@store')->name('situacoes.respostas.store');


 Route::get('/admin/analises', 'Admin\AnalyzeController@index')->name('analyzes.index');
 Route::get('/admin/analises/create', 'Admin\AnalyzeController@create')->name('analyzes.create');
 Route::post('/admin/analises', 'Admin\AnalyzeController@store')->name('analyzes.store');
 Route::get('/admin/analises/{id}/processar', 'Admin\AnalyzeController@processar')->name('analyzes.processar');
 Route::get('/admin/analises/{id}/rotina','Admin\AnalyzeController@rotina')->name('analyzes.rotina');

Route::get('/', function () {
    return view('welcome');
});
