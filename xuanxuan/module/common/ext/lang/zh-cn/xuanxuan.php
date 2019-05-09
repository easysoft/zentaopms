<?php
$lang->xuanxuan = new stdclass();
$lang->admin->menu->xuanxuan = array('link' => '客户端|admin|xuanxuan', 'subModule' => 'client,setting');
$lang->admin->menuOrder[6]   = 'xuanxuan';

$lang->admin->subMenu->xuanxuan = new stdclass();
$lang->admin->subMenu->xuanxuan->index   = array('link' => '首页|admin|xuanxuan');
$lang->admin->subMenu->xuanxuan->setting = array('link' => '参数|setting|xuanxuan');
$lang->admin->subMenu->xuanxuan->update  = array('link' => '更新|client|browse', 'subModule' => 'client');
$lang->admin->subMenuOrder->xuanxuan[0]  = 'index';
$lang->admin->subMenuOrder->xuanxuan[5]  = 'setting';
$lang->admin->subMenuOrder->xuanxuan[10] = 'update';

$lang->menugroup->client = 'admin';

$lang->confirmDelete = '您确定要执行删除操作吗？';
