<?php
$lang->xuanxuan = new stdclass();
$lang->admin->menu->xuanxuan = array('link' => 'Desktop|admin|xuanxuan', 'subModule' => 'client,setting');
$lang->admin->menuOrder[6]   = 'xuanxuan';

$lang->admin->subMenu->xuanxuan = new stdclass();
$lang->admin->subMenu->xuanxuan->index   = array('link' => 'Home|admin|xuanxuan');
$lang->admin->subMenu->xuanxuan->setting = array('link' => 'Parameter|setting|xuanxuan');
$lang->admin->subMenu->xuanxuan->update  = array('link' => 'Update|client|browse', 'subModule' => 'client');
$lang->admin->subMenuOrder->xuanxuan[0]  = 'index';
$lang->admin->subMenuOrder->xuanxuan[5]  = 'setting';
$lang->admin->subMenuOrder->xuanxuan[10] = 'update';

$lang->menugroup->client = 'admin';

$lang->confirmDelete = 'Do you want to delete it?';
