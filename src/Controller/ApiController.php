<?php

namespace MicroCMS\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Article;

class ApiController {

    /**
     * Api articles controller
     *
     * @param Application $app Silex application
     *
     * @return All articles in JSON format
     */
    public function getArticlesAction(Application $app) {
        $articles = $app['dao.article']->findAll();
        // convert an array of objects ($articles) into an array of associative arrays ($responseData)
        $responseData = array();
        foreach ($articles as $article) {
            $responseData[] = $this->buildArticleArray($article);
        }
        // Create and return a JSON reponse
        return $app->json($responseData);
    }

    /**
     * Api article details controller
     *
     * @param integer $id Article id
     * @param Application $app Silex application
     *
     * @return Article details in JSON format
     */
    public function getArticleAction($id, Application $app) {
        $article = $app['dao.article']->find($id);
        $responseData = $this->buildArticleArray($article);
        // Create and return  a JSON format
        return $app->json($responseData);
    }

    /** Api create article controller
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     *
     * @return Article details in JSON format
     */
    public function addArticleAction(Request $request, Application $app) {
        // Check request parameters
        if (!$request->request->has('title')) {
            return $app->json('Missing required parameter: title', 400);
        }
        if (!$request->request->has('content')) {
            return $app->json('Missing required parameter: content', 400);
        }
        // Build and save the new article
        $article = new Article();
        $article->setTitle($request->request->get('title'));
        $article->setContent($request->request->get('content'));
        $app['dao.article']->save($article);
        $responseData = $this->buildArticleArray($article);
        return $app->json($responseData, 201); // 201 = Created
    }

    /**
     * Api delete article controller
     *
     * @param integer $id Article id
     * @param Application $app Silex application
     */
    public function deleteArticleAction($id, Application $app) {
        // Delete the article
        $app['dao.comment']->deleteAllByArticle($id);
        // delete the article
        $app['dao.article']->delete($id);
        return $app->json('No content', 204); // 204 = no content
    }

    /**
     * Converts a Article object into an associative array for JSON encoding
     *
     * @param Article $article Article object
     *
     * @return array Associative array whose fileds are the article properties
     */
    private function buildArticleArray(Article $article) {
        $data = array(
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        );
        return $data;
    }
}