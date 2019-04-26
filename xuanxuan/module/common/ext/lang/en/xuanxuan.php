<?php
$lang->xuanxuan = new stdclass();

$lang->admin->menu->sso['link'] = 'Integrate|admin|xuanxuan';
if(!isset($lang->admin->menu->sso['alias'])) $lang->admin->menu->sso['alias'] = '';
$lang->admin->menu->sso['alias'] = $lang->admin->menu->sso['alias'] . ',sso';
if(!isset($lang->admin->menu->sso['subModule'])) $lang->admin->menu->sso['subModule'] = '';
$lang->admin->menu->sso['subModule'] = $lang->admin->menu->sso['subModule'] . ',client';

$lang->admin->subMenu->sso->xuanxuan = array('link' => 'ZT Client|admin|xuanxuan', 'subModule' => 'client');
$lang->admin->subMenuOrder->sso[4] = 'xuanxuan';

$lang->client = new stdclass();
$lang->client->menu = $lang->admin->menu;

$lang->menugroup->client = 'admin';

$lang->confirmDelete = 'Do you want to delete it?';
