<?php
$lang->xuanxuan = new stdclass();
$lang->admin->menu->xuanxuan = array('link' => '客户端|admin|xuanxuan', 'subModule' => 'client,setting');
$lang->admin->menuOrder[6]   = 'xuanxuan';

$lang->admin->menu->xuanxuan['subMenu'] = new stdclass();
$lang->admin->menu->xuanxuan['subMenu']->index   = array('link' => '首页|admin|xuanxuan');
$lang->admin->menu->xuanxuan['subMenu']->setting = array('link' => '参数|setting|xuanxuan');
$lang->admin->menu->xuanxuan['subMenu']->update  = array('link' => '更新|client|browse', 'subModule' => 'client');

$lang->admin->menu->xuanxuan['menuOrder'][0]  = 'index';
$lang->admin->menu->xuanxuan['menuOrder'][5]  = 'setting';
$lang->admin->menu->xuanxuan['menuOrder'][10] = 'update';

$lang->navGroup->im      = 'admin';
$lang->navGroup->setting = 'admin';
$lang->navGroup->client  = 'admin';

$lang->confirmDelete = '您确定要执行删除操作吗？';
