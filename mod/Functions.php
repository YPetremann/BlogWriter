<?php
function url($url, $unique=false)
{
    $url = GlobalS::urlprefix . $url;
    (!$unique) ?: $url .= "?v=".uniqid('', true);
    return $url;
}
