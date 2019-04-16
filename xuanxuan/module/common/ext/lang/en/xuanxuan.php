<?php
$lang->xuanxuan = new stdclass();

$lang->admin->menu->sso['link'] = 'Integrate|admin|xuanxuan';
if(!isset($lang->admin->menu->sso['alias'])) $lang->admin->menu->sso['alias'] = '';
$lang->admin->menu->sso['alias'] = $lang->admin->menu->sso['alias'] . ',sso';

$lang->admin->subMenu->sso->xuanxuan = 'ZT Client|admin|xuanxuan';
$lang->admin->subMenuOrder->sso[4] = 'xuanxuan';
