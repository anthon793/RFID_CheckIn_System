<?php

function require_login()
{
    if (!isset($_SESSION['id'])) {
        header('Location: login.php');
        exit();
    }
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function valid_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

function set_flash($icon, $title, $text, $variant = '')
{
    $_SESSION['flash'] = [
        'icon' => $icon,
        'title' => $title,
        'text' => $text,
        'variant' => $variant,
    ];
}

function get_flash()
{
    if (empty($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}
