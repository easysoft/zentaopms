<?php
$lang->xuanxuan = new stdclass();
$lang->admin->menu->xuanxuan = array('link' => '客户端|admin|xuanxuan', 'subModule' => 'client,setting,owt');
$lang->admin->menuOrder[6]   = 'xuanxuan';

$lang->admin->menu->xuanxuan['subMenu'] = new stdclass();
$lang->admin->menu->xuanxuan['subMenu']->index   = array('link' => '首页|admin|xuanxuan');
$lang->admin->menu->xuanxuan['subMenu']->setting = array('link' => '参数|setting|xuanxuan');
$lang->admin->menu->xuanxuan['subMenu']->owt     = array('link' => '音视频|owt|admin');
$lang->admin->menu->xuanxuan['subMenu']->update  = array('link' => '更新|client|browse', 'subModule' => 'client');

$lang->admin->menu->xuanxuan['menuOrder'][0]  = 'index';
$lang->admin->menu->xuanxuan['menuOrder'][5]  = 'setting';
$lang->admin->menu->xuanxuan['menuOrder'][10] = 'owt';
$lang->admin->menu->xuanxuan['menuOrder'][15] = 'update';

$lang->navGroup->im      = 'admin';
$lang->navGroup->setting = 'admin';
$lang->navGroup->owt     = 'admin';
$lang->navGroup->client  = 'admin';

$lang->confirmDelete = '您确定要执行删除操作吗？';
