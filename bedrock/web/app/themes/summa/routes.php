<?php

use Rareloop\Lumberjack\Facades\Router;

/**
 *  General Routes
 */
Router::get('', 'SiteController@home');
Router::get('projekt', 'SiteController@project');
Router::get('aktuelles', 'SiteController@news');
Router::get('index', 'SiteController@index');
Router::get('corpus', 'SiteController@corpus');

Router::get('index/{workId}', 'SiteController@work');
Router::get('index/{workId}/{bookId}', 'SiteController@book');
Router::get('index/{workId}/{bookId}/{chapterId}', 'SiteController@chapter');
Router::get('index/{workId}/{bookId}/{chapterId}/{articleId}', 'SiteController@article');


/**
 *  Admin Routes
 */
Router::get('/redaktion', 'AdminController@home');
Router::get('/redaktion/chapters/{chapterId}', 'AdminController@chapter');
Router::post('/redaktion/chapters/{chapterId}', 'AdminController@setChapter');
Router::post('/redaktion/translators/{translatorId}', 'AdminController@setTranslator');
Router::post('/redaktion/translators', 'AdminController@createTranslator');
Router::get('/redaktion/index/{workId}/{bookId}/{chapterId}/{articleId}', 'AdminController@article');
Router::post('/redaktion/preview', 'AdminController@getPreview');
Router::post('/redaktion/index/{workId}/{bookId}/{chapterId}/{articleId}', 'AdminController@setChunk');



/**
 *  Async Routes
 */
Router::get('/book-count', 'AsyncController@bookCount');
Router::get('/chapter-count', 'AsyncController@chapterCount');
Router::get('/article-count', 'AsyncController@articleCount');
Router::get('/translator-count', 'AsyncController@translatorCount');
Router::get('/status-chapter-count', 'AsyncController@statusChapterCount');




