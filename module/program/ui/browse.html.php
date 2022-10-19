<?php
common::sortFeatureMenu();

$toolbar = toolbar();
foreach($this->lang->program->featureBar['browse'] as $key => $label)
{
    $tab = tab($label)->link(inlink('browse', "status=$key&orderBy=$orderBy"))->active($key == $status);
    $toolbar->append($tab);
}

$menu = block('h');
$menu->toolbar   = $toolbar;
//$menu->actionbar = '<div>actionbar</div>';

$page = page('list');
$page->top->menu = $menu;

$page->x();
