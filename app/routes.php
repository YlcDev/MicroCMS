<?php

// home page
$app->get('/', "MicroCMS\Controller\HomeController::indexAction")
    ->bind('home');

// detailed info about an article
$app->match('/article/{id}', "MicroCMS\Controller\HomeController::articleAction")
    ->bind('article');

// login form
$app->get('/login', "MicroCMS\Controller\HomeController::loginAction")
    ->bind('login');

// admin zone
$app->get('/admin', "MicroCMS\Controller\AdminController::indexAction")
    ->bind('admin');

// add a new article
$app->match('/admin/article/add', "MicroCMS\Controller\AdminController::addArticleAction")
    ->bind('admin_article_add');

// edit an existing article
$app->match('/admin/article/{id}/edit', "MicroCMS\Controller\AdminController::editArticleAction")
    ->bind('admin_article_edit');

// remove an article
$app->get('/admin/article/{id}/delete', "MicroCMS\Controller\AdminController!!deleteArticleAction")
    ->bind('admin_article_delete');

// edit an existing comment
$app->match('/admin/comment/{id}/edit', "MicroCMS\Controller\AdminController::editCommentAction")
    ->bind('admin_comment_edit');

// remove a comment
$app->get('/admin/comment/{id}/delete', "MicroCMS\Controller\AdminController::deleteCommentAction")
    ->bind('admin_comment_delete');

// add a user
$app->match('/admin/user/add', "MicroCMS\Controller\AdminController::adduserAction")
    ->bind('admin_user_add');

// edit an existing user
$app->match('/admin/user/{id}/edit', "MicroCMS\Controller\AdminController::editUserAction")
    ->bind('admin_user_edit');

// remove a user
$app->get('/admin/user/{id}/delete', "MicroCMS\Controller\AdminController::deleteUserAction")
    ->bind('admin_user_delete');

// API : get all articles
$app->get('/api/articles', "MicroCMS\Controller\ApiController::getArticlesAction")
    ->bind('api_articles');

// API : get an article
$app->get('/api/article/{id}', "MicroCMS\Controller\ApiController::getArticleAction")
    ->bind('api_article');

// API: create an article
$app->post('/api/article', "MicroCMS\Controller\ApiController::addArticleAction")
    ->bind('api_article_add');

// API : remove an article
$app->delete('/api/article/{id}', "MicroCMS\Controller\ApiController::deleteArticleAction")
    ->bind('api_article_delete');

