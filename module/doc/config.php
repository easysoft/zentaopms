<?php
global $lang, $app;

$config->doc = new stdclass();
$config->doc->createlib = new stdclass();
$config->doc->editlib   = new stdclass();
$config->doc->create    = new stdclass();
$config->doc->createTemplate    = new stdclass();
$config->doc->edit      = new stdclass();
$config->doc->showfiles = new stdclass();

$config->doc->createlib->requiredFields = 'name';
$config->doc->editlib->requiredFields   = 'name';
$config->doc->create->requiredFields    = 'lib,title';
$config->doc->createTemplate->requiredFields    = 'lib,title';
$config->doc->edit->requiredFields      = 'lib,title';

$config->doc->customObjectLibs  = 'files,customFiles';
$config->doc->notArticleType    = 'chapter';
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

$config->doc->iconList['html']       = 'rich-text';
$config->doc->iconList['markdown']   = 'markdown';
$config->doc->iconList['url']        = 'text-link';
$config->doc->iconList['text']       = 'wiki-file';
$config->doc->iconList['template']   = 'wiki-file';
$config->doc->iconList['word']       = 'word';
$config->doc->iconList['ppt']        = 'ppt';
$config->doc->iconList['excel']      = 'excel';
$config->doc->iconList['attachment'] = 'attachment';

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
$config->doc->search['fields']['addedBy']    = $lang->doc->addedBy;
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

$config->doc->actionList['movedoc']['icon']        = 'folder-move';
$config->doc->actionList['movedoc']['hint']        = $lang->doc->moveDocAction;
$config->doc->actionList['movedoc']['text']        = $lang->doc->moveDocAction;
$config->doc->actionList['movedoc']['url']         = helper::createLink('doc', 'moveDoc', 'docID={id}');
$config->doc->actionList['movedoc']['data-toggle'] = 'modal';
$config->doc->actionList['movedoc']['data-size']   = 'sm';

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
$config->doc->actionList['delete']['data-confirm'] = array('message' => $lang->doc->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->doc->showfiles->actionList['download']['icon']   = 'import';
$config->doc->showfiles->actionList['download']['hint']   = $lang->doc->download;
$config->doc->showfiles->actionList['download']['text']   = $lang->doc->download;
$config->doc->showfiles->actionList['download']['url']    = array('module' => 'file', 'method' => 'download', 'params' => 'fileID={id}');
$config->doc->showfiles->actionList['download']['target'] = '_blank';

$config->doc->quickMenu = array();
$config->doc->quickMenu['view']      = array('id' => 1, 'name' => $lang->doc->myView);
$config->doc->quickMenu['createdby'] = array('id' => 2, 'name' => $lang->doc->myCreation);
$config->doc->quickMenu['collect']   = array('id' => 3, 'name' => $lang->doc->myCollection);
$config->doc->quickMenu['editedby']  = array('id' => 4, 'name' => $lang->doc->myEdited);

$config->doc->zentaoListMenuPosition = 22;

$config->doc->templateTypeParents = array();
$config->doc->templateTypeParents['PP']   = 'plan';
$config->doc->templateTypeParents['QAP']  = 'plan';
$config->doc->templateTypeParents['CMP']  = 'plan';
$config->doc->templateTypeParents['ITP']  = 'plan';
$config->doc->templateTypeParents['STP']  = 'plan';
$config->doc->templateTypeParents['ERS']  = 'story';
$config->doc->templateTypeParents['URS']  = 'story';
$config->doc->templateTypeParents['SRS']  = 'story';
$config->doc->templateTypeParents['HLDS'] = 'design';
$config->doc->templateTypeParents['DDS']  = 'design';
$config->doc->templateTypeParents['DBDS'] = 'design';
$config->doc->templateTypeParents['ADS']  = 'design';
$config->doc->templateTypeParents['Code'] = 'dev';
$config->doc->templateTypeParents['ITTC'] = 'test';
$config->doc->templateTypeParents['STTC'] = 'test';
$config->doc->templateTypeParents['UM']   = 'desc';

$config->doc->zentaoList = array();
$config->doc->zentaoList['story'] = array('key' => 'story', 'name' => $lang->doc->zentaoList['story'] . $lang->doc->list, 'icon' => 'lightbulb',  'subMenu' => array(), 'priv' => 'storyBrowse');
$config->doc->zentaoList['task']  = array('key' => 'task',  'name' => $lang->doc->zentaoList['task'] . $lang->doc->list,  'icon' => 'check-sign', 'module' => 'execution', 'method' => 'task', 'params' => 'execution=0&status=unclosed&param=0&orderBy=&recTotal=0&recPerPage=100&pageID=1&from=doc', 'priv' => 'taskBrowse');
$config->doc->zentaoList['case']  = array('key' => 'case',  'name' => $lang->doc->zentaoList['case'] . $lang->doc->list,  'icon' => 'testcase',   'subMenu' => array(), 'priv' => 'caseBrowse', 'vision' => array('rnd'));
$config->doc->zentaoList['bug']   = array('key' => 'bug',   'name' => $lang->doc->zentaoList['bug'] . $lang->doc->list,   'icon' => 'bug',        'subMenu' => array(), 'priv' => 'bugBrowse');
$config->doc->zentaoList['more']  = array('key' => 'more',  'name' => $lang->doc->zentaoList['more'] . $lang->doc->list,  'icon' => 'ellipsis-v', 'subMenu' => array());

$config->doc->zentaoList['story']['subMenu'][] = array('key' => 'productStory',   'name' => $lang->doc->zentaoList['productStory'] . $lang->doc->list,   'icon' => 'lightbulb-alt', 'module' => 'product', 'method' => 'browse', 'params' => 'productID=0&branch=all&browseType=&param=0&storyType=story&orderBy=&recTotal=0&recPerPage=20&pageID=1&projectID=0&from=doc', 'priv' => 'productStory');
$config->doc->zentaoList['story']['subMenu'][] = array('key' => 'projectStory',   'name' => $lang->doc->zentaoList['projectStory'] . $lang->doc->list,   'icon' => 'project',       'module' => 'projectStory', 'method' => 'story', 'params' => 'projectID=0&productID=0&branch=&browseTyp=&param=0&storyType=story&orderBy=&recTotal=0&recPerPage=20&pageID=1&from=doc', 'priv' => 'projectStory');
$config->doc->zentaoList['story']['subMenu'][] = array('key' => 'executionStory', 'name' => $lang->doc->zentaoList['executionStory'] . $lang->doc->list, 'icon' => 'run',           'module' => 'execution', 'method' => 'story', 'params' => 'executionID=0&storyType=story&orderBy=&type=all&param=0&recTotal=0&recPerPage=20&pageID=1&from=doc', 'priv' => 'executionStory');
$config->doc->zentaoList['story']['subMenu'][] = array('key' => 'planStory',      'name' => $lang->doc->zentaoList['planStory'] . $lang->doc->list,      'icon' => 'productplan',   'module' => 'productplan', 'method' => 'story', 'params' => 'productID=0&planID=0&blockID=0', 'priv' => 'productplanView');

$config->doc->zentaoList['case']['subMenu'][] = array('key' => 'productCase', 'name' => $lang->doc->zentaoList['productCase'] . $lang->doc->list, 'icon' => 'lightbulb-alt', 'module' => 'testcase', 'method' => 'browse', 'params' => 'productID=0&branch=&browseType=all&param=0&caseType=&orderBy=sort_asc,id_desc&recTotal=0&recPerPage=20&pageID=1&projectID=0&from=doc', 'priv' => 'productCase');
$config->doc->zentaoList['case']['subMenu'][] = array('key' => 'caselib',     'name' => $lang->doc->zentaoList['caselib'] . $lang->doc->list,     'icon' => 'usecase',       'module' => 'caselib', 'method' => 'browse', 'params' => 'libID=0&browseType=all&param=0&orderBy=id_desc&recTotal=0&recPerPage=20&pageID=1&from=doc', 'priv' => 'caselibBrowse');

$config->doc->zentaoList['bug']['subMenu'][] = array('key' => 'productBug', 'name' => $lang->doc->zentaoList['productBug'] . $lang->doc->list, 'icon' => 'lightbulb-alt', 'module' => 'bug', 'method' => 'browse', 'params' => 'productID=0&branch=&browseType=&param=0&orderBy=&recTotal=0&recPerPage=20&pageID=1&from=doc', 'priv' => 'productBug');
$config->doc->zentaoList['bug']['subMenu'][] = array('key' => 'planBug',    'name' => $lang->doc->zentaoList['planBug'] . $lang->doc->list,    'icon' => 'productplan',   'module' => 'productplan', 'method' => 'bug', 'params' => 'productID=0&planID=0&blockID=0', 'priv' => 'productplanView');

$config->doc->zentaoList['more']['subMenu'][] = array('key' => 'productPlan',    'name' => $lang->doc->zentaoList['productPlan'] . $lang->doc->list,    'icon' => 'productplan',   'module' => 'productplan', 'method' => 'browse', 'params' => 'productID=0&branch=&browseType=undone&queryID=0&orderBy=begin_desc&recTotal=0&recPerPage=20&pageID=1&from=doc', 'priv' => 'productplanBrowse');
$config->doc->zentaoList['more']['subMenu'][] = array('key' => 'productRelease', 'name' => $lang->doc->zentaoList['productRelease'] . $lang->doc->list, 'icon' => 'send',          'module' => 'release', 'method' => 'browse', 'params' => 'productID=0&branch=all&type=all&orderBy=&param=0&recTotal=0&recPerPage=20&pageID=1&from=doc', 'priv' => 'releaseBrowse');
$config->doc->zentaoList['more']['subMenu'][] = array('key' => 'projectRelease', 'name' => $lang->doc->zentaoList['projectRelease'] . $lang->doc->list, 'icon' => 'send',          'module' => 'projectRelease', 'method' => 'browse', 'params' => 'projectID=0&executionID=0&type=all&orderBy=&recTotal=0&recPerPage=20&pageID=1&from=doc', 'priv' => 'projectReleaseBrowse');
$config->doc->zentaoList['more']['subMenu'][] = array('key' => 'ER',             'name' => $lang->doc->zentaoList['ER'] . $lang->doc->list,             'icon' => 'lightbulb-alt', 'module' => 'product', 'method' => 'browse', 'params' => 'productID=0&branch=all&browseType=&param=0&storyType=epic&orderBy=&recTotal=0&recPerPage=20&pageID=1&projectID=0&from=doc', 'priv' => 'epicBrowse');
$config->doc->zentaoList['more']['subMenu'][] = array('key' => 'UR',             'name' => $lang->doc->zentaoList['UR'] . $lang->doc->list,             'icon' => 'customer',      'module' => 'product', 'method' => 'browse', 'params' => 'productID=0&branch=all&browseType=&param=0&storyType=requirement&orderBy=&recTotal=0&recPerPage=20&pageID=1&projectID=0&from=doc', 'priv' => 'requirementBrowse');

if(in_array($config->edition, array('biz', 'max', 'ipd')))
{
    $config->doc->zentaoList['more']['subMenu'][] = array('key' => 'feedback', 'name' => $lang->doc->zentaoList['feedback'] . $lang->doc->list, 'icon' => 'feedback', 'module' => 'feedback', 'method' => 'admin', 'params' => 'browseType=wait&param=0&orderBy=editedDate_desc,id_desc&recTotal=0&recPerPage=20&pageID=1&from=doc', 'priv' => 'feedbackBrowse');
    $config->doc->zentaoList['more']['subMenu'][] = array('key' => 'ticket',   'name' => $lang->doc->zentaoList['ticket'] . $lang->doc->list,   'icon' => 'support-ticket', 'module' => 'ticket', 'method' => 'browse', 'params' => 'browseType=wait&param=0&orderBy=id_desc&recTotal=0&recPerPage=20&pageID=1&from=doc', 'priv' => 'ticketBrowse');
}

if(in_array($config->edition, array('max', 'ipd'))) $config->doc->zentaoList['gantt'] = array('key' => 'gantt', 'name' => $lang->doc->zentaoList['gantt'],  'icon' => 'gantt', 'isModal' => true, 'module' => 'programPlan', 'method' => 'browse', "params" => 'projectID=0&productID=0&type=gantt&orderBy=id_asc&baselineID=0&browseType=&queryID=0&from=doc', 'priv' => 'programplanBrowse');

//$config->doc->zentaoList['storyView'] = array('key' => 'storyView', 'name' => $lang->doc->zentaoList['story'] . $lang->doc->detail, 'icon' => 'lightbulb',  'priv' => 'storyView');
//$config->doc->zentaoList['taskView']  = array('key' => 'taskView',  'name' => $lang->doc->zentaoList['task'] . $lang->doc->detail,  'icon' => 'check-sign', 'priv' => 'taskView');
//$config->doc->zentaoList['caseView']  = array('key' => 'caseView',  'name' => $lang->doc->zentaoList['case'] . $lang->doc->detail,  'icon' => 'testcase',   'priv' => 'caseView');
//$config->doc->zentaoList['bugView']   = array('key' => 'bugView',   'name' => $lang->doc->zentaoList['bug'] . $lang->doc->detail,   'icon' => 'bug',        'priv' => 'bugView');
//$config->doc->zentaoList['moreView']  = array('key' => 'moreView',  'name' => $lang->doc->zentaoList['more'] . $lang->doc->detail,  'icon' => 'ellipsis-v', 'subMenu' => array());
//
//$config->doc->zentaoList['moreView']['subMenu'][] = array('key' => 'productPlanView',    'name' => $lang->doc->zentaoList['productPlan'] . $lang->doc->detail,    'icon' => 'productplan',   'priv' => 'productplanView');
//$config->doc->zentaoList['moreView']['subMenu'][] = array('key' => 'productReleaseView', 'name' => $lang->doc->zentaoList['productRelease'] . $lang->doc->detail, 'icon' => 'send',          'priv' => 'releaseView');
//if(in_array($config->edition, array('biz', 'max', 'ipd')))
//{
//    $config->doc->zentaoList['moreView']['subMenu'][] = array('key' => 'feedbackView', 'name' => $lang->doc->zentaoList['feedback'] . $lang->doc->detail, 'icon' => 'feedback',  'priv' => 'feedbackView');
//    $config->doc->zentaoList['moreView']['subMenu'][] = array('key' => 'ticketView',   'name' => $lang->doc->zentaoList['ticket'] . $lang->doc->detail,   'icon' => 'support-ticket',   'priv' => 'ticketView');
//}
