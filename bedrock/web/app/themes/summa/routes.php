<?php

use Rareloop\Lumberjack\Facades\Router;

/**
 *  General Routes
 */
Router::get('', 'SiteController@home');
Router::get('index/{workId}', 'SiteController@work');
Router::get('index/{workId}/{bookId}', 'SiteController@book');
Router::get('index/{workId}/{bookId}/{chapterId}', 'SiteController@chapter');
Router::get('index/{workId}/{bookId}/{chapterId}/{articleId}', 'SiteController@article');


/**
 *  Admin Routes
 */
Router::get('/redaktion', 'AdminController@home');







