<?php
$lang->demandpool = new stdclass();
$lang->demandpool->common = 'RM Hub';

$lang->demand = new stdclass();
$lang->demand->common = 'Demand';

$lang->projectInit = new stdclass();
$lang->projectInit->common = 'Initiating';

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

    $lang->mainNav->feedback = $lang->navIcons['feedback'] . 'Feedback|feedback|browse|browseType=unclosed';

    if($config->visions == ',lite,') unset($lang->mainNav->feedback);
}

$lang->mainNav->menuOrder[5]  = 'my';
$lang->mainNav->menuOrder[10] = 'demandpool';
$lang->mainNav->menuOrder[15] = 'product';
$lang->mainNav->menuOrder[20] = 'projectInit';
$lang->mainNav->menuOrder[25] = 'feedback';
$lang->mainNav->menuOrder[30] = 'doc';
$lang->mainNav->menuOrder[35] = 'admin';

$lang->navGroup->demandpool = 'demandpool';
$lang->navGroup->demand     = 'demandpool';

$lang->demandpool->menu = new stdclass();
$lang->demandpool->menu->browse  = array('link' => "{$lang->demand->common}|demand|browse|poolID=%s", 'alias' => 'create,batchcreate,edit,managetree,view,review,tostory,showimport');
//$lang->demandpool->menu->review  = array('link' => "Review|demand|review|poolID=%s");
$lang->demandpool->menu->kanban  = array('link' => "Kanban|demand|kanban|poolID=%s");
$lang->demandpool->menu->track   = array('link' => "Track|demand|track|demandID=%s");
$lang->demandpool->menu->insight = array('link' => "Insight|demand|insight|demandID=%s");
$lang->demandpool->menu->view    = array('link' => "View|demandpool|view|poolID=%s");

$lang->demandpool->menuOrder[5]  = 'browse';
$lang->demandpool->menuOrder[10] = 'review';
$lang->demandpool->menuOrder[15] = 'kanban';
$lang->demandpool->menuOrder[20] = 'track';
$lang->demandpool->menuOrder[25] = 'insight';
$lang->demandpool->menuOrder[30] = 'view';
