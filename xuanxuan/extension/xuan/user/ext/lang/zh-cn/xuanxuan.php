<?php
$lang->user->tokenInvalid  = "Token 认证失败，请尝试使用密码登录。";

/* When zentaourl?mode=getconfig is called, the $_SERVER does not have HTTP_USER_AGENT. */
if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') != false)
{
    $lang->user->errorDeny = "抱歉，您无权访问『<b>%s</b>』模块的『<b>%s</b>』功能。请联系管理员获取权限。";
}
