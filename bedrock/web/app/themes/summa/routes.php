<?php

use Rareloop\Lumberjack\Facades\Router;

/**
 *  General Routes
 */
Router::get('', 'SiteController@home');
Router::get('projekt', 'SiteController@project');
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



/**
 *  Async Routes
 */
Router::get('/book-count', 'AsyncController@bookCount');
Router::get('/chapter-count', 'AsyncController@chapterCount');
Router::get('/article-count', 'AsyncController@articleCount');
Router::get('/translator-count', 'AsyncController@translatorCount');
Router::get('/status-chapter-count', 'AsyncController@statusChapterCount');




