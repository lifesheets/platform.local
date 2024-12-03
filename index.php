<?php

/**
 * Захист від прямого доступу.
 * Встановлює константу `SECURITY_LIVECMS`, яка запобігає прямому виклику скрипта.
 */

define('SECURITY_LIVECMS', true);

/**
 * Визначення кореневої директорії проєкту.
 * Константа `ROOT` вказує шлях до поточної директорії, де знаходиться скрипт.
 */

define('ROOT', dirname(__FILE__));

/**
 * Визначення шляху до директорії фреймворку.
 * Константа `LCMS` вказує шлях до фреймворку відносно кореня проєкту.
 */

define('LCMS', ROOT . '/framework');

/**
 * Автозавантаження класів.
 * Підключає файл, що автоматично завантажує необхідні класи.
 */

require_once LCMS . '/autoload.php';

/**
 * Отримання значень параметрів з запиту.
 * Параметри `base`, `path`, `subpath` та `section` витягуються за допомогою класу `direct`.
 */

$base = direct::get('base');
$path = direct::get('path');
$subpath = direct::get('subpath');
$section = direct::get('section');

/**
 * Перевірка наявності параметрів у запиті.
 * Якщо будь-який з параметрів `base`, `path`, `subpath` або `section` присутній в URI, виконується перенаправлення на головну сторінку.
 */

$paramsToCheck = ['base', 'path', 'subpath', 'section'];
if (array_reduce($paramsToCheck, fn($carry, $param) => $carry || str_contains(REQUEST_URI, "$param="), false)) {
    redirect('/');
}

/**
 * Функція для підключення файлу, якщо він існує.
 *
 * @param string $path Шлях до файлу відносно кореня проєкту.
 * @param string $type Тип об'єкта (наприклад, 'file' або 'dir').
 */

function includeFile(string $path, string $type): void {
    if (direct::existsPath($path, $type)) {                  // Перевірка існування шляху та типу.
        require_once ROOT . $path;                           // Підключення файлу, якщо він існує.
        exit;                                                // Завершення виконання скрипта.
    }
}

/**
 * Логіка обробки запиту:
 * Перевіряються вкладені директорії та файли. Якщо вказаний файл чи директорія існують,
 * вони підключаються відповідно до структури запиту.
 */

if (direct::existsPath("/$base/", 'dir')) {
    if (direct::existsPath("/$base/$path/$subpath/", 'dir')) {
        includeFile("/$base/$path/$subpath/$section.php", 'file')
            ?: includeFile("/$base/$path/$subpath/index.php", 'file')
            ?: includeFile('/modules/main/index.php', 'file');
    } elseif (direct::existsPath("/$base/$path/", 'dir')) {
        includeFile("/$base/$path/$section.php", 'file')
            ?: includeFile("/$base/$path/index.php", 'file')
            ?: includeFile('/modules/main/index.php', 'file');
    } else {
        includeFile("/$base/$section.php", 'file')
            ?: includeFile("/$base/index.php", 'file')
            ?: includeFile('/modules/main/index.php', 'file');
    }
} else {
    // Якщо жодна директорія не знайдена, підключається головний модуль.
    require_once ROOT . '/modules/main/index.php';
}
