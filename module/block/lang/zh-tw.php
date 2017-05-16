<?php
/**
 * The zh-tw file of crm block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block 
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
$lang->block = new stdclass();
$lang->block->common = '區塊';
$lang->block->name   = '區塊名稱';
$lang->block->style  = '外觀';
$lang->block->grid   = '寬度';
$lang->block->color  = '顏色';

$lang->block->lblModule    = '模組';
$lang->block->lblBlock     = '區塊';
$lang->block->lblNum       = '條數';
$lang->block->lblHtml      = 'HTML內容';
$lang->block->dynamic      = '最新動態';
$lang->block->lblFlowchart = '流程圖';

$lang->block->params = new stdclass();
$lang->block->params->name  = '參數名稱';
$lang->block->params->value = '參數值';

$lang->block->createBlock        = '添加區塊';
$lang->block->editBlock          = '編輯區塊';
$lang->block->ordersSaved        = '排序已保存';
$lang->block->confirmRemoveBlock = '確定移除區塊【{0}】嗎？';
$lang->block->closeForever       = '永久關閉';
$lang->block->confirmClose       = '確定永久關閉該區塊嗎？閉後所有人都將無法使用該區塊，可以在後台自定義中打開';
$lang->block->remove             = '移除';
$lang->block->refresh            = '刷新';
$lang->block->hidden             = '隱藏';
$lang->block->dynamicInfo        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>。";

$lang->block->default['product']['1']['title'] = '未關閉的' . $lang->productCommon;
$lang->block->default['product']['1']['block'] = 'list';
$lang->block->default['product']['1']['grid']  = 8;

$lang->block->default['product']['1']['params']['num']  = 15;
$lang->block->default['product']['1']['params']['type'] = 'noclosed';

$lang->block->default['product']['2']['title'] = '指派給我的需求';
$lang->block->default['product']['2']['block'] = 'story';
$lang->block->default['product']['2']['grid']  = 4;

$lang->block->default['product']['2']['params']['num']     = 15;
$lang->block->default['product']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['product']['2']['params']['type']    = 'assignedTo';

$lang->block->default['project']['1']['title'] = '進行中的' . $lang->projectCommon;
$lang->block->default['project']['1']['block'] = 'list';
$lang->block->default['project']['1']['grid']  = 8;

$lang->block->default['project']['1']['params']['num']     = 15;
$lang->block->default['project']['1']['params']['orderBy'] = 'id_desc';
$lang->block->default['project']['1']['params']['type']    = 'undone';

$lang->block->default['project']['2']['title'] = '指派給我的任務';
$lang->block->default['project']['2']['block'] = 'task';
$lang->block->default['project']['2']['grid']  = 4;

$lang->block->default['project']['2']['params']['num']     = 15;
$lang->block->default['project']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['project']['2']['params']['type']    = 'assignedTo';

$lang->block->default['qa']['1']['title'] = '指派給我的Bug';
$lang->block->default['qa']['1']['block'] = 'bug';
$lang->block->default['qa']['1']['grid']  = 4;

$lang->block->default['qa']['1']['params']['num']     = 15;
$lang->block->default['qa']['1']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['1']['params']['type']    = 'assignedTo';

$lang->block->default['qa']['2']['title'] = '指派給我的用例';
$lang->block->default['qa']['2']['block'] = 'case';
$lang->block->default['qa']['2']['grid']  = 4;

$lang->block->default['qa']['2']['params']['num']     = 15;
$lang->block->default['qa']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['2']['params']['type']    = 'assigntome';

$lang->block->default['qa']['3']['title'] = '待測版本列表';
$lang->block->default['qa']['3']['block'] = 'testtask';
$lang->block->default['qa']['3']['grid']  = 4;

$lang->block->default['qa']['3']['params']['num']     = 15;
$lang->block->default['qa']['3']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['3']['params']['type']    = 'wait';

$lang->block->default['full']['my']['1']['title']  = '流程圖';
$lang->block->default['full']['my']['1']['block']  = 'flowchart';
$lang->block->default['full']['my']['1']['grid']   = 8;
$lang->block->default['full']['my']['1']['source'] = '';
$lang->block->default['full']['my']['2']['title']  = '最新動態';
$lang->block->default['full']['my']['2']['block']  = 'dynamic';
$lang->block->default['full']['my']['2']['grid']   = 4;
$lang->block->default['full']['my']['2']['source'] = '';
$lang->block->default['full']['my']['3'] = $lang->block->default['project']['1'];
$lang->block->default['full']['my']['3']['source'] = 'project';
$lang->block->default['full']['my']['4']['title']  = '我的待辦';
$lang->block->default['full']['my']['4']['block']  = 'list';
$lang->block->default['full']['my']['4']['grid']   = 4;
$lang->block->default['full']['my']['4']['source'] = 'todo';
$lang->block->default['full']['my']['4']['params']['num'] = '20';
$lang->block->default['full']['my']['5'] = $lang->block->default['product']['1'];
$lang->block->default['full']['my']['5']['source'] = 'product';
$lang->block->default['full']['my']['6'] = $lang->block->default['project']['2'];
$lang->block->default['full']['my']['6']['source'] = 'project';
$lang->block->default['full']['my']['7'] = $lang->block->default['qa']['1'];
$lang->block->default['full']['my']['7']['source'] = 'qa';
$lang->block->default['full']['my']['8'] = $lang->block->default['product']['2'];
$lang->block->default['full']['my']['8']['source'] = 'product';
$lang->block->default['full']['my']['9'] = $lang->block->default['qa']['2'];
$lang->block->default['full']['my']['9']['source'] = 'qa';

$lang->block->default['onlyTest']['my']['1'] = $lang->block->default['qa']['1'];
$lang->block->default['onlyTest']['my']['1']['source'] = 'qa';
$lang->block->default['onlyTest']['my']['1']['grid']   = '8';
$lang->block->default['onlyTest']['my']['2']['title']  = '最新動態';
$lang->block->default['onlyTest']['my']['2']['block']  = 'dynamic';
$lang->block->default['onlyTest']['my']['2']['grid']   = 4;
$lang->block->default['onlyTest']['my']['2']['source'] = '';
$lang->block->default['onlyTest']['my']['3']['title']  = '我的待辦';
$lang->block->default['onlyTest']['my']['3']['block']  = 'list';
$lang->block->default['onlyTest']['my']['3']['grid']   = 6;
$lang->block->default['onlyTest']['my']['3']['source'] = 'todo';
$lang->block->default['onlyTest']['my']['3']['params']['num'] = '20';
$lang->block->default['onlyTest']['my']['4'] = $lang->block->default['qa']['2'];
$lang->block->default['onlyTest']['my']['4']['source'] = 'qa';
$lang->block->default['onlyTest']['my']['4']['grid']   = '6';

$lang->block->default['onlyStory']['my']['1'] = $lang->block->default['product']['1'];
$lang->block->default['onlyStory']['my']['1']['source'] = 'product';
$lang->block->default['onlyStory']['my']['1']['grid']   = '8';
$lang->block->default['onlyStory']['my']['2']['title']  = '最新動態';
$lang->block->default['onlyStory']['my']['2']['block']  = 'dynamic';
$lang->block->default['onlyStory']['my']['2']['grid']   = 4;
$lang->block->default['onlyStory']['my']['2']['source'] = '';
$lang->block->default['onlyStory']['my']['3']['title']  = '我的待辦';
$lang->block->default['onlyStory']['my']['3']['block']  = 'list';
$lang->block->default['onlyStory']['my']['3']['grid']   = 6;
$lang->block->default['onlyStory']['my']['3']['source'] = 'todo';
$lang->block->default['onlyStory']['my']['3']['params']['num'] = '20';
$lang->block->default['onlyStory']['my']['4'] = $lang->block->default['product']['2'];
$lang->block->default['onlyStory']['my']['4']['source'] = 'product';
$lang->block->default['onlyStory']['my']['4']['grid']   = '4';

$lang->block->default['onlyTask']['my']['1'] = $lang->block->default['project']['1'];
$lang->block->default['onlyTask']['my']['1']['source'] = 'project';
$lang->block->default['onlyTask']['my']['1']['grid']   = '8';
$lang->block->default['onlyTask']['my']['2']['title']  = '最新動態';
$lang->block->default['onlyTask']['my']['2']['block']  = 'dynamic';
$lang->block->default['onlyTask']['my']['2']['grid']   = 4;
$lang->block->default['onlyTask']['my']['2']['source'] = '';
$lang->block->default['onlyTask']['my']['3']['title']  = '我的待辦';
$lang->block->default['onlyTask']['my']['3']['block']  = 'list';
$lang->block->default['onlyTask']['my']['3']['grid']   = 6;
$lang->block->default['onlyTask']['my']['3']['source'] = 'todo';
$lang->block->default['onlyTask']['my']['3']['params']['num'] = '20';
$lang->block->default['onlyTask']['my']['4'] = $lang->block->default['project']['2'];
$lang->block->default['onlyTask']['my']['4']['source'] = 'project';
$lang->block->default['onlyTask']['my']['4']['grid']   = 6;

$lang->block->num      = '數量';
$lang->block->type     = '類型';
$lang->block->orderBy  = '排序';

$lang->block->availableBlocks = new stdclass();

$lang->block->availableBlocks->todo     = '我的待辦';
$lang->block->availableBlocks->task     = '我的任務';
$lang->block->availableBlocks->bug      = '我的Bug';
$lang->block->availableBlocks->case     = '我的用例';
$lang->block->availableBlocks->story    = '我的需求';
$lang->block->availableBlocks->product  = $lang->productCommon . '列表';
$lang->block->availableBlocks->project  = $lang->projectCommon . '列表';
$lang->block->availableBlocks->plan     = '計劃列表';
$lang->block->availableBlocks->release  = '發佈列表';
$lang->block->availableBlocks->build    = '版本列表';
$lang->block->availableBlocks->testtask = '測試版本列表';

$lang->block->moduleList['product'] = $lang->productCommon;
$lang->block->moduleList['project'] = $lang->projectCommon;
$lang->block->moduleList['qa']      = '測試';
$lang->block->moduleList['todo']    = '待辦';

$lang->block->modules['product'] = new stdclass();
$lang->block->modules['product']->availableBlocks = new stdclass();
$lang->block->modules['product']->availableBlocks->list    = $lang->productCommon . '列表';
$lang->block->modules['product']->availableBlocks->story   = '需求列表';
$lang->block->modules['product']->availableBlocks->plan    = '計劃列表';
$lang->block->modules['product']->availableBlocks->release = '發佈列表';
$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks = new stdclass();
$lang->block->modules['project']->availableBlocks->list  = $lang->projectCommon . '列表';
$lang->block->modules['project']->availableBlocks->task  = '任務列表';
$lang->block->modules['project']->availableBlocks->build = '版本列表';
$lang->block->modules['qa']      = new stdclass();
$lang->block->modules['qa']->availableBlocks = new stdclass();
$lang->block->modules['qa']->availableBlocks->bug      = 'Bug列表';
$lang->block->modules['qa']->availableBlocks->case     = '用例列表';
$lang->block->modules['qa']->availableBlocks->testtask = '版本列表';
$lang->block->modules['todo'] = new stdclass();
$lang->block->modules['todo']->availableBlocks = new stdclass();
$lang->block->modules['todo']->availableBlocks->list = '待辦列表';

$lang->block->orderByList = new stdclass();

$lang->block->orderByList->task = array();
$lang->block->orderByList->task['id_asc']        = 'ID 遞增';
$lang->block->orderByList->task['id_desc']       = 'ID 遞減';
$lang->block->orderByList->task['pri_asc']       = '優先順序遞增';
$lang->block->orderByList->task['pri_desc']      = '優先順序遞減';
$lang->block->orderByList->task['estimate_asc']  = '預計時間遞增';
$lang->block->orderByList->task['estimate_desc'] = '預計時間遞減';
$lang->block->orderByList->task['status_asc']    = '狀態正序';
$lang->block->orderByList->task['status_desc']   = '狀態倒序';
$lang->block->orderByList->task['deadline_asc']  = '截止日期遞增';
$lang->block->orderByList->task['deadline_desc'] = '截止日期遞減';

$lang->block->orderByList->bug = array();
$lang->block->orderByList->bug['id_asc']        = 'ID 遞增';
$lang->block->orderByList->bug['id_desc']       = 'ID 遞減';
$lang->block->orderByList->bug['pri_asc']       = '優先順序遞增';
$lang->block->orderByList->bug['pri_desc']      = '優先順序遞減';
$lang->block->orderByList->bug['severity_asc']  = '級別遞增';
$lang->block->orderByList->bug['severity_desc'] = '級別遞減';

$lang->block->orderByList->case = array();
$lang->block->orderByList->case['id_asc']        = 'ID 遞增';
$lang->block->orderByList->case['id_desc']       = 'ID 遞減';
$lang->block->orderByList->case['pri_asc']       = '優先順序遞增';
$lang->block->orderByList->case['pri_desc']      = '優先順序遞減';

$lang->block->orderByList->story = array();
$lang->block->orderByList->story['id_asc']        = 'ID 遞增';
$lang->block->orderByList->story['id_desc']       = 'ID 遞減';
$lang->block->orderByList->story['pri_asc']       = '優先順序遞增';
$lang->block->orderByList->story['pri_desc']      = '優先順序遞減';
$lang->block->orderByList->story['status_asc']    = '狀態正序';
$lang->block->orderByList->story['status_desc']   = '狀態倒序';
$lang->block->orderByList->story['stage_asc']     = '階段正序';
$lang->block->orderByList->story['stage_desc']    = '階段倒序';

$lang->block->typeList = new stdclass();

$lang->block->typeList->task['assignedTo'] = '指派給我';
$lang->block->typeList->task['openedBy']   = '由我創建';
$lang->block->typeList->task['finishedBy'] = '由我完成';
$lang->block->typeList->task['closedBy']   = '由我關閉';
$lang->block->typeList->task['canceledBy'] = '由我取消';

$lang->block->typeList->bug['assignedTo'] = '指派給我';
$lang->block->typeList->bug['openedBy']   = '由我創建';
$lang->block->typeList->bug['resolvedBy'] = '由我解決';
$lang->block->typeList->bug['closedBy']   = '由我關閉';

$lang->block->typeList->case['assigntome'] = '指派給我';
$lang->block->typeList->case['openedbyme'] = '由我創建';

$lang->block->typeList->story['assignedTo'] = '指派給我';
$lang->block->typeList->story['openedBy']   = '由我創建';
$lang->block->typeList->story['reviewedBy'] = '由我評審';
$lang->block->typeList->story['closedBy']   = '由我關閉';

$lang->block->typeList->product['noclosed'] = '未關閉';
$lang->block->typeList->product['closed']   = '已關閉';
$lang->block->typeList->product['all']      = '全部';

$lang->block->typeList->project['undone']  = '未完成';
$lang->block->typeList->project['isdoing'] = '進行中';
$lang->block->typeList->project['all']     = '全部';

$lang->block->typeList->testtask['wait']    = '待測版本';
$lang->block->typeList->testtask['doing']   = '測試中版本';
$lang->block->typeList->testtask['blocked'] = '阻塞版本';
$lang->block->typeList->testtask['done']    = '已測版本';
$lang->block->typeList->testtask['all']     = '全部';

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

$lang->block->flowchart   = array();
$lang->block->flowchart[] = array('管理員',   '維護公司', '添加用戶', '維護權限');
$lang->block->flowchart[] = array($lang->productCommon . '經理', '創建' . $lang->productCommon, '維護模組', '維護計劃', '維護需求', '創建發佈');
$lang->block->flowchart[] = array($lang->projectCommon . '經理', '創建' . $lang->projectCommon, '維護團隊', '關聯' . $lang->productCommon, '關聯需求', '分解任務');
$lang->block->flowchart[] = array('研發人員', '領取任務和Bug', '更新狀態', '完成任務和Bug');
$lang->block->flowchart[] = array('測試人員', '撰寫用例', '執行用例', '提交Bug', '驗證Bug', '關閉Bug');
