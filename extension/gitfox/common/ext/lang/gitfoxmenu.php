<?php
global $config;

$lang->navIcons['my']       = "<i class='icon icon-home'></i>";
$lang->navIcons['system']   = "<i class='icon icon-has-authority-pack'></i>";
$lang->navIcons['space']    = "<i class='icon icon-grid-2x2'></i>";
$lang->navIcons['repo']     = "<i class='icon icon-code'></i>";
$lang->navIcons['ppm']      = "<i class='icon icon-review'></i>";
$lang->navIcons['codescan'] = "<i class='icon icon-test'></i>";
$lang->navIcons['issue']    = "<i class='icon icon-issue'></i>";
$lang->navIcons['pipeline'] = "<i class='icon icon-waterfall'></i>";
$lang->navIcons['artifact'] = "<i class='icon icon-package'></i>";
$lang->navIcons['admin']    = "<i class='icon icon-has-authority'></i>";

/* Main Navigation. */
$lang->mainNav           = new stdclass();
$lang->mainNav->my       = "{$lang->navIcons['my']} {$lang->my->shortCommon}|my|index|";
$lang->mainNav->system   = "{$lang->navIcons['system']} {$lang->devops->system}|product|browse|";
$lang->mainNav->space    = "{$lang->navIcons['space']} {$lang->space->common}|space|browse|";
$lang->mainNav->repo     = "{$lang->navIcons['repo']} {$lang->repo->common}|repo|maintain|";
$lang->mainNav->ppm      = "{$lang->navIcons['ppm']} {$lang->devops->ppm}|ppm|browse|";
$lang->mainNav->codescan = "{$lang->navIcons['codescan']} {$lang->devops->codescan}|codescan|task|";
$lang->mainNav->issue    = "{$lang->navIcons['issue']} {$lang->devops->issue}|repo|review|";
$lang->mainNav->pipeline = "{$lang->navIcons['pipeline']} {$lang->pipeline->common}|pipeline|browse|";
$lang->mainNav->artifact = "{$lang->navIcons['artifact']} {$lang->artifact->common}|artifact|browse|";
$lang->mainNav->admin    = "{$lang->navIcons['admin']} {$lang->admin->common}|admin|index|";

/* Menu order. */
$lang->mainNav->menuOrder     = array();
$lang->mainNav->menuOrder[5]  = 'my';
$lang->mainNav->menuOrder[10] = 'system';
$lang->mainNav->menuOrder[15] = 'space';
$lang->mainNav->menuOrder[20] = 'repo';
$lang->mainNav->menuOrder[25] = 'ppm';
$lang->mainNav->menuOrder[30] = 'codescan';
$lang->mainNav->menuOrder[35] = 'issue';
$lang->mainNav->menuOrder[40] = 'pipeline';
$lang->mainNav->menuOrder[45] = 'artifact';
$lang->mainNav->menuOrder[50] = 'admin';

/* 分组：地盘 | 应用、空间、代码库、评审、扫描、问题 | 流水线、制品库 | 后台 */
$lang->dividerMenu = ',system,pipeline,admin,';

$lang->dashboard = isset($lang->dashboard->common) ? $lang->dashboard->common : $lang->dashboard;

/* 地盘菜单。 */
$lang->my->menu = new stdclass();
$lang->my->menu->index   = array('link' => "$lang->dashboard|my|index");
$lang->my->menu->work    = array('link' => "{$lang->my->work}|my|work|mode=task");
$lang->my->menu->audit   = array('link' => "{$lang->review->common}|my|audit|browseType=all&param=&orderBy=time_desc", 'subModule' => 'review');
$lang->my->menu->dynamic = array('link' => "$lang->dynamic|my|dynamic|");
$lang->my->menu->ssh     = array('link' => "{$lang->ssh->common}|my|ssh|");

/* 地盘菜单顺序。 */
$lang->my->menuOrder     = array();
$lang->my->menuOrder[5]  = 'index';
$lang->my->menuOrder[10] = 'work';
$lang->my->menuOrder[15] = 'audit';
$lang->my->menuOrder[20] = 'dynamic';
$lang->my->menuOrder[25] = 'ssh';

$lang->system->menu = new stdclass();
$lang->system->menu->browse = array('link' => "{$lang->devops->system}|product|browse|");

/* 空间列表页。 */
$lang->space->homeMenu = new stdclass();
$lang->space->homeMenu->browse = array('link' => "{$lang->space->common}|space|browse", 'alias' => 'create,edit');

/* 进入空间后的菜单。 */
$lang->space->menu = new stdclass();
$lang->space->menu->repos    = array('link' => "{$lang->devops->repo}|repo|maintain|spaceID=%s", 'alias' => 'create,edit,import,createrepo,binduser', 'exclude' => 'repo-setrules');
$lang->space->menu->pipeline = array('link' => "{$lang->pipeline->common}|pipeline|browse|spaceID=%s&repoID=0&type=space", 'subModule' => 'pipeline');
$lang->space->menu->artifact = array('link' => "{$lang->artifact->common}|artifact|browse|spaceID=%s&repoID=0&type=space", 'subModule' => 'artifact');
$lang->space->menu->apps     = array('link' => "{$lang->devops->system}|repo|browsesystem|spaceID=%s", 'subModule' => 'system');
$lang->space->menu->settings = array('link' => "{$lang->devops->spaceSetting}|space|view|spaceID=%s", 'exclude' => 'repo-maintain,repo-browsesystem');

/* 进入空间后的菜单顺序。 */
$lang->space->menuOrder     = array();
$lang->space->menuOrder[5]  = 'repos';
$lang->space->menuOrder[10] = 'pipeline';
$lang->space->menuOrder[15] = 'artifact';
$lang->space->menuOrder[20] = 'apps';
$lang->space->menuOrder[25] = 'settings';

/* 代码库列表页。 */
$lang->repo->homeMenu = new stdclass();
$lang->repo->homeMenu->repos = array('link' => "{$lang->devops->repo}|repo|maintain", 'alias' => 'create,edit,import,createrepo,binduser', 'exclude' => 'repo-setrules');

/* 进入代码库后的菜单。 */
$lang->repo->menu = new stdclass();
$lang->repo->menu->code      = array('link' => "{$lang->repocode->common}|repo|browse|repoID=%s", 'subModule' => 'repo', 'exclude' => 'repo-review,repo-browsetag,repo-browsebranch,repo-log,repo-diff,repo-revision,repo-setrules');
$lang->repo->menu->commit    = array('link' => "{$lang->repo->commit}|repo|log|repoID=%s", 'alias' => 'diff');
$lang->repo->menu->branch    = array('link' => "{$lang->repo->branch}|repo|browsebranch|repoID=%s");
$lang->repo->menu->tag       = array('link' => "{$lang->repo->tag}|repo|browsetag|repoID=%s");
$lang->repo->menu->ppm       = array('link' => "{$lang->devops->ppm}|ppm|browse|repoID=%s");
$lang->repo->menu->pipeline  = array('link' => "{$lang->pipeline->common}|pipeline|browse|spaceID=0&repoID=%s&type=repo", 'subModule' => 'pipeline');
$lang->repo->menu->artifact  = array('link' => "{$lang->artifact->common}|artifact|browse|spaceID=0&repoID=%s&type=repo", 'subModule' => 'artifact');
$lang->repo->menu->codescan  = array('link' => "{$lang->devops->codescan}|codescan|task|repoID=%s", 'subModule' => 'codescan');
$lang->repo->menu->issue     = array('link' => "{$lang->devops->issue}|repo|review|repoID=%s", 'subModule' => 'bug');
$lang->repo->menu->settings  = array('link' => "{$lang->repoSettings->common}|repobranchtype|browse|repoID=%s", 'subModule' => 'reporeviewflow,repobranchtype', 'alias' => 'browsewebhooks,browserule,createwebhook,editwebhook,logwebhook,setarchive');

/* 代码库设置子菜单。 */
$lang->repo->menu->settings['subMenu'] = new stdclass();
$lang->repo->menu->settings['subMenu']->branchType = array('link' => "{$lang->devops->branchType}|repobranchtype|browse|repoID=%s", 'alias' => 'create,edit,delete,import');
$lang->repo->menu->settings['subMenu']->reviewFlow = array('link' => "{$lang->devops->reviewFlow}|reporeviewflow|browse|repoID=%s", 'alias' => 'create,edit');
$lang->repo->menu->settings['subMenu']->provider   = array('link' => "{$lang->provider->common}|provider|browse|", 'alias' => 'create,edit');
$lang->repo->menu->settings['subMenu']->runner     = array('link' => "{$lang->runner->common}|runner|browse|");

/* 进入代码库后的菜单顺序。 */
$lang->repo->menuOrder     = array();
$lang->repo->menuOrder[5]  = 'code';
$lang->repo->menuOrder[10] = 'commit';
$lang->repo->menuOrder[15] = 'branch';
$lang->repo->menuOrder[20] = 'tag';
$lang->repo->menuOrder[25] = 'ppm';
$lang->repo->menuOrder[30] = 'pipeline';
$lang->repo->menuOrder[35] = 'artifact';
$lang->repo->menuOrder[40] = 'codescan';
$lang->repo->menuOrder[45] = 'issue';
$lang->repo->menuOrder[50] = 'settings';

$lang->repo->menu->settings['menuOrder'][5]  = 'branchType';
$lang->repo->menu->settings['menuOrder'][10] = 'reviewFlow';
$lang->repo->menu->settings['menuOrder'][15] = 'provider';
$lang->repo->menu->settings['menuOrder'][20] = 'runner';

if($config->vision == 'gitfox')
{
    $lang->navGroup->my        = 'my';
    $lang->navGroup->todo      = 'my';
    $lang->navGroup->product   = 'system';
    $lang->navGroup->space     = 'space';
    $lang->navGroup->repo      = 'repo';
    $lang->navGroup->ci        = 'repo';
    $lang->navGroup->job       = 'repo';
    $lang->navGroup->ppm       = 'ppm';
    $lang->navGroup->codescan  = 'codescan';
    $lang->navGroup->pipeline  = 'pipeline';
    $lang->navGroup->artifact  = 'artifact';
    $lang->navGroup->company   = 'my';
    $lang->navGroup->admin     = 'admin';
}
