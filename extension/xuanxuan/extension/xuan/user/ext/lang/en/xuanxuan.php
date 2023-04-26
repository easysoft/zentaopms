<?php
$lang->user->tokenInvalid  = "Token denied, please login with password.";

if(strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') != false)
{
    $lang->user->errorDeny = "Sorry, your access to <b>%2\$s</b> of <b>%1\$s</b> is denied. Please contact your Admin to get privileges.";
}
