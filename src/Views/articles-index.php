<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    
        <title>Hello</title>
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="stylesheets/main.css">
    </head>
    <body>
        <div class="container">
            <nav class="navbar navbar-default">
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
            </nav>
        </div>
        <div class="container">
            <main>
                <h1><?php echo htmlentities($article->title, 0, 'UTF-8'); ?></h1>
                <?php echo $article->content; ?>
            </main>
            <section class="reactions">
                <ol class="reactions">
                    <li class="reaction">
                        <h2><img src="http://lorempixel.com/60/60/people/1"><a href="#">tincidunt91</a></h2>
                        <form method="POST" action="score" class="score-reaction">
                            <label for="score-1">Score</label>
                            <select name="score" id="score-1">
                                <option value="-1">-1</option>
                                <option value="0">0</option>
                                <option value="1">+1</option>
                                <option value="2">+2</option>
                                <option value="3">+3</option>
                            </select>
                            <input type="submit" value="Geef deze score">
                        </form>
                        <div class="currentscore">+2</div>
                        <time datetime="2019-04-29 21:32">29 april 2019 21:32</time>
                        <div class="usercontent"><p>Lorem ipsum dolor sit amet</p></div>
                        <ol>
                            <li class="reaction">
                                <h2><img src="http://lorempixel.com/60/60/people/3"><a href="#">justoNibh99</a></h2>
                                <form method="POST" action="score" class="score-reaction">
                                    <label for="score-1">Score</label>
                                    <select name="score" id="score-1">
                                        <option value="-1">-1</option>
                                        <option value="0">0</option>
                                        <option value="1">+1</option>
                                        <option value="2">+2</option>
                                        <option value="3">+3</option>
                                    </select>
                                    <input type="submit" value="Geef deze score">
                                </form>
                                <div class="currentscore">+2</div>
                                <time datetime="2019-04-29 21:32">29 april 2019 21:32</time>
                                <div class="usercontent"><p>Lorem ipsum dolor sit amet</p></div>
                            </li>
                            <li class="reaction">
                                <h2><img src="http://lorempixel.com/60/60/people/4"><a href="#">dap1bus</a></h2>
                                <form method="POST" action="score" class="score-reaction">
                                    <label for="score-1">Score</label>
                                    <select name="score" id="score-1">
                                        <option value="-1">-1</option>
                                        <option value="0">0</option>
                                        <option value="1">+1</option>
                                        <option value="2">+2</option>
                                        <option value="3">+3</option>
                                    </select>
                                    <input type="submit" value="Geef deze score">
                                </form>
                                <div class="currentscore">+2</div>
                                <time datetime="2019-04-29 21:32">29 april 2019 21:32</time>
                                <div class="usercontent"><p>Lorem ipsum dolor sit amet</p></div>
                            </li>
                        </ol>
                    </li>
                    <li class="reaction">
                        <h2><img src="http://lorempixel.com/60/60/people/2"><a href="#">-leo-</a></h2>
                        <form method="POST" action="score" class="score-reaction">
                            <label for="score-1">Score</label>
                            <select name="score" id="score-1">
                                <option value="-1">-1</option>
                                <option value="0">0</option>
                                <option value="1">+1</option>
                                <option value="2">+2</option>
                                <option value="3">+3</option>
                            </select>
                            <input type="submit" value="Geef deze score">
                        </form>
                        <div class="currentscore">+1</div>
                        <time datetime="2019-04-29 21:32">29 april 2019 21:32</time>
                        <div class="usercontent"><p>Lorem ipsum dolor sit amet</p></div>
                    </li>
                </ol>
            </section>
        </div>
    </body>
</html>
