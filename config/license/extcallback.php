<?php
function ioncube_event_handler($err_code, $params)
{
    global $app;
    $extensionLink = helper::createLink('extension', 'browse');
    $homePageLink  = helper::createLink('my', 'index');
    $pluginNotice  = '';
    $deleteBtnZh   = '';
    $deleteBtnEn   = '';

    /* Display plugin information and permission check */
    if(!empty($params['license_file']) && preg_match('/([a-zA-Z]+)(\d+\.\d+)/', basename($params['license_file']), $matches))
    {
        $extensionInfo = !empty($matches[1]) ? $app->dao->select('*')->from(TABLE_EXTENSION)->where('code')->like( "%$matches[1]%")->fetch() : [];
        $pluginName    = !empty($extensionInfo->name) ? $extensionInfo->name : '';
        $pluginNotice  = !empty($pluginName) ? "<h3 style='margin: 30px 30px 0px;'><span class='icon icon-exclamation-sign warning-pale rounded-full icon-2x' style='margin-right:10px;'></span>“{$pluginName}”插件暂无授权</h3>" : '';

        $isPlugin = commonModel::hasPriv('extension', 'uninstall');
        if($isPlugin)
        {
            $deleteBtnZh = "<a href='{$extensionLink}' target='_top' class='btn btn-wide btn-default'>卸载插件</a>";
            $deleteBtnEn = "<a href='{$extensionLink}' target='_top' class='btn item btn-wide btn-default'>Delete Plugin</a>";
        }
    }

    $email   = 'co@zentao.net';
    $mobile  = '4006-8899-23';
    $expired = "
        <html>
        <head>
        <meta http-equiv='content-type' content='text/html; charset=utf-8' />
        <style>
        </style>
        <link rel='stylesheet' href='/js/zui3/zui.zentao.css'>
        <title>error</title>
        </head>
        <body style='font-size: 14px;'>
        {$pluginNotice}
        <h2 style='color:red;text-align:center'>没有授权此版本</h2>
        <div style='text-align:left; margin: 0px 20%;'>
            <p>您当前授权版本不支持此插件，请联系我们购买插件的授权。</p>
            <p>Email：{$email}</p>
            <p>电话：{$mobile}</p>
            <p>网站：<a href='https://www.zentao.net' target='_blank'>www.zentao.net</a></p>
        </div>
        <div style='text-align:center; margin: 13px 0px;'>
            <a href='https://www.zentao.net/extension-browse.html' target='_blank' class='btn btn-wide primary' style='margin-right: 20px;'>下载更新</a>
            {$deleteBtnZh}
            <a href='{$homePageLink}' target='_blank' class='btn btn-wide primary' style='margin-left: 20px;'>我的地盘</a>
        </div>
        <br /> <br /> <br />
        <h2 style='color:red;text-align:center'>This version is not licensed</h2>
        <p style='text-align:left; margin: 0px 20%;'>This license version is not enable this extension. Please contact us to buy thie right licenses.</p>
        <div style='text-align:left; margin: 0px 20%;'>
            <p>Email：{$email}</p>
            <p>Web：<a href='https://www.zentao.pm' target='_blank'>www.zentao.pm</a></p>
        </div>
         <div style='text-align:center; margin: 13px 0px;'>
            <a href='https://www.zentao.net/extension-browse.html' target='_blank' class='btn btn-wide primary' style='margin-right: 20px;'>Download Update</a>
            {$deleteBtnEn}
            <a href='{$homePageLink}' target='_blank' class='btn btn-wide primary' style='margin-left: 20px;'>Dashboard</a>
        </div>
        </body>
        </html>
        ";
    $server = "
        <html>
        <head>
        <meta http-equiv='content-type' content='text/html; charset=utf-8' />
        <link rel='stylesheet' href='/js/zui3/zui.zentao.css'>
        <title>error</title>
        </head>
        <body style='font-size: 13px;'>
        <h2 style='color:red;text-align:center'>错误的IP地址或MAC地址，或错误的域名访问</h2>
        <p style='text-align:left; margin: 0px 20%;'>软件授权的IP地址或MAC地址和当前系统的IP地址或MAC地址不一致，请使用最初授权的服务器。或你访问的域名与绑定的域名不一致。</p>
        <p style='text-align:center; margin: 13px 0px;'>
        <a href='https://www.zentao.net/extension-browse.html' target='_blank' class='btn btn-wide btn-primary' style='margin-right: 20px;'>下载更新</a>
        {$deleteBtnZh}
        </p>
        <br /> <br /> <br />
        <h2 style='color:red;text-align:center'>Wrong IP, MAC address, or domains!</h2>
        <p style='text-align:left; margin: 0px 20%;'>The IP, MAC address, or the domains of your server is not the same one in your license.</p>
        <p style='text-align:center; margin: 13px 0px;'>
        <a href='https://www.zentao.net/extension-browse.html' target='_blank' class='btn btn-wide primary' style='margin-right: 20px;'>Download Update</a>
        {$deleteBtnEn}
        <a href='{$homePageLink}' target='_blank' class='btn btn-wide primary' style='margin-left: 20px;'>Home Page</a>
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
