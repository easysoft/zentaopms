<?php
$lang->admin->xuanxuan        = '聊天状态';
$lang->admin->blockStatus     = '状态监控';
$lang->admin->blockStatistics = '系统统计';
$lang->admin->xuanxuanSetting = '参数设置';

$lang->admin->fileSize      = '附件大小';
$lang->admin->countUsers    = '当前在线用户数';
$lang->admin->setServer     = '服务器设置';
$lang->admin->totalUsers    = '总用户数';
$lang->admin->totalGroups   = '讨论组数';
$lang->admin->totalMessages = '消息数量';
$lang->admin->xxdStartDate  = 'XXD上次启动时间';

$lang->admin->message = array();
$lang->admin->message['total'] = '总消息数';
$lang->admin->message['hour']  = '最近一小时消息数';
$lang->admin->message['day']   = '最近一天消息数';

$lang->admin->sizeType = array();
$lang->admin->sizeType['K'] = 1024;
$lang->admin->sizeType['M'] = 1024 * 1024;
$lang->admin->sizeType['G'] = 1024 * 1024 * 1024;

global $config;
if($config->vision != 'lite')
{
    $lang->admin->menuList->system['subMenu']['xuanxuan'] = array('link' => '聊天|admin|xuanxuan|', 'subModule' => 'client,setting,conference');
    $lang->admin->menuList->system['menuOrder']['20'] = 'xuanxuan';

    $lang->admin->menuList->system['tabMenu']['xuanxuan']['index']   = array('link' => '首页|admin|xuanxuan|');
    $lang->admin->menuList->system['tabMenu']['xuanxuan']['setting'] = array('link' => '参数|setting|xuanxuan|');
    if($config->edition != 'open')
    {
        $lang->admin->menuList->system['tabMenu']['xuanxuan']['conference'] = array('link' => '音视频|conference|admin|');
        $lang->navGroup->conference = 'admin';
    }
    $lang->admin->menuList->system['tabMenu']['xuanxuan']['update'] = array('link' => '更新|client|browse|', 'subModule' => 'client');
}
