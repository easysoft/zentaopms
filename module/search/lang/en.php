<?php
/**
 * The search module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->search->common        = 'Search';
$lang->search->reset         = 'Reset';
$lang->search->saveQuery     = 'Save Query';
$lang->search->myQuery       = 'My Query';
$lang->search->group1        = 'Group 1';
$lang->search->group2        = 'Group 2';
$lang->search->buildForm     = 'Search Form';
$lang->search->buildQuery    = 'Execute Query';
$lang->search->savedQuery    = 'Saved Query';
$lang->search->deleteQuery   = 'Delete Query';
$lang->search->setQueryTitle = 'Enter a title. Search then the query is saved.';
$lang->search->select        = 'Story/Task Filter';
$lang->search->me            = 'Me';
$lang->search->noQuery       = 'No query is saved yet!';
$lang->search->onMenuBar     = 'Show in Menu';
$lang->search->custom        = 'Custom';

$lang->search->account  = 'Account';
$lang->search->module   = 'Module';
$lang->search->title    = 'Title';
$lang->search->form     = 'Form Field';
$lang->search->sql      = 'SQL Condition';
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
$lang->search->operators['belong']     = 'Belong';

$lang->search->andor['and']         = 'And';
$lang->search->andor['or']          = 'Or';

$lang->search->null = 'Null';

$lang->userquery        = new stdclass();
$lang->userquery->title = 'Title';

$lang->searchObjects['todo']      = 'Todo';
$lang->searchObjects['effort']    = 'Effort';
$lang->searchObjects['testsuite'] = 'Test Suite';

$lang->search->objectType = 'Object Type';
$lang->search->objectID   = 'Object ID';
$lang->search->content    = 'Content';
$lang->search->addedDate  = 'Added';

$lang->search->index      = 'Full Text Search';
$lang->search->buildIndex = 'Rebuild Index';
$lang->search->preview    = 'Preview';

$lang->search->result            = 'Search Results';
$lang->search->buildSuccessfully = 'Search index initialized.';
$lang->search->executeInfo       = '%s search results for you in %s seconds.';
$lang->search->buildResult       = 'Created index %s and added %s records.';

$lang->search->modules['all']         = 'All';
$lang->search->modules['task']        = 'Task';
$lang->search->modules['bug']         = 'Bug';
$lang->search->modules['case']        = 'Case';
$lang->search->modules['doc']         = 'Doc';
$lang->search->modules['todo']        = 'Todo';
$lang->search->modules['build']       = 'Build';
$lang->search->modules['effort']      = 'Effort';
$lang->search->modules['caselib']     = 'CaseLib';
$lang->search->modules['product']     = $lang->productCommon;
$lang->search->modules['release']     = 'Release';
$lang->search->modules['testtask']    = 'Test Request';
$lang->search->modules['testsuite']   = 'Test Suite';
$lang->search->modules['testreport']  = 'Testing Report';
$lang->search->modules['productplan'] = 'Plan';
$lang->search->modules['program']     = 'Program';
$lang->search->modules['project']     = 'Project';
$lang->search->modules['execution']   = $lang->executionCommon;
$lang->search->modules['story']       = 'Story';

$lang->search->objectTypeList['story']            = $lang->SRCommon;
$lang->search->objectTypeList['requirement']      = $lang->URCommon;
$lang->search->objectTypeList['stage']            = 'stage';
$lang->search->objectTypeList['sprint']           = $lang->executionCommon;
$lang->search->objectTypeList['commonIssue']      = 'Issue';
$lang->search->objectTypeList['stakeholderIssue'] = 'Stakeholder Issue';
