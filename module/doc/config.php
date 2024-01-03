<?php
global $lang, $app;

$config->doc = new stdclass();
$config->doc->createlib = new stdclass();
$config->doc->editlib   = new stdclass();
$config->doc->create    = new stdclass();
$config->doc->edit      = new stdclass();
$config->doc->showfiles = new stdclass();

$config->doc->createlib->requiredFields = 'name';
$config->doc->editlib->requiredFields   = 'name';
$config->doc->create->requiredFields    = 'lib,title';
$config->doc->edit->requiredFields      = 'title';

$config->doc->customObjectLibs  = 'files,customFiles';
$config->doc->notArticleType    = '';
$config->doc->officeTypes       = 'word,ppt,excel,attachment';
$config->doc->textTypes         = 'html,markdown,text';
$config->doc->docTypes          = 'text,word,ppt,excel,url,article,attachment';
$config->doc->saveDraftInterval = '60';

$config->doc->custom = new stdclass();
$config->doc->custom->objectLibs = $config->doc->customObjectLibs;
$config->doc->custom->showLibs   = 'zero,children';

$config->doc->editor = new stdclass();
$config->doc->editor->create     = array('id' => 'content', 'tools' => 'docTools');
$config->doc->editor->edit       = array('id' => 'content', 'tools' => 'docTools');
$config->doc->editor->view       = array('id' => 'comment,lastComment', 'tools' => 'simple');
$config->doc->editor->objectlibs = array('id' => 'comment,lastComment', 'tools' => 'simple');

$config->doc->markdown = new stdclass();
$config->doc->markdown->create = array('id' => 'contentMarkdown', 'tools' => 'withchange');

$config->doc->iconList['html']     = 'rich-text';
$config->doc->iconList['markdown'] = 'markdown';
$config->doc->iconList['url']      = 'text-link';
$config->doc->iconList['text']     = 'wiki-file';
$config->doc->iconList['template'] = 'wiki-file';
$config->doc->iconList['word']     = 'word';
$config->doc->iconList['ppt']      = 'ppt';
$config->doc->iconList['excel']    = 'excel';

$config->doc->objectIconList['product']   = 'icon-product';
$config->doc->objectIconList['project']   = 'icon-project';
$config->doc->objectIconList['execution'] = 'icon-run';
$config->doc->objectIconList['mine']      = 'icon-contacts';
$config->doc->objectIconList['custom']    = 'icon-groups';

$config->doc->spaceMethod['mine']      = 'myspace';
$config->doc->spaceMethod['view']      = 'myspace';
$config->doc->spaceMethod['collect']   = 'myspace';
$config->doc->spaceMethod['createdby'] = 'myspace';
$config->doc->spaceMethod['editedby']  = 'myspace';
$config->doc->spaceMethod['product']   = 'productspace';
$config->doc->spaceMethod['project']   = 'projectspace';
$config->doc->spaceMethod['execution'] = 'projectspace';
$config->doc->spaceMethod['custom']    = 'teamspace';

$config->doc->search['module']               = 'doc';
$config->doc->search['fields']['title']      = $lang->doc->title;
$config->doc->search['fields']['id']         = $lang->doc->id;
$config->doc->search['fields']['product']    = $lang->doc->product;
if($app->rawMethod == 'contribute') $config->doc->search['fields']['project'] = $lang->doc->project;
$config->doc->search['fields']['execution']  = $lang->doc->execution;
$config->doc->search['fields']['lib']        = $lang->doc->lib;
$config->doc->search['fields']['status']     = $lang->doc->status;
$config->doc->search['fields']['module']     = $lang->doc->module;
$config->doc->search['fields']['addedBy']    = $lang->doc->addedByAB;
$config->doc->search['fields']['addedDate']  = $lang->doc->addedDate;
$config->doc->search['fields']['editedBy']   = $lang->doc->editedBy;
$config->doc->search['fields']['editedDate'] = $lang->doc->editedDate;
$config->doc->search['fields']['keywords']   = $lang->doc->keywords;
$config->doc->search['fields']['version']    = $lang->doc->version;

$config->doc->search['params']['title']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->doc->search['params']['product']    = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->doc->search['params']['lib']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->doc->search['params']['status']     = array('operator' => '=',       'control' => 'select', 'values' => $lang->doc->statusList);
$config->doc->search['params']['module']     = array('operator' => 'belong',  'control' => 'select', 'values' => '');
if($app->rawMethod == 'contribute') $config->doc->search['params']['project'] = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->doc->search['params']['execution']  = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->doc->search['params']['addedBy']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->doc->search['params']['addedDate']  = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->doc->search['params']['editedBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->doc->search['params']['editedDate'] = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->doc->search['params']['keywords']   = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->doc->search['params']['version']    = array('operator' => '=',       'control' => 'input',  'values' => '');

$config->doc->actionList['edit']['icon']     = 'edit';
$config->doc->actionList['edit']['hint']     = $lang->edit;
$config->doc->actionList['edit']['text']     = $lang->edit;
$config->doc->actionList['edit']['url']      = helper::createLink('doc', 'edit', 'docID={id}');
$config->doc->actionList['edit']['data-app'] = $app->tab;

$config->doc->actionList['delete']['icon']         = 'trash';
$config->doc->actionList['delete']['hint']         = $lang->delete;
$config->doc->actionList['delete']['text']         = $lang->delete;
$config->doc->actionList['delete']['url']          = helper::createLink('doc', 'delete', 'docID={id}');
$config->doc->actionList['delete']['className']    = 'ajax-submit';
$config->doc->actionList['delete']['data-confirm'] = $lang->doc->confirmDelete;

$config->doc->showfiles->actionList['download']['icon']   = 'import';
$config->doc->showfiles->actionList['download']['hint']   = $lang->doc->download;
$config->doc->showfiles->actionList['download']['text']   = $lang->doc->download;
$config->doc->showfiles->actionList['download']['url']    = array('module' => 'file', 'method' => 'download', 'params' => 'fileID={id}');
$config->doc->showfiles->actionList['download']['target'] = '_blank';
