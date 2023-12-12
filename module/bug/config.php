<?php
$config->bug = new stdClass();
$config->bug->batchCreate  = 10;
$config->bug->longlife     = 7;
$config->bug->removeFields = 'objectTypeList,productList,executionList,gitlabID,gitlabProjectID';

$config->bug->create  = new stdclass();
$config->bug->edit    = new stdclass();
$config->bug->resolve = new stdclass();
$config->bug->create->requiredFields  = 'title,openedBuild';
$config->bug->edit->requiredFields    = 'title,openedBuild';
$config->bug->resolve->requiredFields = 'resolution';

$config->bug->actions = new stdclass();
$config->bug->actions->view = array();
$config->bug->actions->view['mainActions']   = array('confirm', 'assignTo', 'resolve', 'close', 'activate', 'toStory', 'toTask', 'createCase');
$config->bug->actions->view['suffixActions'] = array('edit', 'copy', 'delete');

$config->bug->browseTypeList = array('all', 'bymodule', 'assigntome', 'openedbyme', 'resolvedbyme', 'assigntonull', 'unconfirmed', 'unresolved', 'unclosed', 'toclosed', 'longlifebugs', 'postponedbugs', 'overduebugs', 'assignedbyme', 'review', 'needconfirm', 'bysearch');

$config->bug->list = new stdclass();
$config->bug->list->allFields = 'id, module, execution, story, task,
    title, keywords, severity, pri, type, os, browser, hardware,
    found, steps, status, deadline, activatedCount, confirmed, mailto,
    openedBy, openedDate, openedBuild,
    assignedTo, assignedDate,
    resolvedBy, resolution, resolvedBuild, resolvedDate,
    closedBy, closedDate,
    duplicateBug, relatedBug,
    case,
    lastEditedBy,
    lastEditedDate';

$config->bug->list->defaultFields           = 'id,title,severity,pri,openedBy,assignedTo,resolvedBy,resolution';
$config->bug->list->customCreateFields      = 'execution,noticefeedbackBy,story,task,pri,severity,os,browser,deadline,mailto,keywords';
$config->bug->list->customBatchEditFields   = 'type,severity,pri,productplan,assignedTo,deadline,resolvedBy,resolution,os,browser,keywords';
$config->bug->list->customBatchCreateFields = 'project,execution,steps,type,pri,deadline,severity,os,browser,keywords';

$config->bug->custom = new stdclass();
$config->bug->custom->createFields      = $config->bug->list->customCreateFields;
$config->bug->custom->batchCreateFields = 'project,execution,deadline,steps,type,pri,severity,os,browser,%s';
$config->bug->custom->batchEditFields   = 'type,severity,pri,assignedTo,deadline,status,resolvedBy,resolution';

$config->bug->exportFields = 'id, product, branch, module, project, execution, story, task,
    title, keywords, severity, pri, type, os, browser,
    steps, status, deadline, activatedCount, confirmed, mailto,
    openedBy, openedDate, openedBuild,
    assignedTo, assignedDate,
    resolvedBy, resolution, resolvedBuild, resolvedDate,
    closedBy, closedDate,
    duplicateBug, relatedBug,
    case,
    lastEditedBy,
    lastEditedDate, files ,feedbackBy, notifyEmail';

$config->bug->excludeCheckFields = ',severities,oses,browsers,lanes,regions,executions,projects,branches,';

$config->bug->editor = new stdclass();
$config->bug->editor->create   = array('id' => 'steps', 'tools' => 'bugTools');
$config->bug->editor->edit     = array('id' => 'steps,comment', 'tools' => 'bugTools');
$config->bug->editor->view     = array('id' => 'comment,lastComment', 'tools' => 'bugTools');
$config->bug->editor->confirm  = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->assignto = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->resolve  = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->close    = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->activate = array('id' => 'comment', 'tools' => 'bugTools');

$config->bug->discardedTypes = array('interface', 'designchange', 'newfeature', 'trackthings');

$config->bug->colorList = new stdclass();
$config->bug->colorList->pri[0]      = '#c0c0c0';
$config->bug->colorList->pri[1]      = '#d50000';
$config->bug->colorList->pri[2]      = '#ff9800';
$config->bug->colorList->pri[3]      = '#2098ee';
$config->bug->colorList->pri[4]      = '#009688';
$config->bug->colorList->pri[5]      = '#919090';
$config->bug->colorList->pri[6]      = '#B6B4B4';
$config->bug->colorList->pri[7]      = '#BDBEBD';
$config->bug->colorList->severity[1] = '#c62828';
$config->bug->colorList->severity[2] = '#ff8f00';
$config->bug->colorList->severity[3] = '#fdd835';
$config->bug->colorList->severity[4] = '#cddc39';
$config->bug->colorList->severity[5] = '#8bc34a';
$config->bug->colorList->severity[6] = '#B6B4B4';
$config->bug->colorList->severity[7] = '#BDBEBD';

global $lang;
$config->bug->actionList = array();
$config->bug->actionList['confirm']['icon']        = 'ok';
$config->bug->actionList['confirm']['text']        = $lang->bug->abbr->confirmed;
$config->bug->actionList['confirm']['hint']        = $lang->bug->abbr->confirmed;
$config->bug->actionList['confirm']['url']         = array('module' => 'bug', 'method' => 'confirm', 'params' => 'bugID={id}');
$config->bug->actionList['confirm']['data-toggle'] = 'modal';

$config->bug->actionList['assignTo']['icon']        = 'hand-right';
$config->bug->actionList['assignTo']['text']        = $lang->bug->assignTo;
$config->bug->actionList['assignTo']['hint']        = $lang->bug->assignTo;
$config->bug->actionList['assignTo']['url']         = array('module' => 'bug', 'method' => 'assignTo', 'params' => 'bugID={id}');
$config->bug->actionList['assignTo']['data-toggle'] = 'modal';

$config->bug->actionList['resolve']['icon']        = 'checked';
$config->bug->actionList['resolve']['text']        = $lang->bug->resolve;
$config->bug->actionList['resolve']['hint']        = $lang->bug->resolve;
$config->bug->actionList['resolve']['url']         = array('module' => 'bug', 'method' => 'resolve', 'params' => 'bugID={id}');
$config->bug->actionList['resolve']['data-toggle'] = 'modal';

$config->bug->actionList['close']['icon']        = 'off';
$config->bug->actionList['close']['text']        = $lang->bug->close;
$config->bug->actionList['close']['hint']        = $lang->bug->close;
$config->bug->actionList['close']['url']         = array('module' => 'bug', 'method' => 'close', 'params' => 'bugID={id}');
$config->bug->actionList['close']['data-toggle'] = 'modal';

$config->bug->actionList['activate']['icon']        = 'magic';
$config->bug->actionList['activate']['text']        = $lang->bug->activate;
$config->bug->actionList['activate']['hint']        = $lang->bug->activate;
$config->bug->actionList['activate']['url']         = array('module' => 'bug', 'method' => 'activate', 'params' => 'bugID={id}');
$config->bug->actionList['activate']['data-toggle'] = 'modal';

$config->bug->actionList['toStory']['icon']         = 'lightbulb';
$config->bug->actionList['toStory']['id']           = 'toStory';
$config->bug->actionList['toStory']['text']         = $lang->bug->toStory;
$config->bug->actionList['toStory']['hint']         = $lang->bug->toStory;
$config->bug->actionList['toStory']['data-tab']     = 'product';
$config->bug->actionList['toStory']['data-confirm'] = array('html' => "<strong><i class='icon icon-exclamation-sign text-warning text-lg mr-2'></i>{$lang->bug->notice->confirmToStory}</strong>");
$config->bug->actionList['toStory']['data-url']     = array('module' => 'story', 'method' => 'create', 'params' => 'product={product}&branch={branch}&module=0&story=0&execution=0&bugID={id}');

$config->bug->actionList['toTask']['icon']        = 'check';
$config->bug->actionList['toTask']['text']        = $lang->bug->toTask;
$config->bug->actionList['toTask']['hint']        = $lang->bug->toTask;
$config->bug->actionList['toTask']['data-target'] = '#toTask';
$config->bug->actionList['toTask']['data-toggle'] = 'modal';
$config->bug->actionList['toTask']['data-size']   = 'sm';

$config->bug->actionList['createCase']['icon'] = 'sitemap';
$config->bug->actionList['createCase']['text'] = $lang->bug->createCase;
$config->bug->actionList['createCase']['hint'] = $lang->bug->createCase;
$config->bug->actionList['createCase']['url']  = array('module' => 'testcase', 'method' => 'create', 'params' => 'productID={product}&branch={branch}&moduleID=0&from=bug&bugID={id}');

global $app;
$config->bug->actionList['edit']['icon']     = 'edit';
$config->bug->actionList['edit']['text']     = $lang->bug->edit;
$config->bug->actionList['edit']['hint']     = $lang->bug->edit;
$config->bug->actionList['edit']['url']      = array('module' => 'bug', 'method' => 'edit', 'params' => 'bugID={id}');
$config->bug->actionList['edit']['data-app'] = $app->tab;

$config->bug->actionList['copy']['icon']     = 'copy';
$config->bug->actionList['copy']['text']     = $lang->bug->copy;
$config->bug->actionList['copy']['hint']     = $lang->bug->copy;
$config->bug->actionList['copy']['url']      = array('module' => 'bug', 'method' => 'create', 'params' => 'productID={product}&branch={branch}&extra=bugID={id},projectID={project},executionID={execution}');
$config->bug->actionList['copy']['data-app'] = $app->tab;

$config->bug->actionList['delete']['icon']         = 'trash';
$config->bug->actionList['delete']['text']         = $lang->bug->delete;
$config->bug->actionList['delete']['hint']         = $lang->bug->delete;
$config->bug->actionList['delete']['url']          = array('module' => 'bug', 'method' => 'delete', 'params' => 'bugID={id}');
$config->bug->actionList['delete']['className']    = 'ajax-submit';
$config->bug->actionList['delete']['data-confirm'] = $lang->bug->notice->confirmDelete;
