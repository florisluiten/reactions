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
        // User has logged in via Telepathy™
        $this->user = new \StdClass();
        $this->user->userID = '1';

        $path = $request->getPath();

        if ($path == '' or $path == '/') {
            header('HTTP/1.1 302 Found');
            header('Location: /news/152056');
            return 'Please be redirected';
        } elseif (substr($path, 0, 6) == '/news/') {
            list($slash, $news, $articleID) = explode('/', $path, 3);

            return $this->displayArticle($articleID);
        } elseif (substr($path, 0, 7) == '/score/' and
            isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] == 'POST'
        ) {
            list($slash, $news, $reactionID) = explode('/', $path, 4);

            $reaction = App\Models\Reactions::getById($this->database, $reactionID);

            if ($reaction->userID != $this->user->userID) {
                $newReaction = new App\Models\Scores();
                $newReaction->reactionID = $reactionID;
                $newReaction->userID = $this->user->userID;
                $newReaction->score = $_POST['score'];

                if (App\Models\Scores::add($this->database, $newReaction)) {
                    header('HTTP/1.1 302 Found');
                    header('Location: /news/152056');
                    return 'Please be redirected';
                }
            }
        }

        header('HTTP/1.1 Page not found');
        return 'Page not found';
    }

    /**
     * Display the specified article
     *
     * @param string $articleID The articleID
     *
     * @return boolean True on success, false otherwise
     */
    private function displayArticle($articleID)
    {
        if (isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->insertReaction($articleID, $_POST['reaction'], $_POST['replyto'] ?? null);
        }

        $resource = App\Models\Articles::queryById($this->database, $articleID);
        $resource->execute();

        $article = $resource->fetch();

        if (!$article) {
            return $this->parseView('page-not-found');
        }
        
        $reactions = App\Models\Reactions::getThread(
            $this->database,
            $articleID,
            isset($_GET['reverse']) ? \SORT_NEWEST_FIRST : \SORT_OLDEST_FIRST
        );

        return $this->parseView('articles-index', array('article' => $article, 'reactions' => $reactions));
    }

    /**
     * Handle inserting new reaction
     *
     * @param string $reaction The reaction
     * @param string $replyTo  The reactionID in case of a reply, defaults
     *                         to NULL
     *
     * @return string
     */
    public function insertReaction(string $articleID, string $reaction, string $replyTo = null)
    {
        $newReaction = new App\Models\Reactions();
        $newReaction->articleID = $articleID;
        $newReaction->userID = $this->user->userID;
        $newReaction->content = nl2br(htmlentities($reaction, 0, 'UTF-8'));
        $newReaction->parentID = $replyTo;
        $newReaction->publishDate = new \Datetime();

        return App\Models\Reactions::add($this->database, $newReaction);
    }
}
