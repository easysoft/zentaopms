<?php
function ioncube_event_handler($err_code, $params) 
{
    $expired = "
        <html>
        <head>
        <meta http-equiv='content-type' content='text/html; charset=utf-8' />
        <title>error</title>
        </head>
        <body>
        <h1 style='color:red;text-align:center'>您的软件已经到期</h1>
        <p>网站错误：您的软件已经到期，功能无法继续使用。如果想要继续使用，请续费。</p>
        </body>
        </html>
        ";
    $server = "
        <html>
        <head>
        <meta http-equiv='content-type' content='text/html; charset=utf-8' />
        <title>error</title>
        </head>
        <body>
        <h1 style='color:red;text-align:center'>软件运行服务器受限制</h1>
        <p>网站错误：软件运行服务器IP受限制，请在正确的IP服务器下运行。</p>
        </body>
        </html>
        ";
    if($err_code == ION_LICENSE_EXPIRED)
    {
        echo $expired;
    }
    elseif($err_code == ION_LICENSE_SERVER_INVALID)
    {
        echo $server;
    }
}
