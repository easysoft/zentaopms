<?php
function ioncube_event_handler($err_code, $params) 
{
    $expiredate = "%expiredate%";
    $expired = "
        <html>
        <head>
        <meta http-equiv='content-type' content='text/html; charset=utf-8' />
        <title>error</title>
        </head>
        <body>
        <h2 style='color:red;text-align:center'>您使用的版本已经过期</h2>
        <p>您当前使用的版本截止日期是{$expiredate}，已经过期，请联系我们购买授权。</p>
        <p>电话：4006 889923</p>
        <p>QQ：co@zentao.net(1492153927)</p>
        <p>Email：co@zentao.net</p>
        <p>网站：<a href='http://www.zentao.net' target='_blank'>http://www.zentao.net</a></p>
        <br /> <br /> <br />
        <h2 style='color:red;text-align:center'>The edition has been out of date.</h2>
        <p>The edition's end date is $expiredate, has been out of date now, please contact us to renew your license.</p>
        <p>Email：co@zentao.net</p>
        <p>网站：<a href='http://www.zentao.net/en/' target='_blank'>http://www.zentao.net/en/</a></p>
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
        <h2 style='color:red;text-align:center'>错误的IP地址或MAC地址</h2>
        <p>软件授权的IP地址或MAC地址和当前系统的IP地址或MAC地址不一致，请使用最初授权的服务器。</p>
        <p>电话：4006 889923</p>
        <p>QQ：co@zentao.net(1492153927)</p>
        <p>Email：co@zentao.net</p>
        <p>网站：<a href='http://www.zentao.net' target='_blank'>http://www.zentao.net</a></p>
        <br /> <br /> <br />
        <h2 style='color:red;text-align:center'>Wrong ip address or mac address.</h2>
        <p>The ip or mac address of current server is wrong. Please run zentao on the server we licensed.</p>
        <p>Email : co@zentao.net</p>
        <p>Site : <a href='http://www.zentao.neti/en/' target='_blank'>http://www.zentao.net/en/</a></p>
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
    exit;
}
