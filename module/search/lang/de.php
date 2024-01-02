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
$lang->search->common        = 'Suche';
$lang->search->id            = 'ID';
$lang->search->editedDate    = 'Edited Date';
$lang->search->key           = 'Key';
$lang->search->value         = 'Value';
$lang->search->reset         = 'Zurücksetzen';
$lang->search->saveQuery     = 'Speichern';
$lang->search->myQuery       = 'Meine Suchen';
$lang->search->group1        = 'Gruppe 1';
$lang->search->group2        = 'Gruppe 2';
$lang->search->buildForm     = 'Suchformular';
$lang->search->buildQuery    = 'Suche ausführen';
$lang->search->savedQuery    = 'Suche speichern';
$lang->search->deleteQuery   = 'Suche löschen';
$lang->search->setQueryTitle = 'Bitte Titel angeben und nach der Suche speichern.';
$lang->search->select        = 'Storys/Aufgaben Filter';
$lang->search->me            = 'Ich';
$lang->search->noQuery       = 'Keine Suche gespeichert';
$lang->search->onMenuBar     = 'In Menü anzeigen';
$lang->search->custom        = 'Eigene';
$lang->search->setCommon     = 'Set as public query criteria';
$lang->search->saveCondition = 'Save search options';
$lang->search->setCondName   = 'Please enter a save condition name';

$lang->search->account  = 'Konto';
$lang->search->module   = 'Module';
$lang->search->title    = 'Titel';
$lang->search->form     = 'Formfeld';
$lang->search->sql      = 'SQL Bedingung';
$lang->search->shortcut = $lang->search->onMenuBar;

$lang->search->operators['=']          = '=';
$lang->search->operators['!=']         = '!=';
$lang->search->operators['>']          = '>';
$lang->search->operators['>=']         = '>=';
$lang->search->operators['<']          = '<';
$lang->search->operators['<=']         = '<=';
$lang->search->operators['include']    = 'enthält';
$lang->search->operators['between']    = 'zwischen';
$lang->search->operators['notinclude'] = 'ohne';
$lang->search->operators['belong']     = 'gehört zu';

$lang->search->andor['and']         = 'Und';
$lang->search->andor['or']          = 'Oder';

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

$lang->search->inputWords        = 'Please input search words';
$lang->search->result            = 'Search Results';
$lang->search->resultCount       = '<strong>%s</strong> items';
$lang->search->buildSuccessfully = 'Search index initialized.';
$lang->search->executeInfo       = '%s search results for you in %s seconds.';
$lang->search->buildResult       = "Create index %s and created <strong class='%scount'>%s</strong> records.";
$lang->search->queryTips         = "Separate ids with comma";
$lang->search->confirmDelete     = 'Are you sure to delete this record';

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
$lang->search->modules['project']     = $lang->projectCommon;
$lang->search->modules['execution']   = $lang->execution->common;
$lang->search->modules['story']       = $lang->SRCommon;
$lang->search->modules['requirement'] = $lang->URCommon;

$lang->search->objectTypeList['story']            = $lang->SRCommon;
$lang->search->objectTypeList['requirement']      = $lang->URCommon;
$lang->search->objectTypeList['stage']            = 'stage';
$lang->search->objectTypeList['sprint']           = $lang->execution->common;
$lang->search->objectTypeList['kanban']           = 'kanban';
$lang->search->objectTypeList['commonIssue']      = 'Issue';
$lang->search->objectTypeList['stakeholderIssue'] = 'Stakeholder Issue';
