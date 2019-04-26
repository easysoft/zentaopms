<?php
$lang->xuanxuan = new stdclass();

$lang->admin->menu->sso['link'] = '集成|admin|xuanxuan';
if(!isset($lang->admin->menu->sso['alias'])) $lang->admin->menu->sso['alias'] = '';
$lang->admin->menu->sso['alias'] = $lang->admin->menu->sso['alias'] . ',sso';
if(!isset($lang->admin->menu->sso['subModule'])) $lang->admin->menu->sso['subModule'] = '';
$lang->admin->menu->sso['subModule'] = $lang->admin->menu->sso['subModule'] . ',client';

$lang->admin->subMenu->sso->xuanxuan = array('link' => '客户端集成|admin|xuanxuan', 'subModule' => 'client');
$lang->admin->subMenuOrder->sso[4] = 'xuanxuan';

$lang->menugroup->client = 'admin';

$lang->confirmDelete = '您确定要执行删除操作吗？';
