<?php

/**
 * Повне видалення символів з тексту без заміни.
 *
 * Ця функція видаляє всі спеціальні символи з переданого тексту, які визначені у масиві `$special_chars`,
 * а також символи '%20' та '+' для видалення пробілів і пробілів в URL.
 * Функція також обрізає текст від крапок, дефісів і підкреслень на початку та в кінці рядка.
 * Повертає текст, оброблений функцією `htmlspecialchars()` для запобігання XSS-атакам.
 *
 * @param string $text Текст, з якого потрібно видалити спеціальні символи.
 * @return string Повертає текст без спеціальних символів.
 */

function clearspecialchars($text) {
    // Масив спеціальних символів, які потрібно видалити з тексту.
    $special_chars = array('?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '&', '$', '#', '*', '(', ')', '|', '~', '`', '!', '{', '}', '%', '+', chr(0));
    // Заміна символу неперервного пробілу на звичайний пробіл.
    $text = preg_replace("#\x{00a0}#siu", ' ', $text);
    // Видалення спеціальних символів.
    $text = str_replace($special_chars, '', $text);
    // Видалення '%20' і '+' (пробіли в URL).
    $text = str_replace(array('%20', '+'), '', $text);
    // Обрізання крапок, дефісів і підкреслень на початку та в кінці тексту.
    $text = trim($text, '.-_');
    // Повертає текст, оброблений функцією `htmlspecialchars()` для запобігання XSS-атакам.
    return htmlspecialchars($text);
}