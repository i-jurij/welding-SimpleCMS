<?php

function my_sanitize_number($number)
{
    return filter_var($number, FILTER_SANITIZE_NUMBER_INT);
}

function my_sanitize_decimal($decimal)
{
    return filter_var($decimal, FILTER_SANITIZE_NUMBER_FLOAT);
}

function my_sanitize_string($string)
{
    // obrezka do 1000 znakov na vsak slu4aj
    $string = mb_substr($string, 0, 1000);
    $string = strip_tags($string);
    $string = addslashes($string);

    return htmlspecialchars($string);
}

function my_sanitize_html($string)
{
    $string = strip_tags($string, '<a><strong><em><hr><br><p><u><ul><ol><li><dl><dt><dd><table><thead><tr><th><tbody><td><tfoot>');
    $string = addslashes($string);

    return htmlspecialchars($string);
}

function my_sanitize_url($url)
{
    return filter_var($url, FILTER_SANITIZE_URL);
}

function my_sanitize_email($string)
{
    return filter_var($string, FILTER_SANITIZE_EMAIL);
}
