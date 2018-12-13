<?php
function url($url, $unique=false)
{
    $url = GlobalC::urlprefix . $url;
    (!$unique) ?: $url .= "?v=".uniqid('', true);
    return $url;
}
