<?php
/**
 * HTTP response
 *
 * @author  Floris Luiten <floris@florisluiten.nl>
 * @package Reactions
 */

declare(strict_types=1);

namespace Fluiten\Reactions\Response;

use \Fluiten\Reactions as App;

class Http extends Base
{
    /**
     * Handle the HTTP request and return the response
     *
     * @param \Fluiten\Reactions\Request\Http $request The HTTP request
     *
     * @return string
     */
    public function handleRequest(\Fluiten\Reactions\Request\Http $request): string
    {
        if (isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->insertReaction($_POST['reaction']);
        }

        $resource = App\Models\Articles::queryById($this->database, '152056');
        $resource->execute();

        $article = $resource->fetch();

        if (!$article) {
            return $this->parseView('page-not-found');
        }
        
        $reactions = App\Models\Reactions::getThread($this->database, '152056');

        return $this->parseView('articles-index', array('article' => $article, 'reactions' => $reactions));
    }

    /**
     * Handle inserting new reaction
     *
     * @param string $reaction The reaction
     *
     * @return string
     */
    public function insertReaction(string $reaction)
    {
        $newReaction = new App\Models\Reactions();
        $newReaction->articleID = '152056';
        $newReaction->userID = '1';
        $newReaction->content = nl2br(htmlentities($reaction, 0, 'UTF-8'));
        $newReaction->parentID = null;
        $newReaction->publishDate = new \Datetime();

        return App\Models\Reactions::add($this->database, $newReaction);
    }
}
