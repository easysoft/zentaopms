<?php
global $config;
$lang->navIcons['repo']         = "<i class='icon icon-code-block'></i>";
$lang->navIcons['compile']      = "<i class='icon icon-inherit-space'></i>";
$lang->navIcons['deploy']       = "<i class='icon icon-doclib'></i>";
$lang->navIcons['space']        = "<i class='icon icon-layout'></i>";
$lang->navIcons['dynamic']      = "<i class='icon icon-gantt-alt'></i>";
$lang->navIcons['artifactrepo'] = "<i class='icon icon-folder'></i>";
$lang->navIcons['env']          = "<i class='icon icon-lang'></i>";

/* Main Navigation. */
$lang->mainNav          = new stdclass();
$lang->mainNav->my      = "{$lang->navIcons['my']} {$lang->my->shortCommon}|my|index|";
$lang->mainNav->repo    = "{$lang->navIcons['repo']} {$lang->devops->repo}|repo|maintain|";
$lang->mainNav->compile = "{$lang->navIcons['compile']} {$lang->devops->compile}|job|browse|";
$lang->mainNav->deploy  = "{$lang->navIcons['deploy']} {$lang->deployment->common}|host|browse|";
$lang->mainNav->bi      = "{$lang->navIcons['bi']} {$lang->bi->common}|screen|browse|";
$lang->mainNav->env     = "{$lang->navIcons['env']} {$lang->devops->env}|env|browse|";
$lang->mainNav->space   = "{$lang->navIcons['space']} {$lang->devops->space}|devopsspace|view|";
$lang->mainNav->dynamic = "{$lang->navIcons['dynamic']} {$lang->devops->dynamic}|company|dynamic|";
$lang->mainNav->system  = "{$lang->navIcons['system']} {$lang->system->common}|my|team|";
$lang->mainNav->setting = "{$lang->navIcons['admin']} {$lang->admin->common}|repo|setrules|";

/* Menu order. */
$lang->mainNav->menuOrder     = array();
$lang->mainNav->menuOrder[5]  = 'my';
$lang->mainNav->menuOrder[10] = 'repo';
$lang->mainNav->menuOrder[15] = 'compile';
$lang->mainNav->menuOrder[35] = 'deploy';
$lang->mainNav->menuOrder[40] = 'bi';
$lang->mainNav->menuOrder[45] = 'env';
$lang->mainNav->menuOrder[50] = 'space';
$lang->mainNav->menuOrder[55] = 'dynamic';
$lang->mainNav->menuOrder[60] = 'system';
$lang->mainNav->menuOrder[65] = 'setting';
