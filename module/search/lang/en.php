<?php
/**
 * The search module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->search->common        = 'Search';
$lang->search->id            = 'ID';
$lang->search->editedDate    = 'Edited on';
$lang->search->key           = 'Key';
$lang->search->value         = 'Value';
$lang->search->reset         = 'Reset';
$lang->search->saveQuery     = 'Save Query';
$lang->search->myQuery       = 'My Queries';
$lang->search->group1        = 'Group 1';
$lang->search->group2        = 'Group 2';
$lang->search->buildForm     = 'Search Form';
$lang->search->buildQuery    = 'Search';
$lang->search->savedQuery    = 'Saved Queries';
$lang->search->deleteQuery   = 'Delete Query';
$lang->search->setQueryTitle = 'Please enter a query title (Search before saving):';
$lang->search->select        = "{$lang->SRCommon}/Task Filter";
$lang->search->me            = 'Me';
$lang->search->noQuery       = 'No saved queries yet';
$lang->search->onMenuBar     = 'Show in Menu Bar';
$lang->search->custom        = 'Custom';
$lang->search->setCommon     = 'Set as Public Query';
$lang->search->saveCondition = 'Save Search Criteria';
$lang->search->setCondName   = 'Enter a name for this search';

$lang->search->account  = 'Account';
$lang->search->module   = 'Module';
$lang->search->title    = 'Title';
$lang->search->form     = 'Form Fields';
$lang->search->sql      = 'SQL Conditions';
$lang->search->shortcut = $lang->search->onMenuBar;

$lang->search->operators['=']          = '=';
$lang->search->operators['!=']         = '!=';
$lang->search->operators['>']          = '>';
$lang->search->operators['>=']         = '>=';
$lang->search->operators['<']          = '<';
$lang->search->operators['<=']         = '<=';
$lang->search->operators['include']    = 'Include';
$lang->search->operators['between']    = 'Between';
$lang->search->operators['notinclude'] = 'Exclude';
$lang->search->operators['belong']     = 'Belongs to ';

$lang->search->andor['and']         = 'And';
$lang->search->andor['or']          = 'Or';

$lang->search->null = 'Null';

$lang->userquery        = new stdclass();
$lang->userquery->title = 'Title';

$lang->searchObjects['todo']      = 'To-do';
$lang->searchObjects['effort']    = 'Effort';
$lang->searchObjects['testsuite'] = 'Test Suite';

$lang->search->objectType = 'Item Type';
$lang->search->objectID   = 'Item ID';
$lang->search->content    = 'Content';
$lang->search->addedDate  = 'Added on';

$lang->search->index      = 'Full-Text Search';
$lang->search->buildIndex = 'Rebuild Index';
$lang->search->preview    = 'Preview';

$lang->search->inputWords        = 'Search keywords...';
$lang->search->result            = 'Search Results';
$lang->search->resultCount       = '<strong>%s</strong> items';
$lang->search->buildSuccessfully = 'Search index initialized';
$lang->search->executeInfo       = '%s results found in %s seconds';
$lang->search->buildResult       = "Creating %s index: <strong class='%scount'>%s</strong> records created;";
$lang->search->queryTips         = "Separate ids with comma";
$lang->search->confirmDelete     = 'Are you sure you want to delete this record?';

$lang->search->modules['all']         = 'All';
$lang->search->modules['task']        = 'Task';
$lang->search->modules['bug']         = 'Bug';
$lang->search->modules['case']        = 'Case';
$lang->search->modules['doc']         = 'Document';
$lang->search->modules['todo']        = 'To-Do';
$lang->search->modules['build']       = 'Builds';
$lang->search->modules['effort']      = 'Effort';
$lang->search->modules['caselib']     = 'Test Library';
$lang->search->modules['product']     = $lang->productCommon;
$lang->search->modules['release']     = 'Releases';
$lang->search->modules['testtask']    = 'Test Requests';
$lang->search->modules['testsuite']   = 'Test Suites';
$lang->search->modules['testreport']  = 'Test Reports';
$lang->search->modules['productplan'] = 'Plans';
$lang->search->modules['program']     = 'Programs';
$lang->search->modules['project']     = $lang->projectCommon;
$lang->search->modules['execution']   = $lang->execution->common;
$lang->search->modules['story']       = $lang->SRCommon;
$lang->search->modules['requirement'] = $lang->URCommon;
$lang->search->modules['epic']        = $lang->ERCommon;
$lang->search->modules['aiapp']       = 'AI';

$lang->search->objectTypeList['story']            = $lang->SRCommon;
$lang->search->objectTypeList['requirement']      = $lang->URCommon;
$lang->search->objectTypeList['epic']             = $lang->ERCommon;
$lang->search->objectTypeList['stage']            = 'Phases';
$lang->search->objectTypeList['sprint']           = $lang->execution->common;
$lang->search->objectTypeList['kanban']           = 'kanban';
$lang->search->objectTypeList['commonIssue']      = 'Issues';
$lang->search->objectTypeList['stakeholderIssue'] = 'Stakeholder Issues';

if(!helper::hasFeature('testsuite') ) unset($lang->searchObjects['testsuite']);
