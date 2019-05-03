<?php
/**
 * Article index
 *
 * @package Reactions
 * @author  Floris Luiten <floris@florisluiten.nl>
 */

/**
 * Macro for rendering a reaction
 *
 * @param App\Reaction[] $reactions The reactions
 * @param stdClass       $user      The current user
 *
 * @return void
 */
function renderReactions(array $reactions, stdClass $user)
{
    foreach ($reactions as $i => $reaction) {
        echo '<li class="reaction"><div class="wrapper" data-score="' . $reaction['score'] . '">
		<h2><img src="' . $reaction['userimage'] . '"><a href="#">' . $reaction['username'] . '</a></h2>';

        if ($reaction['userID'] != $user->userID) {
            echo '<form method="POST" action="/score/' . $reaction['reactionID'] . '" class="score-reaction">
			<label for="score-1">Score</label>
			<select name="score" id="score-1" class="form-control">
				<option value="-1">-1</option>
				<option value="0">0</option>
				<option value="1">+1</option>
				<option value="2">+2</option>
				<option value="3">+3</option>
			</select><input type="submit" value="Geef deze score" class="btn btn-default">
		</form>';
        }

        echo '<div class="currentscore ' . scoreToWord($reaction['score']) . '">'
        . htmlentities($reaction['score'], 0, 'UTF-8') . '</div>
		<time datetime="2019-04-29 21:32">' . htmlentities($reaction['publishDate'], 0, 'UTF-8') . '</time>
		<div class="usercontent">' . $reaction['content'] . '</div>
		<details>
			<summary>Reageer</summary>
			<form method="POST" action="?">
				<label for="reaction">Reageer:</label>
				<textarea name="reaction" class="form-control"></textarea>
				<input type="hidden" name="replyto" value="' . $reaction['reactionID'] . '">
				<input type="submit" value="Plaats reactie" class="btn btn-primary">
			</form>
		</details>
		</div>
		<ol>';
        renderReactions($reaction['children'], $user);
        echo ' </ol> </li>';
    }
}

/**
 * Macro for transforming the score to a generic word. Eg "-1" to "low"
 *
 * @param string $score The score
 *
 * @return string
 */
function scoreToWord(string $score)
{
    switch ($score) {
        case '-1':
            return 'low';
        case '0':
            return 'neutral';
        case 1:
            return 'good';
        case 2:
            return 'high';
        case 3:
            return 'veryhigh';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    
        <title>Hello</title>
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="/stylesheets/main.css">
    </head>
    <body>
        <div class="container">
            <nav class="navbar navbar-default" style="padding-right: 15px">
                <div class="navbar-header">
                    <a href="#" class="navbar-brand">Reactions</a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="#">Nieuws</a>
                    </li>
                    <li>
                        <a href="#">Reviews</a>
                    </li>
                    <li>
                        <a href="#">Meer</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#">
                            Ingelogd als: <strong><?php echo $user->username; ?></strong>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="container">
            <main>
                <h1><?php echo htmlentities($article->title, 0, 'UTF-8'); ?></h1>
                <?php echo $article->content; ?>
            </main>
            <section class="reactions">
                <div class="displaySettingBox row">
                    <div class="col-md-8"></div>
                    <div class="col-md-2">
                        <ul class="sorting">
                            <li><a href="?regular">Oudste bericht eerst</a></li>
                            <li><a href="?reverse">Nieuwste bericht eerst</a></li>
                        </ul>
                    </div>
                </div>
                <ol class="reactions">
<?php renderReactions($reactions, $user); ?>
                </ol>
                <form method="POST" action="?">
                    <label for="reaction">Reageer:</label>
                    <textarea name="reaction" class="form-control"></textarea>
                    <input type="submit" value="Plaats reactie" class="btn btn-primary">
                </form>
            </section>
        </div>
        <script src="/scripts/main.js"></script>
    </body>
</html>
