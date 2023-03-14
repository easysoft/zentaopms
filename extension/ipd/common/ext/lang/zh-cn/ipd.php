<?php
$lang->demandpool = new stdclass();
$lang->demandpool->common = '需求池';

$lang->demand = new stdclass();
$lang->demand->common = '用户需求';

$lang->projectInit = new stdclass();
$lang->projectInit->common = '立项';

$lang->navIcons['demandpool'] = "<i class='icon icon-bars'></i>";

/* Main Navigation. */
$lang->mainNav              = new stdclass();
$lang->mainNav->my          = "{$lang->navIcons['my']} {$lang->my->shortCommon}|my|index|";
$lang->mainNav->demandpool  = "{$lang->navIcons['demandpool']} {$lang->demandpool->common}|demandpool|browse|";
$lang->mainNav->product     = "{$lang->navIcons['product']} {$lang->productCommon}|$productModule|$productMethod|";
$lang->mainNav->projectInit = "{$lang->navIcons['project']} {$lang->projectInit->common}|$productModule|$productMethod|";
$lang->mainNav->doc         = "{$lang->navIcons['doc']} {$lang->doc->common}|doc|index|";
$lang->mainNav->admin       = "{$lang->navIcons['admin']} {$lang->admin->common}|admin|index|";

if($config->edition != 'open')
{
    $lang->navIcons['feedback'] = "<i class='icon icon-feedback'></i>";

    $lang->mainNav->feedback = $lang->navIcons['feedback'] . '反馈|feedback|browse|browseType=unclosed';

    if($config->visions == ',lite,') unset($lang->mainNav->feedback);
}

$lang->mainNav->menuOrder[5]  = 'my';
$lang->mainNav->menuOrder[10] = 'demandpool';
$lang->mainNav->menuOrder[15] = 'product';
$lang->mainNav->menuOrder[20] = 'projectInit';
$lang->mainNav->menuOrder[25] = 'feedback';
$lang->mainNav->menuOrder[30] = 'doc';
$lang->mainNav->menuOrder[35] = 'admin';
