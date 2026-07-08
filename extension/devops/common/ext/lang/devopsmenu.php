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
$lang->mainNav->space   = "{$lang->navIcons['space']} {$lang->devops->space}|space|view|";
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

if($config->edition != 'open')
{
    $lang->mainNav->artifactrepo = "{$lang->navIcons['artifactrepo']} {$lang->devops->artifactrepo}|artifactrepo|browse|";
    $lang->mainNav->deploy       = "{$lang->navIcons['deploy']} {$lang->deployment->common}|deploy|browse|";

    $lang->mainNav->menuOrder[25] = 'artifactrepo';

    $lang->deploy = new stdclass();
    $lang->deploy->menu = new stdclass();
    $lang->deploy->menu->deploy = array('link' => "{$lang->devops->deploy}|deploy|browse", 'subModule' => 'deploy');
    $lang->deploy->menu->host   = array('link' => "{$lang->devops->host}|host|browse", 'alias' => 'treemap,create,edit,tree,view,tree-browse', 'subModule' => 'tree,serverroom');

    $lang->deploy->menuOrder[10] = 'deploy';
    $lang->deploy->menuOrder[25] = 'host';
}

$lang->dashboard = isset($lang->dashboard->common) ? $lang->dashboard->common : $lang->dashboard;

/* My menu. */
$lang->my->menu = new stdclass();
$lang->my->menu->index      = array('link' => "$lang->dashboard|my|index");
$lang->my->menu->contribute = array('link' => "{$lang->devops->contribute}|my|index");
$lang->my->menu->todo       = array('link' => "{$lang->devops->todo}|mr|browse|", 'subModule' => 'pullreq', 'alias' => 'pullreq');
$lang->my->menu->setting    = array('link' => "{$lang->devops->setting}|user|ssh|");

/* My menu order. */
$lang->my->menuOrder     = array();
$lang->my->menuOrder[5]  = 'index';
$lang->my->menuOrder[10] = 'todo';
$lang->my->menuOrder[15] = 'contribute';
$lang->my->menuOrder[20] = 'setting';

$lang->my->dividerMenu = ',todo,';

$lang->repo->menu = new stdclass();

$lang->repo->homeMenu = new stdclass();
$lang->repo->homeMenu->repos = array('link' => "{$lang->devops->repo}|repo|maintain", 'alias' => 'create,edit,import,createrepo', 'exclude' => 'repo-setrules');

$lang->repo->menu->code      = array('link' => "{$lang->repocode->common}|repo|browse|repoID=%s", 'subModule' => 'repo', 'exclude' => 'repo-review,repo-browsetag,repo-browsebranch,repo-log,repo-diff,repo-revision,repo-setrules');
$lang->repo->menu->commit    = array('link' => "{$lang->repo->commit}|repo|log|repoID=%s", 'alias' => 'diff');
$lang->repo->menu->branch    = array('link' => "{$lang->repo->branch}|repo|browsebranch|repoID=%s");
$lang->repo->menu->tag       = array('link' => "{$lang->repo->tag}|repo|browsetag|repoID=%s");
$lang->repo->menu->review    = array('link' => "{$lang->devops->review}|mr|browse|repoID=%s");
$lang->repo->menu->compile   = array('link' => "{$lang->devops->compile}|job|browse|repoID=%s", 'subModule' => 'compile,job');
$lang->repo->menu->dynamic   = array('link' => "{$lang->devops->dynamic}|company|browse|");
$lang->repo->menu->dashboard = array('link' => "{$lang->dashboard}|repo|dashboard|");

$lang->repo->menu->compile['subMenu'] = new stdclass();
$lang->repo->menu->compile['subMenu']->compile    = array('link' => "{$lang->devops->compile}|job|browse|repoID=%s");
$lang->repo->menu->compile['subMenu']->compileLog = array('link' => "{$lang->devops->compileLog}|compile|browse|jobID=%s");

$lang->repo->menu->compile['menuOrder'][5]  = 'compile';
$lang->repo->menu->compile['menuOrder'][10] = 'compileLog';

$lang->repo->menuOrder[15] = 'code';
$lang->repo->menuOrder[20] = 'commit';
$lang->repo->menuOrder[25] = 'branch';
$lang->repo->menuOrder[35] = 'tag';
$lang->repo->menuOrder[40] = 'review';
$lang->repo->menuOrder[45] = 'compile';
$lang->repo->menuOrder[60] = 'dynamic';

if($config->edition != 'open')
{
    $lang->repo->menu->issue = array('link' => "{$lang->devops->issue}|repo|review|repoID=%s", 'subModule' => 'bug', 'alias' => 'create');

    $lang->repo->menuOrder[55] = 'issue';
}

$lang->space = new stdclass();
$lang->space->menu = new stdclass();
$lang->space->menu->baseinfo = array('link' => "{$lang->devops->spaceInfo}|space|view");
$lang->space->menu->team     = array('link' => "{$lang->devops->spaceTeam}|space|team");
$lang->space->menu->spaces   = array('link' => "{$lang->devops->spaces}|space|browse");

$lang->space->menuOrder[5]  = 'baseinfo';
$lang->space->menuOrder[10] = 'team';
$lang->space->menuOrder[15] = 'spaces';

$lang->compile->menu = new stdclass();
$lang->compile->menu->browse = array('link' => "{$lang->devops->compile}|job|browse");
$lang->compile->menu->log    = array('link' => "{$lang->devops->compileLog}|compile|browse");

$lang->compile->menuOrder[5]  = 'browse';
$lang->compile->menuOrder[10] = 'log';

$lang->bi->menu = new stdclass();
$lang->bi->menu->screen = array('link' => "{$lang->screen->common}|screen|browse");
$lang->bi->menu->metric = array('link' => "{$lang->metric->common}|metric|preview");

/* Admin menu. */
$lang->setting = new stdclass();
$lang->setting->menu = new stdclass();
$lang->setting->menu->repo         = array('link' => "{$lang->devops->repo}|repo|setrules");
$lang->setting->menu->artifactrepo = array('link' => "{$lang->devops->artifactrepo}|artifactrepo|browse");
$lang->setting->menu->ai           = array('link' => "{$lang->ai->common}|zai|setting");

/* Admin menu order. */
$lang->setting->menuOrder[5]  = 'repo';
$lang->setting->menuOrder[10] = 'artifactrepo';
$lang->setting->menuOrder[20] = 'ai';

$lang->setting->menu->repo['subMenu'] = new stdclass();
$lang->setting->menu->repo['subMenu']->repo   = array('link' => "{$lang->devops->repo}|repo|setrules");
$lang->setting->menu->repo['subMenu']->branch = array('link' => "{$lang->devops->branch}|repo|branchrule");

$lang->setting->menu->repo['menuOrder'][5]  = 'repo';
$lang->setting->menu->repo['menuOrder'][10] = 'branch';

if($config->inQuickon)
{
    $lang->setting->menu->platform = array('link' => "{$lang->devops->platform}|system|dblist");
    $lang->setting->menuOrder[15]  = 'platform';

    $lang->setting->menu->platform['subMenu'] = new stdclass();
    $lang->setting->menu->platform['subMenu']->db    = array('link' => "{$lang->devops->db}|system|dblist");
    $lang->setting->menu->platform['subMenu']->oss   = array('link' => "{$lang->devops->oss}|system|ossview");
    $lang->setting->menu->platform['subMenu']->space = array('link' => "{$lang->devops->service}|space|browse");
    $lang->setting->menu->platform['subMenu']->store = array('link' => "{$lang->devops->store}|store|browse");

    $lang->setting->menu->platform['menuOrder'][25] = 'db';
    $lang->setting->menu->platform['menuOrder'][30] = 'oss';
    $lang->setting->menu->platform['menuOrder'][35] = 'space';
    $lang->setting->menu->platform['menuOrder'][40] = 'store';
}

if($config->vision == 'devops')
{
    $lang->navGroup->repo             = 'repo';
    $lang->navGroup->repo_setRules    = 'setting';
    $lang->navGroup->job              = 'repo';
    $lang->navGroup->jenkins          = 'repo';
    $lang->navGroup->mr               = 'repo';
    $lang->navGroup->gitlab           = 'repo';
    $lang->navGroup->gogs             = 'repo';
    $lang->navGroup->gitea            = 'repo';
    $lang->navGroup->sonarqube        = 'repo';
    $lang->navGroup->sonarqubeproject = 'repo';
    $lang->navGroup->compile          = 'repo';
    $lang->navGroup->ci               = 'repo';
    $lang->navGroup->svn              = 'repo';
    $lang->navGroup->git              = 'repo';
    $lang->navGroup->app              = 'repo';
    $lang->navGroup->pipeline         = 'repo';
    $lang->navGroup->devopssetting    = 'repo';
    $lang->navGroup->space            = 'repo';
    $lang->navGroup->store            = 'repo';
    $lang->navGroup->instance         = 'repo';
    $lang->navGroup->deploy           = 'repo';
    $lang->navGroup->host             = 'repo';
    $lang->navGroup->artifactrepo     = 'artifactrepo';
    $lang->navGroup->company          = 'dynamic';
    $lang->navGroup->zai              = 'setting';
}
