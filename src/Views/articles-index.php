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
        </div>
    </body>
</html>
