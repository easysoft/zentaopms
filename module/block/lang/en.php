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

$lang->block->lblModule    = 'Module';
$lang->block->lblBlock     = 'Block';
$lang->block->lblNum       = 'Number';
$lang->block->lblHtml      = 'HTML Content';
$lang->block->dynamic      = 'Dynamic';
$lang->block->lblFlowchart = 'Flowchart';

$lang->block->params = new stdclass();
$lang->block->params->name  = 'Name';
$lang->block->params->value = 'Value';

$lang->block->createBlock        = 'Add block';
$lang->block->editBlock          = 'Edit block';
$lang->block->ordersSaved        = 'Sort have been saved';
$lang->block->confirmRemoveBlock = 'Are you sure remove block [{0}] ?';
$lang->block->refresh            = 'Refresh';
$lang->block->hidden             = 'Hidden';
$lang->block->dynamicInfo        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>.";

$lang->block->default['product']['1']['title'] = $lang->productCommon . ' list';
$lang->block->default['product']['1']['block'] = 'list';
$lang->block->default['product']['1']['grid']  = 8;

$lang->block->default['product']['1']['params']['num']  = 15;
$lang->block->default['product']['1']['params']['type'] = 'noclosed';

$lang->block->default['product']['2']['title'] = 'Story of assigned to me';
$lang->block->default['product']['2']['block'] = 'story';
$lang->block->default['product']['2']['grid']  = 4;

$lang->block->default['product']['2']['params']['num']     = 15;
$lang->block->default['product']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['product']['2']['params']['type']    = 'assignedTo';

$lang->block->default['project']['1']['title'] = $lang->projectCommon . ' list';
$lang->block->default['project']['1']['block'] = 'list';
$lang->block->default['project']['1']['grid']  = 8;

$lang->block->default['project']['1']['params']['num']     = 15;
$lang->block->default['project']['1']['params']['orderBy'] = 'id_desc';
$lang->block->default['project']['1']['params']['type']    = 'undone';

$lang->block->default['project']['2']['title'] = 'Task of assigned to me';
$lang->block->default['project']['2']['block'] = 'task';
$lang->block->default['project']['2']['grid']  = 4;

$lang->block->default['project']['2']['params']['num']     = 15;
$lang->block->default['project']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['project']['2']['params']['type']    = 'assignedTo';

$lang->block->default['qa']['1']['title'] = 'Bug of assigned to me';
$lang->block->default['qa']['1']['block'] = 'bug';
$lang->block->default['qa']['1']['grid']  = 4;

$lang->block->default['qa']['1']['params']['num']     = 15;
$lang->block->default['qa']['1']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['1']['params']['type']    = 'assignedTo';

$lang->block->default['qa']['2']['title'] = 'Case of assigned to me';
$lang->block->default['qa']['2']['block'] = 'case';
$lang->block->default['qa']['2']['grid']  = 4;

$lang->block->default['qa']['2']['params']['num']     = 15;
$lang->block->default['qa']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['2']['params']['type']    = 'assigntome';

$lang->block->default['qa']['3']['title'] = 'Waiting test task';
$lang->block->default['qa']['3']['block'] = 'testtask';
$lang->block->default['qa']['3']['grid']  = 4;

$lang->block->default['qa']['3']['params']['num']     = 15;
$lang->block->default['qa']['3']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['3']['params']['type']    = 'wait';

$lang->block->default['my']['1']['title']  = 'Flowchart';
$lang->block->default['my']['1']['block']  = 'flowchart';
$lang->block->default['my']['1']['grid']   = 8;
$lang->block->default['my']['1']['source'] = '';
$lang->block->default['my']['2']['title']  = 'Dynamic';
$lang->block->default['my']['2']['block']  = 'dynamic';
$lang->block->default['my']['2']['grid']   = 4;
$lang->block->default['my']['2']['source'] = '';
$lang->block->default['my']['3'] = $lang->block->default['project']['1'];
$lang->block->default['my']['3']['source'] = 'project';
$lang->block->default['my']['4']['title']  = 'My todo';
$lang->block->default['my']['4']['block']  = 'list';
$lang->block->default['my']['4']['grid']   = 4;
$lang->block->default['my']['4']['source'] = 'todo';
$lang->block->default['my']['4']['params']['num'] = '20';
$lang->block->default['my']['5'] = $lang->block->default['product']['1'];
$lang->block->default['my']['5']['source'] = 'product';
$lang->block->default['my']['6'] = $lang->block->default['project']['2'];
$lang->block->default['my']['6']['source'] = 'project';
$lang->block->default['my']['7'] = $lang->block->default['qa']['1'];
$lang->block->default['my']['7']['source'] = 'qa';
$lang->block->default['my']['8'] = $lang->block->default['product']['2'];
$lang->block->default['my']['8']['source'] = 'product';
$lang->block->default['my']['9'] = $lang->block->default['qa']['2'];
$lang->block->default['my']['9']['source'] = 'qa';

$lang->block->num      = 'Number';
$lang->block->type     = 'Type';
$lang->block->orderBy  = 'Order';

$lang->block->availableBlocks = new stdclass();

$lang->block->availableBlocks->todo    = 'My todo';
$lang->block->availableBlocks->task    = 'My task';
$lang->block->availableBlocks->bug     = 'My bug';
$lang->block->availableBlocks->case    = 'My case';
$lang->block->availableBlocks->story   = 'My story';
$lang->block->availableBlocks->product = $lang->productCommon;
$lang->block->availableBlocks->project = $lang->projectCommon;
$lang->block->availableBlocks->plan     = 'Plan list';
$lang->block->availableBlocks->release  = 'Release list';
$lang->block->availableBlocks->build    = 'Build list';
$lang->block->availableBlocks->testtask = 'Test build list';

$lang->block->moduleList['product'] = $lang->productCommon;
$lang->block->moduleList['project'] = $lang->projectCommon;
$lang->block->moduleList['qa']      = 'Test';
$lang->block->moduleList['todo']    = 'Todo';

$lang->block->modules['product'] = new stdclass();
$lang->block->modules['product']->availableBlocks = new stdclass();
$lang->block->modules['product']->availableBlocks->list    = $lang->productCommon . ' list';
$lang->block->modules['product']->availableBlocks->story   = 'Story list';
$lang->block->modules['product']->availableBlocks->plan    = 'Plan list';
$lang->block->modules['product']->availableBlocks->release = 'Release list';
$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks = new stdclass();
$lang->block->modules['project']->availableBlocks->list  = $lang->projectCommon . ' list';
$lang->block->modules['project']->availableBlocks->task  = 'Task list';
$lang->block->modules['project']->availableBlocks->build = 'Build list';
$lang->block->modules['qa']      = new stdclass();
$lang->block->modules['qa']->availableBlocks = new stdclass();
$lang->block->modules['qa']->availableBlocks->bug      = 'Bug list';
$lang->block->modules['qa']->availableBlocks->case     = 'Case list';
$lang->block->modules['qa']->availableBlocks->testtask = 'Test build list';
$lang->block->modules['todo'] = new stdclass();
$lang->block->modules['todo']->availableBlocks = new stdclass();
$lang->block->modules['todo']->availableBlocks->list = 'Todo list';

$lang->block->orderByList = new stdclass();

$lang->block->orderByList->task = array();
$lang->block->orderByList->task['id_asc']        = 'ID Increment';
$lang->block->orderByList->task['id_desc']       = 'ID Decreasing';
$lang->block->orderByList->task['pri_asc']       = 'Priority Increment';
$lang->block->orderByList->task['pri_desc']      = 'Priority Decreasing';
$lang->block->orderByList->task['estimate_asc']  = 'Estimate Increment';
$lang->block->orderByList->task['estimate_desc'] = 'Estimate Decreasing';
$lang->block->orderByList->task['status_asc']    = 'Status Increment';
$lang->block->orderByList->task['status_desc']   = 'Status Decreasing';
$lang->block->orderByList->task['deadline_asc']  = 'Deadline Increment';
$lang->block->orderByList->task['deadline_desc'] = 'Deadline Decreasing';

$lang->block->orderByList->bug = array();
$lang->block->orderByList->bug['id_asc']        = 'ID Increment';
$lang->block->orderByList->bug['id_desc']       = 'ID Decreasing';
$lang->block->orderByList->bug['pri_asc']       = 'Priority Increment';
$lang->block->orderByList->bug['pri_desc']      = 'Priority Decreasing';
$lang->block->orderByList->bug['severity_asc']  = 'Severity Increment';
$lang->block->orderByList->bug['severity_desc'] = 'Severity Decreasing';

$lang->block->orderByList->case = array();
$lang->block->orderByList->case['id_asc']        = 'ID Increment';
$lang->block->orderByList->case['id_desc']       = 'ID Decreasing';
$lang->block->orderByList->case['pri_asc']       = 'Priority Increment';
$lang->block->orderByList->case['pri_desc']      = 'Priority Decreasing';

$lang->block->orderByList->story = array();
$lang->block->orderByList->story['id_asc']        = 'ID Increment';
$lang->block->orderByList->story['id_desc']       = 'ID Decreasing';
$lang->block->orderByList->story['pri_asc']       = 'Priority Increment';
$lang->block->orderByList->story['pri_desc']      = 'Priority Decreasing';
$lang->block->orderByList->story['status_asc']    = 'Status Increment';
$lang->block->orderByList->story['status_desc']   = 'Status Decreasing';
$lang->block->orderByList->story['stage_asc']     = 'Stage Increment';
$lang->block->orderByList->story['stage_desc']    = 'Stage Decreasing';

$lang->block->typeList = new stdclass();

$lang->block->typeList->task['assignedTo'] = 'Assigned to';
$lang->block->typeList->task['openedBy']   = 'Opened by';
$lang->block->typeList->task['finishedBy'] = 'Finished by';
$lang->block->typeList->task['closedBy']   = 'Closed by';
$lang->block->typeList->task['canceledBy'] = 'Canceled by';

$lang->block->typeList->bug['assignedTo'] = 'Assigned to';
$lang->block->typeList->bug['openedBy']   = 'Opened by';
$lang->block->typeList->bug['resolvedBy'] = 'Resolved by';
$lang->block->typeList->bug['closedBy']   = 'Closed by';

$lang->block->typeList->case['assigntome'] = 'Assign to me';
$lang->block->typeList->case['openedbyme'] = 'Opened by me';

$lang->block->typeList->story['assignedTo'] = 'Assigned to';
$lang->block->typeList->story['openedBy']   = 'Opened by';
$lang->block->typeList->story['reviewedBy'] = 'Reviewed by';
$lang->block->typeList->story['closedBy']   = 'Closed by';

$lang->block->typeList->product['noclosed'] = 'No closed';
$lang->block->typeList->product['closed']   = 'Closed';
$lang->block->typeList->product['all']      = 'All';

$lang->block->typeList->project['undone']  = 'Undone';
$lang->block->typeList->project['isdoing'] = 'Doing';
$lang->block->typeList->project['all']     = 'All';

$lang->block->typeList->testtask['wait'] = 'Waiting test task';
$lang->block->typeList->testtask['done'] = 'Done test task';
$lang->block->typeList->testtask['all']  = 'All';

$lang->block->modules['product']->moreLinkList = new stdclass();
$lang->block->modules['product']->moreLinkList->list    = 'product|all|product=&status=%s';
$lang->block->modules['product']->moreLinkList->story   = 'my|story|type=%s';
$lang->block->modules['project']->moreLinkList = new stdclass();
$lang->block->modules['project']->moreLinkList->list  = 'project|all|status=%s&project=';
$lang->block->modules['project']->moreLinkList->task  = 'my|task|type=%s';
$lang->block->modules['qa']->moreLinkList = new stdclass();
$lang->block->modules['qa']->moreLinkList->bug      = 'my|bug|type=%s';
$lang->block->modules['qa']->moreLinkList->case     = 'my|testcase|type=%s';
$lang->block->modules['qa']->moreLinkList->testtask = 'my|testtask|type=%s';
$lang->block->modules['todo']->moreLinkList = new stdclass();
$lang->block->modules['todo']->moreLinkList->list = 'my|todo|type=all';
$lang->block->modules['common'] = new stdclass();
$lang->block->modules['common']->moreLinkList = new stdclass();
$lang->block->modules['common']->moreLinkList->dynamic = 'company|dynamic|';

$lang->block->flowchart = array();
$lang->block->flowchart[] = array('Administrator', 'Manage company', 'Add user', 'Manage privilege');
$lang->block->flowchart[] = array('Product manager', 'Create product', 'Manage odules', 'manage plans', 'manage stories', 'Create release');
$lang->block->flowchart[] = array('Project manager', 'Create project', 'Manage team', 'Link products', 'Link stories', 'Create task');
$lang->block->flowchart[] = array('Developer', 'Get tasks/bugs', 'Update status', 'Finish tasks/bugs');
$lang->block->flowchart[] = array('Tester', 'Create testcase', 'Run testcases', 'Create bug', 'Confirm bug', 'Close bug');
