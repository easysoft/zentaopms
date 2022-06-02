<?php
function ioncube_event_handler($err_code, $params)
{
    $extensionLink = helper::createLink('extension', 'browse');
    $expired = "
        <html>
        <head>
        <meta http-equiv='content-type' content='text/html; charset=utf-8' />
        <style>
        .extension-btn {min-width: 120px; color: #fff; background-color: #0c64eb; border-color: transparent; display: inline-block; padding: 6px 12px; margin-bottom: 0; font-size: 13px; font-weight: 400; line-height: 18px; text-align: center; white-space: nowrap; vertical-align: middle; cursor: pointer; user-select: none; border: 1px solid transparent; border-radius: 4px; transition: .4s cubic-bezier(.175,.885,.32,1);}
        a {text-decoration: none;}
        </style>
        <title>error</title>
        </head>
        <body style='font-size: 13px;'>
        <h2 style='color:red;text-align:center'>您使用的插件已经过期</h2>
        <p style='text-align:left; margin: 0px 20%;'>您的插件已过期，如果需要继续使用，请点击下载更新按钮，跳转到禅道官网插件页面进行下载后再更新插件。</p>
        <p style='text-align:left; margin: 0px 20%;'>如果不需要继续使用，可以点击卸载插件按钮，跳转到插件页面卸载插件。</p>
        <p style='text-align:left; margin: 0px 20%;'>如果您无法操作，请联系系统管理员进行处理。</p>
        <p style='text-align:center; margin: 13px 0px;'>
        <a href='https://www.zentao.net/extension-browse.html' target='_blank' class='btn btn-wide btn-primary extension-btn' style='margin-right: 20px;'>下载更新</a>
        <a href='{$extensionLink}' target='_top' class='btn btn-wide extension-btn' style='background-color: #fff; border-color: #d6dae3; color: #3c4353;'>卸载插件</a>
        </p>
        <br /> <br /> <br />
        <h2 style='color:red;text-align:center'>Your plugin has expired</h2>
        <p style='text-align:left; margin: 0px 20%;'>Your plugin has expired. If you need to keep using it, please click the 'Download Update' button to jump to the ZenTao plugin page to download and update.</p>
        <p style='text-align:left; margin: 0px 20%;'>If you decide not to use the plugin, please click the 'Delete Plugin' button to jump to the plugins page to delete it.</p>
        <p style='text-align:left; margin: 0px 20%;'>If you do not have permission, please contact the system administrator to deal with it.</p>
        <p style='text-align:center; margin: 13px 0px;'>
        <a href='https://www.zentao.net/extension-browse.html' target='_blank' class='btn btn-wide btn-primary extension-btn' style='margin-right: 20px;'>Download Update</a>
        <a href='{$extensionLink}' target='_top' class='btn btn-wide extension-btn' style='background-color: #fff; border-color: #d6dae3; color: #3c4353;'>Delete Plugin</a>
        </p>
        </body>
        </html>
        ";
    $server = "
        <html>
        <head>
        <meta http-equiv='content-type' content='text/html; charset=utf-8' />
        <style>
        .extension-btn {min-width: 120px; color: #fff; background-color: #0c64eb; border-color: transparent; display: inline-block; padding: 6px 12px; margin-bottom: 0; font-size: 13px; font-weight: 400; line-height: 18px; text-align: center; white-space: nowrap; vertical-align: middle; cursor: pointer; user-select: none; border: 1px solid transparent; border-radius: 4px; transition: .4s cubic-bezier(.175,.885,.32,1);}
        a {text-decoration: none;}
        </style>
        <title>error</title>
        </head>
        <body style='font-size: 13px;'>
        <h2 style='color:red;text-align:center'>错误的IP地址或MAC地址，或错误的域名访问</h2>
        <p style='text-align:left; margin: 0px 20%;'>软件授权的IP地址或MAC地址和当前系统的IP地址或MAC地址不一致，请使用最初授权的服务器。或你访问的域名与绑定的域名不一致。</p>
        <p style='text-align:center; margin: 13px 0px;'>
        <a href='https://www.zentao.net/extension-browse.html' target='_blank' class='btn btn-wide btn-primary extension-btn' style='margin-right: 20px;'>下载更新</a>
        <a href='{$extensionLink}' target='_top' class='btn btn-wide extension-btn' style='background-color: #fff; border-color: #d6dae3; color: #3c4353;'>卸载插件</a>
        </p>
        <br /> <br /> <br />
        <h2 style='color:red;text-align:center'>Wrong IP, MAC address, or domains!</h2>
        <p style='text-align:left; margin: 0px 20%;'>The IP, MAC address, or the domains of your server is not the same one in your license.</p>
        <p style='text-align:center; margin: 13px 0px;'>
        <a href='https://www.zentao.net/extension-browse.html' target='_blank' class='btn btn-wide btn-primary extension-btn' style='margin-right: 20px;'>Download Update</a>
        <a href='{$extensionLink}' target='_top' class='btn btn-wide extension-btn' style='background-color: #fff; border-color: #d6dae3; color: #3c4353;'>Delete Plugin</a>
        </p>
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
