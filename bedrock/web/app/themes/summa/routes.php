<?php

use Rareloop\Lumberjack\Facades\Router;

/**
 *  General Routes
 */
Router::get('', 'SiteController@home');
Router::get('books', 'SiteController@books');
Router::get('books/{bookId}', 'SiteController@book');
Router::get('books/{bookId}/questions', 'SiteController@questions');
Router::get('books/{bookId}/questions/{questionOrder}', 'SiteController@question');
Router::get('books/{bookId}/questions/{questionOrder}/articles', 'SiteController@articles');
Router::get('books/{bookId}/questions/{questionOrder}/articles/{articleOrder}', 'SiteController@article');







