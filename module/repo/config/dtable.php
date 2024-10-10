<?php
global $lang, $app;
$app->loadLang('sonarqube');
$app->loadLang('bug');

$config->repo->dtable = new stdclass();

$config->repo->dtable->fieldList['name']['name']  = 'name';
$config->repo->dtable->fieldList['name']['title'] = $lang->repo->name;
$config->repo->dtable->fieldList['name']['type']  = 'title';
$config->repo->dtable->fieldList['name']['width'] = '0.2';
$config->repo->dtable->fieldList['name']['hint']  = '{desc}';

$config->repo->dtable->fieldList['product']['name']     = 'productNames';
$config->repo->dtable->fieldList['product']['title']    = $lang->repo->product;
$config->repo->dtable->fieldList['product']['type']     = 'text';
$config->repo->dtable->fieldList['product']['sortType'] = false;
$config->repo->dtable->fieldList['product']['width']    = '136';
$config->repo->dtable->fieldList['product']['hint']     = true;

$config->repo->dtable->fieldList['scm']['name']     = 'SCM';
$config->repo->dtable->fieldList['scm']['title']    = $lang->repo->type;
$config->repo->dtable->fieldList['scm']['type']     = 'scm';
$config->repo->dtable->fieldList['scm']['sortType'] = true;
$config->repo->dtable->fieldList['scm']['map']      = $lang->repo->scmList;
$config->repo->dtable->fieldList['scm']['group']    = 1;

$config->repo->dtable->fieldList['path']['name']  = 'codePath';
$config->repo->dtable->fieldList['path']['title'] = $lang->repo->path;
$config->repo->dtable->fieldList['path']['type']  = 'text';
$config->repo->dtable->fieldList['path']['hint']  = true;
$config->repo->dtable->fieldList['path']['width'] = '260';
$config->repo->dtable->fieldList['path']['group'] = 1;

$config->repo->dtable->fieldList['lastSubmit']['name']       = 'lastSubmitTime';
$config->repo->dtable->fieldList['lastSubmit']['title']      = $lang->repo->lastSubmitTime;
$config->repo->dtable->fieldList['lastSubmit']['type']       = 'datetime';
$config->repo->dtable->fieldList['lastSubmit']['formatDate'] = 'MM-dd hh:mm';
$config->repo->dtable->fieldList['lastSubmit']['sortType']   = false;
$config->repo->dtable->fieldList['lastSubmit']['width']      = '100';

$config->repo->dtable->fieldList['job']['name']  = 'job';
$config->repo->dtable->fieldList['job']['hidden'] = true;

$config->repo->dtable->fieldList['actions']['name']  = 'actions';
$config->repo->dtable->fieldList['actions']['title'] = $lang->actions;
$config->repo->dtable->fieldList['actions']['type']  = 'actions';
$config->repo->dtable->fieldList['actions']['width'] = '132';
$config->repo->dtable->fieldList['actions']['menu']  = array('visit', 'execJob', 'reportView', 'edit', 'delete');

$config->repo->dtable->fieldList['actions']['list']['visit']['icon']   = 'menu-my';
$config->repo->dtable->fieldList['actions']['list']['visit']['hint']   = $lang->repo->visit;
$config->repo->dtable->fieldList['actions']['list']['visit']['target'] = '_blank';

$config->repo->dtable->fieldList['actions']['list']['edit']['icon'] = 'edit';
$config->repo->dtable->fieldList['actions']['list']['edit']['hint'] = $lang->repo->edit;

$config->repo->dtable->fieldList['actions']['list']['execJob']['icon']       = 'sonarqube';
$config->repo->dtable->fieldList['actions']['list']['execJob']['hint']       = $lang->sonarqube->execJob;
$config->repo->dtable->fieldList['actions']['list']['execJob']['url']        = array('module' => 'sonarqube', 'method' => 'execJob', 'params' => "jobID={job}");
$config->repo->dtable->fieldList['actions']['list']['execJob']['className']  = 'ajax-submit';

$config->repo->dtable->fieldList['actions']['list']['reportView']['icon']        = 'audit';
$config->repo->dtable->fieldList['actions']['list']['reportView']['hint']        = $lang->sonarqube->reportView;
$config->repo->dtable->fieldList['actions']['list']['reportView']['url']         = array('module' => 'sonarqube', 'method' => 'reportView', 'params' => "jobID={job}");
$config->repo->dtable->fieldList['actions']['list']['reportView']['data-toggle'] = 'modal';

$config->repo->dtable->fieldList['actions']['list']['delete']['icon']         = 'trash';
$config->repo->dtable->fieldList['actions']['list']['delete']['hint']         = $lang->repo->delete;
$config->repo->dtable->fieldList['actions']['list']['delete']['data-confirm'] = array('message' => $lang->repo->notice->delete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->repo->dtable->fieldList['actions']['list']['delete']['className']    = 'ajax-submit';
$config->repo->dtable->fieldList['actions']['list']['delete']['url']          = helper::createLink('repo', 'delete', 'repoID={id}');

$config->repo->repoDtable = new stdclass();

$config->repo->repoDtable->fieldList['name']['name']     = 'name';
$config->repo->repoDtable->fieldList['name']['title']    = $lang->repo->name;
$config->repo->repoDtable->fieldList['name']['minWidth'] = '160';
$config->repo->repoDtable->fieldList['name']['sortType'] = false;
$config->repo->repoDtable->fieldList['name']['type']     = 'shortTitle';
$config->repo->repoDtable->fieldList['name']['fixed']    = false;
$config->repo->repoDtable->fieldList['name']['hint']     = true;
$config->repo->repoDtable->fieldList['name']['checkbox'] = false;

$config->repo->repoDtable->fieldList['revision']['name']     = 'revision';
$config->repo->repoDtable->fieldList['revision']['title']    = $lang->repo->revisions;
$config->repo->repoDtable->fieldList['revision']['sortType'] = false;
$config->repo->repoDtable->fieldList['revision']['width']    = '90';
$config->repo->repoDtable->fieldList['revision']['hint']     = true;

$config->repo->repoDtable->fieldList['time']['name']     = 'date';
$config->repo->repoDtable->fieldList['time']['title']    = $lang->repo->time;
$config->repo->repoDtable->fieldList['time']['sortType'] = false;
$config->repo->repoDtable->fieldList['time']['type']     = 'date';
$config->repo->repoDtable->fieldList['time']['hint']     = true;

$config->repo->repoDtable->fieldList['committer']['name']     = 'account';
$config->repo->repoDtable->fieldList['committer']['title']    = $lang->repo->committer;
$config->repo->repoDtable->fieldList['committer']['sortType'] = false;
$config->repo->repoDtable->fieldList['committer']['width']    = '90';
$config->repo->repoDtable->fieldList['committer']['hint']     = true;

$config->repo->repoDtable->fieldList['comment']['name']     = 'originalComment';
$config->repo->repoDtable->fieldList['comment']['title']    = $lang->repo->comment;
$config->repo->repoDtable->fieldList['comment']['sortType'] = false;
$config->repo->repoDtable->fieldList['comment']['type']     = 'text';
$config->repo->repoDtable->fieldList['comment']['hint']     = true;

$config->repo->commentDtable = new stdclass();

$config->repo->commentDtable->fieldList['id']['title']    = '';
$config->repo->commentDtable->fieldList['id']['name']     = '';
$config->repo->commentDtable->fieldList['id']['type']     = 'checkID';
$config->repo->commentDtable->fieldList['id']['fixed']    = false;
$config->repo->commentDtable->fieldList['id']['sortType'] = false;
$config->repo->commentDtable->fieldList['id']['checkbox'] = true;
$config->repo->commentDtable->fieldList['id']['width']    = '25';

$config->repo->commentDtable->fieldList['revision']['name']     = 'revision';
$config->repo->commentDtable->fieldList['revision']['title']    = $lang->repo->revisions;
$config->repo->commentDtable->fieldList['revision']['sortType'] = false;
$config->repo->commentDtable->fieldList['revision']['type']     = 'title';
$config->repo->commentDtable->fieldList['revision']['fixed']    = false;
$config->repo->commentDtable->fieldList['revision']['width']    = '100';
$config->repo->commentDtable->fieldList['revision']['hint']     = true;

$config->repo->commentDtable->fieldList['commit']['name']     = 'commit';
$config->repo->commentDtable->fieldList['commit']['title']    = $lang->repo->commit;
$config->repo->commentDtable->fieldList['commit']['sortType'] = false;
$config->repo->commentDtable->fieldList['commit']['width']    = '50';
$config->repo->commentDtable->fieldList['commit']['hint']    = true;

$config->repo->commentDtable->fieldList['time']         = $config->repo->repoDtable->fieldList['time'];
$config->repo->commentDtable->fieldList['time']['name'] = 'time';
$config->repo->commentDtable->fieldList['time']['type'] = 'datetime';

$config->repo->commentDtable->fieldList['committer']         = $config->repo->repoDtable->fieldList['committer'];
$config->repo->commentDtable->fieldList['committer']['name'] = 'committer';

$config->repo->commentDtable->fieldList['comment']         = $config->repo->repoDtable->fieldList['comment'];
$config->repo->commentDtable->fieldList['comment']['name'] = 'originalComment';

$config->repo->logDtable = new stdclass();

$config->repo->logDtable->fieldList['id']['hidden'] = true;

$config->repo->logDtable->fieldList['revision']['type']         = 'revision';
$config->repo->logDtable->fieldList['revision']['width']        = '160';
$config->repo->logDtable->fieldList['revision']['checkbox']     = true;
$config->repo->logDtable->fieldList['revision']['nestedToggle'] = false;
$config->repo->logDtable->fieldList['revision']['data-app']     = $app->tab;

$config->repo->logDtable->fieldList['date']['name']     = 'time';
$config->repo->logDtable->fieldList['date']['type']     = 'datetime';
$config->repo->logDtable->fieldList['date']['sortType'] = false;
$config->repo->logDtable->fieldList['date']['width']    = '160';

$config->repo->logDtable->fieldList['committer']['name']  = 'committer';
$config->repo->logDtable->fieldList['committer']['width'] = '160';

$config->repo->logDtable->fieldList['relations']['name']  = 'relations';
$config->repo->logDtable->fieldList['relations']['type']  = 'html';
$config->repo->logDtable->fieldList['relations']['width'] = '450';
$config->repo->logDtable->fieldList['relations']['title'] =  $lang->repo->relations;

$config->repo->logDtable->fieldList['comment']['type']  = 'html';
$config->repo->logDtable->fieldList['comment']['width'] = '400';

$config->repo->blameDtable = new stdclass();

$config->repo->blameDtable->fieldList['revision']['type'] = 'revision';
$config->repo->blameDtable->fieldList['revision']['width'] = '120';

$config->repo->blameDtable->fieldList['commit']['type']     = 'number';
$config->repo->blameDtable->fieldList['commit']['sortType'] = false;
$config->repo->blameDtable->fieldList['commit']['width'] = '80';

$config->repo->blameDtable->fieldList['committer']['name'] = 'committer';
$config->repo->blameDtable->fieldList['committer']['width'] = '150';

$config->repo->blameDtable->fieldList['line']['type']     = 'number';
$config->repo->blameDtable->fieldList['line']['sortType'] = false;
$config->repo->blameDtable->fieldList['line']['width'] = '80';

$config->repo->blameDtable->fieldList['content']['title'] = $lang->repo->code;
$config->repo->blameDtable->fieldList['content']['type']  = 'html';
$config->repo->blameDtable->fieldList['content']['width'] = '700';

$app->loadLang('task');
$app->loadModuleConfig('task');

$config->repo->taskDtable = new stdclass();
$config->repo->taskDtable->fieldList = array();
$config->repo->taskDtable->fieldList['id']['name']     = 'id';
$config->repo->taskDtable->fieldList['id']['title']    = $lang->idAB;
$config->repo->taskDtable->fieldList['id']['type']     = 'checkID';
$config->repo->taskDtable->fieldList['id']['checkbox'] = true;

$config->repo->taskDtable->fieldList['pri']['title']    = $lang->priAB;
$config->repo->taskDtable->fieldList['pri']['type']     = 'pri';
$config->repo->taskDtable->fieldList['pri']['sortType'] = true;
$config->repo->taskDtable->fieldList['pri']['show']     = true;
$config->repo->taskDtable->fieldList['pri']['group']    = 1;
$config->repo->taskDtable->fieldList['pri']['fixed']    = 'left';

$config->repo->taskDtable->fieldList['name']['flex']         = 1;
$config->repo->taskDtable->fieldList['name']['nestedToggle'] = false;
$config->repo->taskDtable->fieldList['name']['sortType']     = true;
$config->repo->taskDtable->fieldList['name']['required']     = true;
$config->repo->taskDtable->fieldList['name']['fixed']        = 'left';
$config->repo->taskDtable->fieldList['name']['type']         = 'nestedTitle';
$config->repo->taskDtable->fieldList['name']['title']        = $lang->task->name;
$config->repo->taskDtable->fieldList['name']['data-toggle']  = 'modal';
$config->repo->taskDtable->fieldList['name']['data-size']    = 'lg';
$config->repo->taskDtable->fieldList['name']['link']         = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={id}', 'target' => '_blank');

$config->repo->taskDtable->fieldList['finishedBy']['title']    = $lang->task->finishedByAB;
$config->repo->taskDtable->fieldList['finishedBy']['type']     = 'user';
$config->repo->taskDtable->fieldList['finishedBy']['sortType'] = true;
$config->repo->taskDtable->fieldList['finishedBy']['show']     = true;
$config->repo->taskDtable->fieldList['finishedBy']['group']    = 4;

$config->repo->taskDtable->fieldList['assignedTo']['type']        = 'user';
$config->repo->taskDtable->fieldList['assignedTo']['sortType']    = true;
$config->repo->taskDtable->fieldList['assignedTo']['show']        = true;
$config->repo->taskDtable->fieldList['assignedTo']['group']       = 3;
$config->repo->taskDtable->fieldList['assignedTo']['currentUser'] = '';
$config->repo->taskDtable->fieldList['assignedTo']['title']       = $lang->task->assignedTo;
$config->repo->taskDtable->fieldList['assignedTo']['assignLink']  = array('module' => 'task', 'method' => 'assignTo', 'params' => 'executionID={execution}&taskID={id}');

$config->repo->taskDtable->fieldList['status']['title']     = $lang->statusAB;
$config->repo->taskDtable->fieldList['status']['type']      = 'status';
$config->repo->taskDtable->fieldList['status']['statusMap'] = $lang->task->statusList;
$config->repo->taskDtable->fieldList['status']['sortType']  = true;
$config->repo->taskDtable->fieldList['status']['show']      = true;
$config->repo->taskDtable->fieldList['status']['group']     = 1;

$config->repo->reviewDtable = new stdclass();

$config->repo->reviewDtable->fieldList['id']['title'] = $lang->idAB;
$config->repo->reviewDtable->fieldList['id']['type']  = 'id';

$config->repo->reviewDtable->fieldList['title']['type']     = 'title';
$config->repo->reviewDtable->fieldList['title']['data-app'] = $app->tab;
$config->repo->reviewDtable->fieldList['title']['link']     = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}&from=repo');

if($app->tab != 'devops')
{
    $config->repo->reviewDtable->fieldList['repo']['title'] = $lang->repo->common;
    $config->repo->reviewDtable->fieldList['repo']['name']  = 'repoName';
    $config->repo->reviewDtable->fieldList['repo']['width'] = '150';
}

$config->repo->reviewDtable->fieldList['fileLocation']['title'] = $lang->repo->codeLocation;
$config->repo->reviewDtable->fieldList['fileLocation']['name']  = 'entry';
$config->repo->reviewDtable->fieldList['fileLocation']['width'] = '300';

$config->repo->reviewDtable->fieldList['revisionA']['name']  = 'revisionA';
$config->repo->reviewDtable->fieldList['revisionA']['width'] = '100';
$config->repo->reviewDtable->fieldList['revisionA']['hint']  = true;
$config->repo->reviewDtable->fieldList['revisionA']['link']  = array('module' => 'repo', 'method' => 'revision', 'params' => 'repoID={repo}&objectID=0&revision={v2}');

$app->loadLang('bug');
$config->repo->reviewDtable->fieldList['type']['title'] = $lang->repo->type;
$config->repo->reviewDtable->fieldList['type']['name']  = 'type';
$config->repo->reviewDtable->fieldList['type']['map']   = $lang->bug->typeList;
$config->repo->reviewDtable->fieldList['type']['map']  += $lang->repo->typeList;

$config->repo->reviewDtable->fieldList['status']['map'] = $lang->bug->statusList;

$config->repo->reviewDtable->fieldList['openedBy']['type'] = 'user';

$config->repo->reviewDtable->fieldList['assignedTo']['type'] = 'user';

$config->repo->reviewDtable->fieldList['openedDate']['type'] = 'datetime';

$config->repo->dtable->tag = new stdclass();
$config->repo->dtable->tag->fieldList['name']['title'] = $lang->repo->tag;
$config->repo->dtable->tag->fieldList['name']['type']  = 'title';
$config->repo->dtable->tag->fieldList['name']['name']  = 'name';
$config->repo->dtable->tag->fieldList['name']['fixed'] = 0;
$config->repo->dtable->tag->fieldList['name']['width'] = 250;
$config->repo->dtable->tag->fieldList['name']['order'] = 10;
$config->repo->dtable->tag->fieldList['name']['group'] = 1;

$config->repo->dtable->tag->fieldList['createdDate']['title']      = $lang->repo->openedDate;
$config->repo->dtable->tag->fieldList['createdDate']['type']       = 'datetime';
$config->repo->dtable->tag->fieldList['createdDate']['formatDate'] = 'YYYY-MM-dd hh:mm';
$config->repo->dtable->tag->fieldList['createdDate']['order']      = 30;
$config->repo->dtable->tag->fieldList['createdDate']['group']      = 1;

$config->repo->dtable->tag->fieldList['commitID']['title']    = $lang->repo->sourceCommit;
$config->repo->dtable->tag->fieldList['commitID']['link']     = helper::createLink('repo', 'revision', 'repoID={repoID}&objectID=0&revision={commitID}');
$config->repo->dtable->tag->fieldList['commitID']['data-app'] = $app->tab;
$config->repo->dtable->tag->fieldList['commitID']['type']     = 'desc';
$config->repo->dtable->tag->fieldList['commitID']['flex']     = 0;
$config->repo->dtable->tag->fieldList['commitID']['sortType'] = true;
$config->repo->dtable->tag->fieldList['commitID']['order']    = 50;
$config->repo->dtable->tag->fieldList['commitID']['width']    = 100;
$config->repo->dtable->tag->fieldList['commitID']['group']    = 2;

$config->repo->dtable->tag->fieldList['committer']['title']    = $lang->repo->lastCommitter;
$config->repo->dtable->tag->fieldList['committer']['type']     = 'user';
$config->repo->dtable->tag->fieldList['committer']['sortType'] = true;
$config->repo->dtable->tag->fieldList['committer']['order']    = 60;
$config->repo->dtable->tag->fieldList['committer']['width']    = 100;
$config->repo->dtable->tag->fieldList['committer']['group']    = 2;

$config->repo->dtable->tag->fieldList['date']['title']      = $lang->repo->time;
$config->repo->dtable->tag->fieldList['date']['name']       = 'date';
$config->repo->dtable->tag->fieldList['date']['type']       = 'datetime';
$config->repo->dtable->tag->fieldList['date']['formatDate'] = 'YYYY-MM-dd hh:mm';
$config->repo->dtable->tag->fieldList['date']['order']      = 70;
$config->repo->dtable->tag->fieldList['date']['group']      = 2;

$config->repo->dtable->tag->fieldList['message']['title'] = $lang->repo->comment;
$config->repo->dtable->tag->fieldList['message']['type']  = 'text';
$config->repo->dtable->tag->fieldList['message']['order'] = 40;
$config->repo->dtable->tag->fieldList['message']['group'] = 1;

$config->repo->dtable->branch = new stdclass();
$config->repo->dtable->branch->fieldList['name']['title'] = $lang->repo->branch;
$config->repo->dtable->branch->fieldList['name']['type']  = 'title';
$config->repo->dtable->branch->fieldList['name']['name']  = 'name';

$config->repo->dtable->branch->fieldList['commitID']['title']    = $lang->repo->commit;
$config->repo->dtable->branch->fieldList['commitID']['type']     = 'desc';
$config->repo->dtable->branch->fieldList['commitID']['flex']     = 0;
$config->repo->dtable->branch->fieldList['commitID']['link']     = helper::createLink('repo', 'revision', 'repoID={repoID}&objectID=0&revision={commitID}');
$config->repo->dtable->branch->fieldList['commitID']['data-app'] = $app->tab;
$config->repo->dtable->branch->fieldList['commitID']['sortType'] = true;
$config->repo->dtable->branch->fieldList['commitID']['order']    = 30;
$config->repo->dtable->branch->fieldList['commitID']['width']    = 100;

$config->repo->dtable->branch->fieldList['committer']['title']    = $lang->repo->lastCommitter;
$config->repo->dtable->branch->fieldList['committer']['type']     = 'user';
$config->repo->dtable->branch->fieldList['committer']['sortType'] = true;
$config->repo->dtable->branch->fieldList['committer']['order']    = 40;
$config->repo->dtable->branch->fieldList['committer']['width']    = 100;

$config->repo->dtable->branch->fieldList['commitDate']['title']      = $lang->repo->time;
$config->repo->dtable->branch->fieldList['commitDate']['name']       = 'commitDate';
$config->repo->dtable->branch->fieldList['commitDate']['type']       = 'datetime';
$config->repo->dtable->branch->fieldList['commitDate']['formatDate'] = 'YYYY-MM-dd hh:mm';
$config->repo->dtable->branch->fieldList['commitDate']['order']      = 50;
