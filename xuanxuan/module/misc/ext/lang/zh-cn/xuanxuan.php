<?php
$lang->misc->client = new stdclass();
$lang->misc->client->version     = '客户端版本';
$lang->misc->client->os          = '操作系统';
$lang->misc->client->download    = '下载';
$lang->misc->client->downloading = '正在获取安装包:';
$lang->misc->client->downloaded  = '成功获取安装包';
$lang->misc->client->setting     = '正在设置配置信息';
$lang->misc->client->setted      = '成功设置配置信息';

$lang->misc->client->osList['win64']   = 'Windows 64位';
$lang->misc->client->osList['win32']   = 'Windows 32位';
$lang->misc->client->osList['linux64'] = 'Linux 64位';
$lang->misc->client->osList['linux32'] = 'Linux 32位';
$lang->misc->client->osList['mac64']   = 'Mac版';

$lang->misc->client->errorInfo = new stdclass();
$lang->misc->client->errorInfo->downloadError  = '获取安装包失败';
$lang->misc->client->errorInfo->configError    = '配置用户信息失败';
$lang->misc->client->errorInfo->manualOpt      = '请从 %s 手动获取安装包。';
$lang->misc->client->errorInfo->dirNotExist    = '客户端下载存储路径 <span class="code text-red">%s</span> 不存在，请创建该目录。';
$lang->misc->client->errorInfo->dirNotWritable = '客户端下载存储路径 <span class="code text-red">%s</span> 不可写 <br />linux下面请执行命令：<span class="code text-red">sudo chmod 777 %s</span>来修正';
