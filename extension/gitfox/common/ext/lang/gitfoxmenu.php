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

$lang->navIconNames['my']       = 'home';
$lang->navIconNames['system']   = 'has-authority-pack';
$lang->navIconNames['space']    = 'grid-2x2';
$lang->navIconNames['repo']     = 'code';
$lang->navIconNames['ppm']      = 'review';
$lang->navIconNames['codescan'] = 'test';
$lang->navIconNames['issue']    = 'issue';
$lang->navIconNames['pipeline'] = 'waterfall';
$lang->navIconNames['artifact'] = 'package';
$lang->navIconNames['admin']    = 'has-authority';

/* Main Navigation. */
$lang->mainNav           = new stdclass();
$lang->mainNav->my       = "{$lang->navIcons['my']} {$lang->my->shortCommon}|my|index|";
$lang->mainNav->system   = "{$lang->navIcons['system']} {$lang->devops->system}|product|browse|";
$lang->mainNav->space    = "{$lang->navIcons['space']} {$lang->space->common}|space|browse|";
$lang->mainNav->repo     = "{$lang->navIcons['repo']} {$lang->devops->repo}|repo|maintain|";
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

$lang->system->common = $lang->devops->system;

$lang->system->menu = new stdclass();
$lang->system->menu->browse = array('link' => "{$lang->devops->appList}|product|browse|", 'alias' => 'create,edit');

$lang->system->menuOrder    = array();
$lang->system->menuOrder[5] = 'browse';

/* 空间列表页。 */
$lang->space->homeMenu = new stdclass();
$lang->space->homeMenu->browse = array('link' => "{$lang->devops->spaceList}|space|browse", 'alias' => 'create,edit');

/* 进入空间后的菜单。 */
$lang->space->menu = new stdclass();
$lang->space->menu->repos    = array('link' => "{$lang->devops->repo}|repo|maintain|inSpace=1&space=%s", 'alias' => 'create,edit,import,createrepo,binduser', 'exclude' => 'repo-setrules');
$lang->space->menu->pipeline = array('link' => "{$lang->pipeline->common}|pipeline|browse|spaceID=%s&repoID=0&type=space", 'subModule' => 'pipeline');
$lang->space->menu->artifact = array('link' => "{$lang->artifact->common}|artifact|browse|spaceID=%s&repoID=0&type=space", 'subModule' => 'artifact');

/* 空间下的应用列表由 space 模块承载（复用应用模块的列表配置），一级导航稳定为空间。*/
$lang->space->menu->apps     = array('link' => "{$lang->devops->system}|space|apps|spaceID=%s", 'alias' => 'edit');
$lang->space->menu->settings = array('link' => "{$lang->devops->spaceSetting}|space|view|spaceID=%s", 'subModule' =>'space', 'exclude' => 'repo-maintain,system-view,repo-create,repo-createrepo,repo-import,repo-edit,space-apps,space-edit');

$lang->space->menu->settings['subMenu'] = new stdclass();
$lang->space->menu->settings['subMenu']->overview = array('link' => "{$lang->devops->overview}|space|view|spaceID=%s");
$lang->space->menu->settings['subMenu']->member   = array('link' => "{$lang->devops->member}|space|members|spaceID=%s", 'alias' => 'managemembers');
$lang->space->menu->settings['subMenu']->group    = array('link' => "{$lang->devops->group}|space|group|spaceID=%s", 'alias' => 'managepriv');

/* 进入空间后的菜单顺序。 */
$lang->space->menuOrder     = array();
$lang->space->menuOrder[5]  = 'repos';
$lang->space->menuOrder[10] = 'pipeline';
$lang->space->menuOrder[15] = 'artifact';
$lang->space->menuOrder[20] = 'apps';
$lang->space->menuOrder[25] = 'settings';

/* 代码库列表页。 */
$lang->repo->homeMenu = new stdclass();
$lang->repo->homeMenu->repos = array('link' => "{$lang->devops->repoList}|repo|maintain", 'alias' => 'create,edit,import,createrepo,binduser', 'exclude' => 'repo-setrules');

/* 进入代码库后的菜单。 */
$lang->repo->menu = new stdclass();
$lang->repo->menu->code     = array('link' => "{$lang->repocode->common}|repo|browse|repoID=%s", 'alias' => 'view', 'subModule' => 'my', 'exclude' => 'repo-review,repo-browsetag,repo-browsebranch,repo-log,repo-diff,repo-revision,repo-setrules,repo-setbranchrule,repobranchType-browse,repo-browsewebhooks,repo-createwebhook,repo-editwebhook,repo-logwebhook');
$lang->repo->menu->commit   = array('link' => "{$lang->repo->commit}|repo|log|repoID=%s", 'alias' => 'diff');
$lang->repo->menu->branch   = array('link' => "{$lang->repo->branch}|repo|browsebranch|repoID=%s");
$lang->repo->menu->tag      = array('link' => "{$lang->repo->tag}|repo|browsetag|repoID=%s");
$lang->repo->menu->ppm      = array('link' => "{$lang->devops->ppm}|ppm|browse|repoID=%s");
$lang->repo->menu->pipeline = array('link' => "{$lang->pipeline->common}|pipeline|browse|spaceID=0&repoID=%s&type=repo", 'subModule' => 'pipeline');
$lang->repo->menu->artifact = array('link' => "{$lang->artifact->common}|artifact|browse|spaceID=0&repoID=%s&type=repo", 'subModule' => 'artifact');
$lang->repo->menu->codescan = array('link' => "{$lang->devops->codescan}|codescan|task|repoID=%s", 'alias' => 'planview,createplan,editplan', 'subModule' => 'codescan', 'exclude' => 'codescan-issue');
$lang->repo->menu->issue    = array('link' => "{$lang->devops->issue}|repo|review|repoID=%s", 'subModule' => 'bug,codescan', 'exclude' => 'codescan-task,codescan-plan,codescan-solution,codescan-ruleset,codescan-createplan,codescan-editplan');
$lang->repo->menu->settings = array('link' => "{$lang->repoSettings->common}|repobranchtype|browse|repoID=%s", 'subModule' => 'reporeviewflow,repobranchtype', 'alias' => 'browsewebhooks,browserule,createwebhook,editwebhook,logwebhook,setarchive');

$lang->repo->menu->codescan['subMenu'] = new stdclass();
$lang->repo->menu->codescan['subMenu']->scanTask = array('link' => "{$lang->devops->scanTask}|codescan|task|repoID=%s", 'alias' => 'taskview,tasklog,issue,issueview', 'exclude' => 'codescan-plan,codescan-planview');
$lang->repo->menu->codescan['subMenu']->scanPlan = array('link' => "{$lang->devops->scanPlan}|codescan|plan|repoID=%s", 'alias' => 'planview,createplan,editplan,trigger,tips');

$lang->repo->menu->codescan['menuOrder'][5]  = 'scanTask';
$lang->repo->menu->codescan['menuOrder'][10] = 'scanPlan';

$lang->repo->menu->issue['subMenu'] = new stdclass();
$lang->repo->menu->issue['subMenu']->review   = array('link' => "{$lang->devops->reviewIssue}|repo|review|repoID=%s", 'subModule' => 'bug');
$lang->repo->menu->issue['subMenu']->codescan = array('link' => "{$lang->devops->scanIssue}|codescan|issue|repoID=%s", 'alias' => 'issueview', 'subModule' => 'bug', 'exclude' => 'bug-view');

$lang->repo->menu->issue['menuOrder'][10] = 'review';
$lang->repo->menu->issue['menuOrder'][15] = 'codescan';
/* 代码库设置子菜单。 */
$lang->repo->menu->settings['subMenu'] = new stdclass();
$lang->repo->menu->settings['subMenu']->branchType = array('link' => "{$lang->devops->branchType}|repobranchtype|browse|repoID=%s", 'alias' => 'create,edit,delete,import');
$lang->repo->menu->settings['subMenu']->reviewFlow = array('link' => "{$lang->devops->reviewFlow}|reporeviewflow|browse|repoID=%s", 'alias' => 'create,edit');
$lang->repo->menu->settings['subMenu']->webhooks   = array('link' => "Webhooks|repo|browsewebhooks|repoID=%s", 'alias' => 'createwebhook,editwebhook,logwebhook');

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

/* 评审、问题、扫描、流水线与制品库菜单。 */
$lang->ppm->menu = new stdclass();
$lang->ppm->menu->browse = array('link' => "{$lang->devops->ppmList}|ppm|browse|");

$lang->ppm->menuOrder    = array();
$lang->ppm->menuOrder[5] = 'browse';

$lang->issue = new stdclass();
$lang->issue->common = $lang->devops->issue;
$lang->issue->menu = new stdclass();
$lang->issue->menu->review   = array('link' => "{$lang->devops->reviewIssue}|repo|review|repoID=%s", 'subModule' => 'bug');
$lang->issue->menu->codescan = array('link' => "{$lang->devops->scanIssue}|codescan|issue|repoID=%s", 'alias' => 'issueview', 'subModule' => 'bug', 'exclude' => 'bug-view');

$lang->issue->menuOrder     = array();
$lang->issue->menuOrder[5]  = 'review';
$lang->issue->menuOrder[10] = 'codescan';

$lang->codescan = new stdclass();
$lang->codescan->common = $lang->devops->codescan;
$lang->codescan->menu = new stdclass();
$lang->codescan->menu->scanTask     = array('link' => "{$lang->devops->scanTask}|codescan|task|", 'alias' => 'taskview,tasklog,issue');
$lang->codescan->menu->scanPlan     = array('link' => "{$lang->devops->scanPlan}|codescan|plan|", 'alias' => 'planview,trigger,createplan,editplan,tips');
$lang->codescan->menu->scanSolution = array('link' => "{$lang->devops->scanSolution}|codescan|solution|", 'alias' => 'solutionview');
$lang->codescan->menu->ruleset      = array('link' => "{$lang->ruleset->common}|codescan|ruleset|", 'alias' => 'rulesetview');
$lang->codescan->menu->scanRule     = array('link' => "{$lang->devops->scanRule}|codescan|browse|", 'alias' => 'view');

$lang->codescan->menuOrder    = array();
$lang->codescan->menuOrder[5]  = 'scanTask';
$lang->codescan->menuOrder[10] = 'scanPlan';
$lang->codescan->menuOrder[15] = 'scanSolution';
$lang->codescan->menuOrder[20] = 'ruleset';
$lang->codescan->menuOrder[25] = 'scanRule';

$lang->pipeline->menu = new stdclass();
$lang->pipeline->menu->browse = array('link' => "{$lang->devops->pipeline}|pipeline|browse|spaceID=0&repoID=0&type=space", 'alias' => 'view');

$lang->pipeline->menuOrder    = array();
$lang->pipeline->menuOrder[5] = 'browse';

$lang->artifact->menu = new stdclass();
$lang->artifact->menu->browse = array('link' => "{$lang->devops->artifact}|artifact|browse|spaceID=0&repoID=0&type=space", 'alias' => 'view');

$lang->artifact->menuOrder    = array();
$lang->artifact->menuOrder[5] = 'browse';

if($config->vision == 'gitfox')
{
    $lang->navGroup->my             = 'my';
    $lang->navGroup->todo           = 'my';
    $lang->navGroup->product        = 'system';
    $lang->navGroup->space          = 'space';
    $lang->navGroup->repo           = 'repo';
    $lang->navGroup->ci             = 'repo';
    $lang->navGroup->job            = 'repo';
    $lang->navGroup->ppm            = 'ppm';
    $lang->navGroup->codescan       = 'codescan';
    $lang->navGroup->pipeline       = 'pipeline';
    $lang->navGroup->artifact       = 'artifact';
    $lang->navGroup->company        = 'my';
    $lang->navGroup->admin          = 'admin';
    $lang->navGroup->provider       = 'admin';
    $lang->navGroup->runner         = 'admin';
    $lang->navGroup->reporeviewflow = 'repo';
    $lang->navGroup->repobranchtype = 'repo';
    $lang->navGroup->repobranchrule = 'repo';
}
