<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
        }

        .news-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 20px;
        }

        .news-content {
            margin-top: 20px;
            font-size: 18px;
            color: #555;
        }

        .no-news-message {
            margin-top: 20px;
            font-size: 18px;
            color: #555;
        }
    </style>
</head>

<body>
    <header>
        <h1><?php echo $titre; ?></h1>
    </header>

    <div class="news-container">
        <?php if (isset($news)) : ?>
            <div class="news-content">
                <?php echo $news->act_id; ?> -- <?php echo $news->act_intitule; ?>
            </div>
        <?php else : ?>
            <p class="no-news-message"> ℹ️ Pas d'actualité !</p>
        <?php endif; ?>
    </div>
</body>

</html>
