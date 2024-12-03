<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LiveCMS - php-startkit. Легкий старт для розробки сучасних веб-додатків.">
    <meta name="author" content="Довгопол Микола">
    <title>LiveCMS - php-startkit</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #0078d7, #004b87);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }
        .container {
            max-width: 600px;
            padding: 30px;
            background: #fff;
            color: #333;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        h1 {
            margin: 0 0 20px;
            font-size: 2.5rem;
            color: #0078d7;
        }
        p {
            margin: 10px 0;
            line-height: 1.6;
        }
        ul {
            text-align: left;
            margin: 15px 0;
            padding: 0;
            list-style: none;
        }
        ul li {
            margin: 10px 0;
            display: flex;
            align-items: center;
        }
        ul li::before {
            content: '✔';
            color: #0078d7;
            font-size: 1rem;
            margin-right: 10px;
        }
        a {
            color: #0078d7;
            font-weight: bold;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.9rem;
            color: #666;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background-color: #0078d7;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #005ea8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>StartKit</h1>
        <p>Легкий старт для розробки сучасних PHP-додатків.</p>
        <p><strong>Автор:</strong> Довгопол Микола</p>
        <p><strong>Офіційний сайт:</strong> <a href="https://livecms.online" target="_blank">livecms.online</a></p>
        <p><strong>Скачати:</strong> <a href="https://github.com/lifesheets/php-startkit" target="_blank">GitHub</a></p>

        <h2>Вимоги</h2>
        <ul>
            <li>PHP 8.3+</li>
            <li>MySQL 5.7+</li>
            <li>Драйвер PDO</li>
            <li>Виклик зовнішніх програм (<code>exec()</code>) увімкнений</li>
            <li>Віддалене з'єднання з програмами (наприклад, FFmpeg)</li>
            <li>Запис даних у файл (<code>file_put_contents()</code>) увімкнений</li>
            <li>Читання файлів (<code>file_get_contents()</code>) увімкнений</li>
            <li>Клас для роботи із zip-архівами (<code>ZipArchive</code>) увімкнений</li>
        </ul>

        <h2>Документація</h2>
        <p>Перегляньте офіційну документацію на сайті:</p>
        <a class="btn" href="https://livecms.online/docs" target="_blank">Документація</a>

        <div class="footer">
            <p>&copy; 2024 LiveCMS. Усі права захищено.</p>
        </div>
    </div>
</body>
</html>
