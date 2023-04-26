<?php
$lang->admin->xuanxuan        = 'Chat Status';
$lang->admin->blockStatus     = 'Status';
$lang->admin->blockStatistics = 'Statistics';
$lang->admin->xuanxuanSetting = 'Settings';

$lang->admin->fileSize      = 'File Size';
$lang->admin->countUsers    = 'Online Users';
$lang->admin->setServer     = 'Server Settings';
$lang->admin->totalUsers    = 'Users';
$lang->admin->totalGroups   = 'Groups';
$lang->admin->totalMessages = 'Messages';
$lang->admin->xxdStartDate  = 'XXD Last Start';

$lang->admin->message = array();
$lang->admin->message['total'] = 'Total Messages';
$lang->admin->message['hour']  = 'Last Hour Messages';
$lang->admin->message['day']   = 'Last 24 Hours Messages';

$lang->admin->sizeType = array();
$lang->admin->sizeType['K'] = 1024;
$lang->admin->sizeType['M'] = 1024 * 1024;
$lang->admin->sizeType['G'] = 1024 * 1024 * 1024;

global $config;
if($config->vision != 'lite')
{
    $lang->admin->menuList->system['subMenu']['xuanxuan'] = array('link' => 'Desktop|admin|xuanxuan|', 'subModule' => 'client,setting,conference');
    $lang->admin->menuList->system['menuOrder']['20'] = 'xuanxuan';

    $lang->admin->menuList->system['tabMenu']['xuanxuan']['index']   = array('link' => 'Home|admin|xuanxuan|');
    $lang->admin->menuList->system['tabMenu']['xuanxuan']['setting'] = array('link' => 'Parameter|setting|xuanxuan|');
    if($config->edition != 'open')
    {
        $lang->admin->menuList->system['tabMenu']['xuanxuan']['conference'] = array('link' => 'Conference|conference|admin|');
        $lang->navGroup->conference = 'admin';
    }
    $lang->admin->menuList->system['tabMenu']['xuanxuan']['update'] = array('link' => 'Update|client|browse|', 'subModule' => 'client');
}
