<?php
/**
 * The en file of crm block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block 
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
$lang->block = new stdclass();
$lang->block->common = 'Block';
$lang->block->name   = 'Name';
$lang->block->style  = 'Style';
$lang->block->grid   = 'Grid';
$lang->block->color  = 'Color';

$lang->block->account  = 'Account';
$lang->block->module   = 'Module';
$lang->block->title    = 'Title';
$lang->block->source   = 'Source module';
$lang->block->block    = 'Source block';
$lang->block->order    = 'Order';
$lang->block->height   = 'Height';

$lang->block->lblModule    = 'Module';
$lang->block->lblBlock     = 'Block';
$lang->block->lblNum       = 'Number';
$lang->block->lblHtml      = 'HTML';
$lang->block->dynamic      = 'Dynamic';
$lang->block->assignToMe   = 'Assign To Me';
$lang->block->lblFlowchart = 'Workflow';

$lang->block->assignToMeList['todo']  = 'Todo';
$lang->block->assignToMeList['story'] = 'Story';
$lang->block->assignToMeList['task']  = 'Task';
$lang->block->assignToMeList['bug']   = 'Bug';
$lang->block->assignToMeList['case']  = 'Case';

$lang->block->params = new stdclass();
$lang->block->params->name  = 'Name';
$lang->block->params->value = 'Value';

$lang->block->createBlock        = 'Add';
$lang->block->editBlock          = 'Edit';
$lang->block->ordersSaved        = 'Order is saved.';
$lang->block->confirmRemoveBlock = 'Do you want to remove Block【{0}】?';
$lang->block->closeForever       = 'Permanent Close';
$lang->block->confirmClose       = 'Do you want to permanently close this block? Once done, it is not available to anyone. It can be activiated at Admin->Custom.';
$lang->block->remove             = 'Remove';
$lang->block->refresh            = 'Refresh';
$lang->block->hidden             = 'Hide';
$lang->block->dynamicInfo        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>.";

$lang->block->default['product']['1']['title'] = 'Open ' . $lang->productCommon . 's';
$lang->block->default['product']['1']['block'] = 'list';
$lang->block->default['product']['1']['grid']  = 8;

$lang->block->default['product']['1']['params']['num']  = 15;
$lang->block->default['product']['1']['params']['type'] = 'noclosed';

$lang->block->default['product']['2']['title'] = 'My Stories';
$lang->block->default['product']['2']['block'] = 'story';
$lang->block->default['product']['2']['grid']  = 4;

$lang->block->default['product']['2']['params']['num']     = 15;
$lang->block->default['product']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['product']['2']['params']['type']    = 'assignedTo';

$lang->block->default['project']['1']['title'] = 'Active ' . $lang->projectCommon . 's';
$lang->block->default['project']['1']['block'] = 'list';
$lang->block->default['project']['1']['grid']  = 8;

$lang->block->default['project']['1']['params']['num']     = 15;
$lang->block->default['project']['1']['params']['orderBy'] = 'id_desc';
$lang->block->default['project']['1']['params']['type']    = 'undone';

$lang->block->default['project']['2']['title'] = 'My Tasks';
$lang->block->default['project']['2']['block'] = 'task';
$lang->block->default['project']['2']['grid']  = 4;

$lang->block->default['project']['2']['params']['num']     = 15;
$lang->block->default['project']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['project']['2']['params']['type']    = 'assignedTo';

$lang->block->default['qa']['1']['title'] = 'My Bugs';
$lang->block->default['qa']['1']['block'] = 'bug';
$lang->block->default['qa']['1']['grid']  = 4;

$lang->block->default['qa']['1']['params']['num']     = 15;
$lang->block->default['qa']['1']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['1']['params']['type']    = 'assignedTo';

$lang->block->default['qa']['2']['title'] = 'My Cases';
$lang->block->default['qa']['2']['block'] = 'case';
$lang->block->default['qa']['2']['grid']  = 4;

$lang->block->default['qa']['2']['params']['num']     = 15;
$lang->block->default['qa']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['2']['params']['type']    = 'assigntome';

$lang->block->default['qa']['3']['title'] = 'Pending Builds';
$lang->block->default['qa']['3']['block'] = 'testtask';
$lang->block->default['qa']['3']['grid']  = 4;

$lang->block->default['qa']['3']['params']['num']     = 15;
$lang->block->default['qa']['3']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['3']['params']['type']    = 'wait';

$lang->block->default['full']['my']['1']['title']  = 'Flowchart';
$lang->block->default['full']['my']['1']['block']  = 'flowchart';
$lang->block->default['full']['my']['1']['grid']   = 8;
$lang->block->default['full']['my']['1']['source'] = '';
$lang->block->default['full']['my']['2']['title']  = 'Dynamic';
$lang->block->default['full']['my']['2']['block']  = 'dynamic';
$lang->block->default['full']['my']['2']['grid']   = 4;
$lang->block->default['full']['my']['2']['source'] = '';
$lang->block->default['full']['my']['3'] = $lang->block->default['project']['1'];
$lang->block->default['full']['my']['3']['source'] = 'project';
$lang->block->default['full']['my']['4']['title']  = '指派给我';
$lang->block->default['full']['my']['4']['block']  = 'assigntome';
$lang->block->default['full']['my']['4']['grid']   = 8;
$lang->block->default['full']['my']['4']['source'] = '';
$lang->block->default['full']['my']['5']['title']  = 'My Todo';
$lang->block->default['full']['my']['5']['block']  = 'list';
$lang->block->default['full']['my']['5']['grid']   = 4;
$lang->block->default['full']['my']['5']['source'] = 'todo';
$lang->block->default['full']['my']['5']['params']['num'] = '20';
$lang->block->default['full']['my']['6'] = $lang->block->default['product']['1'];
$lang->block->default['full']['my']['6']['source'] = 'product';
$lang->block->default['full']['my']['7'] = $lang->block->default['project']['2'];
$lang->block->default['full']['my']['7']['source'] = 'project';
$lang->block->default['full']['my']['8'] = $lang->block->default['qa']['1'];
$lang->block->default['full']['my']['8']['source'] = 'qa';
$lang->block->default['full']['my']['9'] = $lang->block->default['product']['2'];
$lang->block->default['full']['my']['9']['source'] = 'product';
$lang->block->default['full']['my']['10'] = $lang->block->default['qa']['2'];
$lang->block->default['full']['my']['10']['source'] = 'qa';

$lang->block->default['onlyTest']['my']['1'] = $lang->block->default['qa']['1'];
$lang->block->default['onlyTest']['my']['1']['source'] = 'qa';
$lang->block->default['onlyTest']['my']['1']['grid']   = '8';
$lang->block->default['onlyTest']['my']['2']['title']  = 'Dynamic';
$lang->block->default['onlyTest']['my']['2']['block']  = 'dynamic';
$lang->block->default['onlyTest']['my']['2']['grid']   = 4;
$lang->block->default['onlyTest']['my']['2']['source'] = '';
$lang->block->default['onlyTest']['my']['3']['title']  = 'My Todo';
$lang->block->default['onlyTest']['my']['3']['block']  = 'list';
$lang->block->default['onlyTest']['my']['3']['grid']   = 6;
$lang->block->default['onlyTest']['my']['3']['source'] = 'todo';
$lang->block->default['onlyTest']['my']['3']['params']['num'] = '20';
$lang->block->default['onlyTest']['my']['4'] = $lang->block->default['qa']['2'];
$lang->block->default['onlyTest']['my']['4']['source'] = 'qa';
$lang->block->default['onlyTest']['my']['4']['grid']   = 6;

$lang->block->default['onlyStory']['my']['1'] = $lang->block->default['project']['1'];
$lang->block->default['onlyStory']['my']['1']['source'] = 'project';
$lang->block->default['onlyStory']['my']['1']['grid']   = 8;
$lang->block->default['onlyStory']['my']['2']['title']  = 'Dynamic';
$lang->block->default['onlyStory']['my']['2']['block']  = 'dynamic';
$lang->block->default['onlyStory']['my']['2']['grid']   = 4;
$lang->block->default['onlyStory']['my']['2']['source'] = '';
$lang->block->default['onlyStory']['my']['3']['title']  = 'My Todo';
$lang->block->default['onlyStory']['my']['3']['block']  = 'list';
$lang->block->default['onlyStory']['my']['3']['grid']   = 6;
$lang->block->default['onlyStory']['my']['3']['source'] = 'todo';
$lang->block->default['onlyStory']['my']['3']['params']['num'] = '20';
$lang->block->default['onlyStory']['my']['4'] = $lang->block->default['product']['2'];
$lang->block->default['onlyStory']['my']['4']['source'] = 'product';
$lang->block->default['onlyStory']['my']['4']['grid']   = 6;

$lang->block->default['onlyTask']['my']['1'] = $lang->block->default['project']['1'];
$lang->block->default['onlyTask']['my']['1']['source'] = 'project';
$lang->block->default['onlyTask']['my']['1']['grid']   = 8;
$lang->block->default['onlyTask']['my']['2']['title']  = 'Dynamic';
$lang->block->default['onlyTask']['my']['2']['block']  = 'dynamic';
$lang->block->default['onlyTask']['my']['2']['grid']   = 4;
$lang->block->default['onlyTask']['my']['2']['source'] = '';
$lang->block->default['onlyTask']['my']['3']['title']  = 'My Todo';
$lang->block->default['onlyTask']['my']['3']['block']  = 'list';
$lang->block->default['onlyTask']['my']['3']['grid']   = 6;
$lang->block->default['onlyTask']['my']['3']['source'] = 'todo';
$lang->block->default['onlyTask']['my']['3']['params']['num'] = '20';
$lang->block->default['onlyTask']['my']['4'] = $lang->block->default['project']['2'];
$lang->block->default['onlyTask']['my']['4']['source'] = 'project';
$lang->block->default['onlyTask']['my']['4']['grid']   = 6;

$lang->block->num      = 'Number';
$lang->block->type     = 'Type';
$lang->block->orderBy  = 'Order by';

$lang->block->availableBlocks = new stdclass();

$lang->block->availableBlocks->todo     = 'My Todo';
$lang->block->availableBlocks->task     = 'My Tasks';
$lang->block->availableBlocks->bug      = 'My Bugs';
$lang->block->availableBlocks->case     = 'My Cases';
$lang->block->availableBlocks->story    = 'My Stories';
$lang->block->availableBlocks->product  = $lang->productCommon . 'List';
$lang->block->availableBlocks->project  = $lang->projectCommon . 'List';
$lang->block->availableBlocks->plan     = 'Plans';
$lang->block->availableBlocks->release  = 'Releases';
$lang->block->availableBlocks->build    = 'Builds';
$lang->block->availableBlocks->testtask = 'Test Builds';

$lang->block->moduleList['product'] = $lang->productCommon;
$lang->block->moduleList['project'] = $lang->projectCommon;
$lang->block->moduleList['qa']      = 'QA';
$lang->block->moduleList['todo']    = 'Todo';

$lang->block->modules['product'] = new stdclass();
$lang->block->modules['product']->availableBlocks = new stdclass();
$lang->block->modules['product']->availableBlocks->list    = $lang->productCommon . 'List';
$lang->block->modules['product']->availableBlocks->story   = 'Stories';
$lang->block->modules['product']->availableBlocks->plan    = 'Plans';
$lang->block->modules['product']->availableBlocks->release = 'Releases';
$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks = new stdclass();
$lang->block->modules['project']->availableBlocks->list  = $lang->projectCommon . 'List';
$lang->block->modules['project']->availableBlocks->task  = 'Tasks';
$lang->block->modules['project']->availableBlocks->build = 'Builds';
$lang->block->modules['qa']      = new stdclass();
$lang->block->modules['qa']->availableBlocks = new stdclass();
$lang->block->modules['qa']->availableBlocks->bug      = 'Bugs';
$lang->block->modules['qa']->availableBlocks->case     = 'Cases';
$lang->block->modules['qa']->availableBlocks->testtask = 'Test Builds';
$lang->block->modules['todo'] = new stdclass();
$lang->block->modules['todo']->availableBlocks = new stdclass();
$lang->block->modules['todo']->availableBlocks->list = 'Todo';

$lang->block->orderByList = new stdclass();

$lang->block->orderByList->task = array();
$lang->block->orderByList->task['id_asc']        = 'ID Ascending';
$lang->block->orderByList->task['id_desc']       = 'ID Descending';
$lang->block->orderByList->task['pri_asc']       = 'Priority Ascending';
$lang->block->orderByList->task['pri_desc']      = 'Priority Descending';
$lang->block->orderByList->task['estimate_asc']  = 'Estimate Ascending';
$lang->block->orderByList->task['estimate_desc'] = 'Estimate Descending';
$lang->block->orderByList->task['status_asc']    = 'Status Ascending';
$lang->block->orderByList->task['status_desc']   = 'Status Descending';
$lang->block->orderByList->task['deadline_asc']  = 'Deadline Ascending';
$lang->block->orderByList->task['deadline_desc'] = 'Deadline Descending';

$lang->block->orderByList->bug = array();
$lang->block->orderByList->bug['id_asc']        = 'ID Ascending';
$lang->block->orderByList->bug['id_desc']       = 'ID Descending';
$lang->block->orderByList->bug['pri_asc']       = 'Priority Ascending';
$lang->block->orderByList->bug['pri_desc']      = 'Priority Descending';
$lang->block->orderByList->bug['severity_asc']  = 'Severity Ascending';
$lang->block->orderByList->bug['severity_desc'] = 'Severity Descending';

$lang->block->orderByList->case = array();
$lang->block->orderByList->case['id_asc']        = 'ID Ascending';
$lang->block->orderByList->case['id_desc']       = 'ID Descending';
$lang->block->orderByList->case['pri_asc']       = 'Priority Ascending';
$lang->block->orderByList->case['pri_desc']      = 'Priority Descending';

$lang->block->orderByList->story = array();
$lang->block->orderByList->story['id_asc']        = 'ID Ascending';
$lang->block->orderByList->story['id_desc']       = 'ID Descending';
$lang->block->orderByList->story['pri_asc']       = 'Priority Ascending';
$lang->block->orderByList->story['pri_desc']      = 'Priority Descending';
$lang->block->orderByList->story['status_asc']    = 'Status Ascending';
$lang->block->orderByList->story['status_desc']   = 'Status Descending';
$lang->block->orderByList->story['stage_asc']     = 'Stage Ascending';
$lang->block->orderByList->story['stage_desc']    = 'Stage Descending';

$lang->block->typeList = new stdclass();

$lang->block->typeList->task['assignedTo'] = 'Assigned to Me';
$lang->block->typeList->task['openedBy']   = 'Created by Me';
$lang->block->typeList->task['finishedBy'] = 'Finished by Me';
$lang->block->typeList->task['closedBy']   = 'Closed by Me';
$lang->block->typeList->task['canceledBy'] = 'Cancelled by Me';

$lang->block->typeList->bug['assignedTo'] = 'Assigned to Me';
$lang->block->typeList->bug['openedBy']   = 'Created by Me';
$lang->block->typeList->bug['resolvedBy'] = 'Resolved by Me';
$lang->block->typeList->bug['closedBy']   = 'Closed by Me';

$lang->block->typeList->case['assigntome'] = 'Assigned to Me';
$lang->block->typeList->case['openedbyme'] = 'Created by Me';

$lang->block->typeList->story['assignedTo'] = 'Assigned to Me';
$lang->block->typeList->story['openedBy']   = 'Created by Me';
$lang->block->typeList->story['reviewedBy'] = 'Reviewed by Me';
$lang->block->typeList->story['closedBy']   = 'Closed by Me' ;
 
$lang->block->typeList->product['noclosed'] = 'Open';
$lang->block->typeList->product['closed']   = 'Closed';
$lang->block->typeList->product['all']      = 'All';
$lang->block->typeList->product['involved'] = 'Involved';

$lang->block->typeList->project['undone']   = 'Unfinished';
$lang->block->typeList->project['isdoing']  = 'Doing';
$lang->block->typeList->project['all']      = 'All';
$lang->block->typeList->project['involved'] = 'Involved';

$lang->block->typeList->testtask['wait']    = 'Wait';
$lang->block->typeList->testtask['doing']   = 'Doing';
$lang->block->typeList->testtask['blocked'] = 'Blocked';
$lang->block->typeList->testtask['done']    = 'Done';
$lang->block->typeList->testtask['all']     = 'All';

$lang->block->modules['product']->moreLinkList = new stdclass();
$lang->block->modules['product']->moreLinkList->list    = 'product|all|product=&status=%s';
$lang->block->modules['product']->moreLinkList->story   = 'my|story|type=%s';
$lang->block->modules['project']->moreLinkList = new stdclass();
$lang->block->modules['project']->moreLinkList->list  = 'project|all|status=%s&project=';
$lang->block->modules['project']->moreLinkList->task  = 'my|task|type=%s';
$lang->block->modules['qa']->moreLinkList = new stdclass();
$lang->block->modules['qa']->moreLinkList->bug      = 'my|bug|type=%s';
$lang->block->modules['qa']->moreLinkList->case     = 'my|testcase|type=%s';
$lang->block->modules['qa']->moreLinkList->testtask = 'testtask|browse|type=%s';
$lang->block->modules['todo']->moreLinkList = new stdclass();
$lang->block->modules['todo']->moreLinkList->list = 'my|todo|type=all';
$lang->block->modules['common'] = new stdclass();
$lang->block->modules['common']->moreLinkList = new stdclass();
$lang->block->modules['common']->moreLinkList->dynamic = 'company|dynamic|';

$lang->block->flowchart   = array();
$lang->block->flowchart[] = array('Administration',   'Manage a Company', 'Add Users', 'Maintain Privileges');
$lang->block->flowchart[] = array($lang->productCommon . ' Manager', 'Add ' . $lang->productCommon, 'Maintain Modules', 'Maintain Plans', 'Maintain Stories', 'Create Release');
$lang->block->flowchart[] = array($lang->projectCommon . ' Manager', 'Add ' . $lang->projectCommon, 'Maintain Teams', 'Link ' . $lang->productCommon . 's', 'Link Stories', 'Decompose Tasks');
$lang->block->flowchart[] = array('DEV Team', 'Claim Tasks/Bugs', 'Update Status', 'Finish Tasks/Bugs');
$lang->block->flowchart[] = array('QA Team', 'Write Cases', 'Implement Cases', 'Report Bugs', 'Resolve Bugs', 'Close Bugs');
