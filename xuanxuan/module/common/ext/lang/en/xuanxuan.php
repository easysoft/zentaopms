<?php
$lang->xuanxuan = new stdclass();
$lang->admin->menu->xuanxuan = array('link' => 'ZT Client|admin|xuanxuan', 'subModule' => 'client');

$lang->admin->subMenu->xuanxuan = new stdclass();
$lang->admin->subMenu->xuanxuan->setting = array('link' => 'Parameter|admin|xuanxuan');
$lang->admin->subMenu->xuanxuan->update  = array('link' => 'Update|client|browse', 'subModule' => 'client');
$lang->admin->subMenuOrder->xuanxuan[0] = 'setting';
$lang->admin->subMenuOrder->xuanxuan[5] = 'update';

$lang->menugroup->client = 'admin';

$lang->confirmDelete = 'Do you want to delete it?';
