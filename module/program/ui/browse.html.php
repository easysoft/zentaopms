<?php
common::sortFeatureMenu();

$toolbar = toolbar();
foreach($this->lang->program->featureBar['browse'] as $key => $label)
{
    $tab = tab($label)->link(inlink('browse', "status=$key&orderBy=$orderBy"))->active($key == $status);
    $toolbar->append($tab);
}

if(common::hasPriv('project', 'batchEdit') and $programType != 'bygrid' and $hasProject === true)
{
    $toolbar->append(html::checkbox('editProject', array('1' => $lang->project->edit), '', $this->cookie->editProject ? 'checked=checked' : ''));
}
$toolbar->append('<a class="btn btn-link querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i> ' . $lang->user->search . '</a>');

$actionbar = actionbar();
if(common::hasPriv('project', 'create'))
{
    $button = button('<i class="icon icon-plus"></i> ' . $this->lang->project->create)->link($this->createLink('project', 'createGuide', "programID=0&from=PGM"))->misc('class="btn btn-secondary" data-toggle="modal" data-target="#guideDialog"');
    $actionbar->append($button);
}
if(isset($lang->pageActions))
{
    $actionbar->append($lang->pageActions);
}

$menu = block('h');
$menu->toolbar   = $toolbar;
$menu->actionbar = $actionbar;

$page = page('list');
$page->top->menu = $menu;

$page->x();
