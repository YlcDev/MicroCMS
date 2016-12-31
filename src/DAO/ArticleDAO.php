<?php
namespace MicroCMS\DAO;

use MicroCMS\Domain\Article;

class ArticleDAO extends DAO
{

    /**
     * Return a list of all articles, sorted by date (most recent first).
     *
     * @return array A list of all articles.
     */
    public function findAll() {
        $sql = "SELECT * FROM t_article ORDER BY art_id DESC";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $articles = array();
        foreach ($result as $row) {
            $articleId = $row['art_id'];
            $articles[$articleId] = $this->buildDomainObject($row);
        }
        return $articles;
    }

    /**
     * Returns an article matching the supplied id.
     *
     * @param integer $id The article id.
     *
     * @return \MicroCMS\Domain\Article|throws an exception if no matching article is found
     */
    public function find($id) {
        $sql = "SELECT * FROM t_article WHERE art_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No article matching id " . $id);
    }

    /**
     * saves an article into the ddatabase
     *
     * @param \MicroCMS\Domain\Article $article the article to save
     */
    public function save(Article $article)
    {
        $articleData = array(
            'art_title' => $article->getTitle(),
            'art_content' => $article->getContent(),
        );

        if ($article->getId()) {
            // the article has already been saved : update it
            $this->getDb()->update('t_article', $articleData, array('art_id' => $article->getId()));
        } else {
            // the article has never been saved : insert it
            $this->getDb()->insert('t_article', $articleData);
            // get the id of the newly created article and set it on the entity
            $id = $this->getDb()->lastInsertId();
            $article->setId($id);

        }
    }

    /**
     * Removes an article form the database
     *
     * @param integer $id the article id
     */
    public function delete($id) {
        // delete the article
        $this->getDb()->delete('t_article', array('art_id' => $id));
    }


    /**
     * Creates an Article object based on a DB row.
     *
     * @param array $row The DB row containing Article data.
     * @return \MicroCMS\Domain\Article
     */
    protected function buildDomainObject(array $row) {
        $article = new Article();
        $article->setId($row['art_id']);
        $article->setTitle($row['art_title']);
        $article->setContent($row['art_content']);
        return $article;
    }
}