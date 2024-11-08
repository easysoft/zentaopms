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
$config->doc->edit->requiredFields      = 'lib,title';

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

$config->doc->templateMenu = array();
$config->doc->templateMenu[1] = array('id' => 1, 'scope' => 'product',   'name' => $lang->docTemplate->scopes['product']);
$config->doc->templateMenu[2] = array('id' => 2, 'scope' => 'project',   'name' => $lang->docTemplate->scopes['project']);
$config->doc->templateMenu[3] = array('id' => 3, 'scope' => 'execution', 'name' => $lang->docTemplate->scopes['execution']);
$config->doc->templateMenu[4] = array('id' => 4, 'scope' => 'personal',  'name' => $lang->docTemplate->scopes['personal']);

$config->doc->templateModule = array();
$config->doc->templateModule['product'] = array();
$config->doc->templateModule['product']['plan'] = array();
$config->doc->templateModule['product']['plan']['softwareProductPlan']  = 'Software product plan';
$config->doc->templateModule['product']['plan']['hardwareProductPlan']  = 'Hardware product plan';
$config->doc->templateModule['product']['plan']['qualityAssurancePlan'] = 'Quality assurance plan';

$config->doc->templateModule['product']['desc'] = array();
$config->doc->templateModule['product']['desc']['productDesign']            = 'Product design';
$config->doc->templateModule['product']['desc']['requirementSpecification'] = 'Requirement specification';
$config->doc->templateModule['product']['desc']['userManual']               = 'User manual';

$config->doc->templateModule['product']['report'] = array();
$config->doc->templateModule['product']['report']['competitiveProductAnalysisReport'] = 'Competitive product analysis report';
$config->doc->templateModule['product']['report']['productAcceptanceReport']          = 'Product acceptance report';
$config->doc->templateModule['product']['report']['productSummaryReport']             = 'Product summary report';
$config->doc->templateModule['product']['report']['productQualityReport']             = 'Product quality report';

$config->doc->templateModule['project'] = array();
$config->doc->templateModule['project']['plan'] = array();
$config->doc->templateModule['project']['plan']['projectPlan']                        = 'Project plan';
$config->doc->templateModule['project']['plan']['projectQualityAssurancePlan']        = 'Project quality assurance plan';
$config->doc->templateModule['project']['plan']['projectConfigurationManagementPlan'] = 'Project configuration management plan';
$config->doc->templateModule['project']['plan']['projectIntegrationTestPlan']         = 'Project integration test plan';
$config->doc->templateModule['project']['plan']['projectSystemTestPlan']              = 'Project system test plan';

$config->doc->templateModule['project']['desc'] = array();
$config->doc->templateModule['project']['desc']['businessRequirementsStatement']            = 'Business requirements statement';
$config->doc->templateModule['project']['desc']['projectUserRequirementsSpecification']     = 'Project user requirements specification';
$config->doc->templateModule['project']['desc']['projectSoftwareRequirementsSpecification'] = 'Project software requirements specification';
$config->doc->templateModule['project']['desc']['projectSummaryDesignSpecification']        = 'Project summary design specification';
$config->doc->templateModule['project']['desc']['projectDetailedDesignSpecification']       = 'Project detailed design specification';
$config->doc->templateModule['project']['desc']['projectUserManual']                        = 'Project user manual';

$config->doc->templateModule['project']['dev']  = array();
$config->doc->templateModule['project']['dev']['projectCode']                    = 'Project code';
$config->doc->templateModule['project']['dev']['projectDatabaseDesignDocument']  = 'Project database design document';
$config->doc->templateModule['project']['dev']['projectInterfaceDesignDocument'] = 'Project interface design document';

$config->doc->templateModule['project']['test']  = array();
$config->doc->templateModule['project']['test']['projectIntegrationTestCases'] = 'Project integration test cases';
$config->doc->templateModule['project']['test']['projectSystemTestCases']      = 'Project system test cases';

$config->doc->templateModule['project']['report']  = array();
$config->doc->templateModule['project']['report']['projectProgressReport']   = 'Project progress report';
$config->doc->templateModule['project']['report']['projectRiskReport']       = 'Project risk report';
$config->doc->templateModule['project']['report']['projectAcceptanceReport'] = 'Project acceptance report';
$config->doc->templateModule['project']['report']['projectSummaryReport']    = 'Project summary report';
$config->doc->templateModule['project']['report']['projectQualityReport']    = 'Project quality report';

$config->doc->templateModule['project']['other']  = array();

$config->doc->templateModule['execution'] = array();
$config->doc->templateModule['execution']['plan'] = array();
$config->doc->templateModule['execution']['plan']['executionDevelopmentPlan']      = 'Execution development plan';
$config->doc->templateModule['execution']['plan']['executionQualityAssurancePlan'] = 'Execution quality assurance plan';
$config->doc->templateModule['execution']['plan']['executionTestPlan']             = 'Execution test plan';

$config->doc->templateModule['execution']['desc'] = array();
$config->doc->templateModule['execution']['desc']['executionSoftwareRequirementsSpecification'] = 'Execution software requirements specification';
$config->doc->templateModule['execution']['desc']['executionArchitecturalDesignSpecification']  = 'Execution architectural design specification';
$config->doc->templateModule['execution']['desc']['executionCodeDesignSpecification']           = 'Execution code design specification';

$config->doc->templateModule['execution']['dev'] = array();
$config->doc->templateModule['execution']['dev']['executionCode']                    = 'Execution code';
$config->doc->templateModule['execution']['dev']['executionDatabaseDesignDocument']  = 'Execution database design document';
$config->doc->templateModule['execution']['dev']['executionInterfaceDesignDocument'] = 'Execution interface design document';

$config->doc->templateModule['execution']['test'] = array();
$config->doc->templateModule['execution']['test']['executionFunctionalTesting'] = 'Execution functional testing';
$config->doc->templateModule['execution']['test']['executionTestCases']         = 'Execution test cases';

$config->doc->templateModule['execution']['report'] = array();
$config->doc->templateModule['execution']['report']['executionProgressReport']   = 'Execution progress report';
$config->doc->templateModule['execution']['report']['executionRiskReport']       = 'Execution risk report';
$config->doc->templateModule['execution']['report']['executionAcceptanceReport'] = 'Execution acceptance report';
$config->doc->templateModule['execution']['report']['executionSummaryReport']    = 'Execution summary report';
$config->doc->templateModule['execution']['report']['executionQualityReport']    = 'Execution quality report';

$config->doc->templateModule['personal'] = array();
$config->doc->templateModule['personal']['plan'] = array();
$config->doc->templateModule['personal']['plan']['personalWorkPlan'] = 'Personal work plan';

$config->doc->templateModule['personal']['report'] = array();
$config->doc->templateModule['personal']['report']['personalWorkSummaryReport']   = 'Personal work summary report';
$config->doc->templateModule['personal']['report']['personalValueAnalysisReport'] = 'Personal value analysis report';
