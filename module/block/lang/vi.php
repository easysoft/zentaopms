<?php
/**
 * The vi file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
global $config;
$lang->block->name       = 'Tên';
$lang->block->style      = 'Kiểu';
$lang->block->grid       = 'Vị trí';
$lang->block->color      = 'Màu';
$lang->block->reset      = 'Thiết lập lại giao diện';
$lang->block->story      = 'Story';
$lang->block->investment = 'Investment';
$lang->block->estimate   = 'Estimate';
$lang->block->last       = 'Last';

$lang->block->account = 'Tài khoản';
$lang->block->title   = 'Tiêu đề';
$lang->block->module  = 'Module';
$lang->block->code    = 'Block';
$lang->block->order   = 'Sắp xếp';
$lang->block->height  = 'Chiều cao';
$lang->block->role    = 'Vai trò';

$lang->block->lblModule    = 'Module';
$lang->block->lblBlock     = 'Block';
$lang->block->lblNum       = 'Số';
$lang->block->lblHtml      = 'HTML';
$lang->block->dynamic      = 'Lịch sử';
$lang->block->assignToMe   = 'Todo';
$lang->block->done         = 'Done';
$lang->block->lblFlowchart = 'Biểu đồ';
$lang->block->welcome      = 'Welcome';
$lang->block->lblTesttask  = 'Chi tiết yêu cầu Test';
$lang->block->contribute   = 'Personal Contribution';

$lang->block->leftToday           = 'Việc hôm nay';
$lang->block->myTask              = 'Nhiệm vụ';
$lang->block->myStory             = 'Câu chuyện';
$lang->block->myBug               = 'Bugs';
$lang->block->myExecution         = 'Unclosed ' . $lang->executionCommon . 's';
$lang->block->myProduct           = 'Unclosed ' . $lang->productCommon . 's';
$lang->block->delayed             = 'Tạm ngưng';
$lang->block->noData              = 'Không có dữ liệu trên loại báo cáo này.';
$lang->block->emptyTip            = 'No Data.';
$lang->block->createdTodos        = 'Todos Created';
$lang->block->createdRequirements = 'UR/Epics Created';
$lang->block->createdStories      = 'SR/Stories Created';
$lang->block->finishedTasks       = 'Tasks Finished';
$lang->block->createdBugs         = 'Bugs Created';
$lang->block->resolvedBugs        = 'Bugs Resolved';
$lang->block->createdCases        = 'Cases Created';
$lang->block->createdRisks        = 'Risks Created';
$lang->block->resolvedRisks       = 'Risks Resolved';
$lang->block->createdIssues       = 'Issues Created';
$lang->block->resolvedIssues      = 'Issues Resolved';
$lang->block->createdDocs         = 'Docs Created';
$lang->block->allExecutions       = 'All ' . $lang->executionCommon;
$lang->block->doingExecution      = 'Doning ' . $lang->executionCommon;
$lang->block->finishExecution     = 'Finish ' . $lang->executionCommon;
$lang->block->estimatedHours      = 'Estimated';
$lang->block->consumedHours       = 'Cost';
$lang->block->time                = 'No';
$lang->block->week                = 'Week';
$lang->block->month               = 'Month';
$lang->block->selectProduct       = 'Product selection';
$lang->block->blockTitle          = '%1$s thực %2$s';
$lang->block->remain              = 'Left';

$lang->block->createBlock        = 'Thêm Block';
$lang->block->editBlock          = 'Sửa Block';
$lang->block->ordersSaved        = 'Thứ tự đã được lưu';
$lang->block->confirmRemoveBlock = 'Bạn có muốn gỡ Block này?';
$lang->block->noticeNewBlock     = 'Một giao diện mới đã có sẵn. Bạn có muốn chuyển sang loại mới này không?';
$lang->block->confirmReset       = 'Bạn có muốn thiết lập lại giao diện?';
$lang->block->closeForever       = 'Đóng vĩnh viễn';
$lang->block->confirmClose       = 'Bạn có muốn đóng vĩnh viễn block này? Một khi đã thực hiện, nó không có sẵn nữa. Nó có thể được kích hoạt lại tại Quản trị->Tùy biến.';
$lang->block->remove             = 'Gỡ bỏ';
$lang->block->refresh            = 'Refresh';
$lang->block->nbsp               = ' ';
$lang->block->hidden             = 'Ẩn';
$lang->block->dynamicInfo        = "<span class='timeline-tag'>%s</span> <span class='timeline-text'>%s <em>%s</em> %s <a href='%s' title='%s'>%s</a></span>";
$lang->block->cannotPlaceInLeft  = 'Khối này không thể được đặt ở bên trái.';
$lang->block->cannotPlaceInRight = 'Khối này không thể được đặt ở bên phải.';

$lang->block->productName  = $lang->productCommon . ' Name';
$lang->block->totalStory   = 'Total';
$lang->block->totalBug     = 'Total Bug';
$lang->block->totalRelease = 'Release The Number';

$lang->block->totalInvestment = 'Total investment';
$lang->block->totalPeople     = 'Total number';
$lang->block->spent           = 'Has Been Spent';
$lang->block->budget          = 'Budget';
$lang->block->left            = 'Remain';

$lang->block->summary = new stdclass();
$lang->block->summary->welcome = 'Zentao has been with you for %s days. <strong>Yesterday</strong>, you has finished <a href="' . helper::createLink('my', 'contribute', 'mode=audit') .'" class="text-success">%s</a> reviews, <a href="' . helper::createLink('my', 'contribute', 'mode=task&type=finishedBy') . '" class="text-success">%s</a> tasks , <a href="' . helper::createLink('my', 'contribute', 'mode=bug&type=resolvedBy') . '" class="text-success">%s</a>  bugs were resolved.';

$lang->block->dashboard['default'] = 'Dashboard';
$lang->block->dashboard['my']      = 'My';

$lang->block->default['waterfall']['project']['3']['title']  = 'Plan Gantt Chart';
$lang->block->default['waterfall']['project']['3']['block']  = 'waterfallgantt';
$lang->block->default['waterfall']['project']['3']['source'] = 'project';
$lang->block->default['waterfall']['project']['3']['grid']   = 8;

$lang->block->default['waterfall']['project']['6']['title']  = 'Dynamic';
$lang->block->default['waterfall']['project']['6']['block']  = 'dynamic';
$lang->block->default['waterfall']['project']['6']['grid']   = 4;
$lang->block->default['waterfall']['project']['6']['source'] = 'project';

$lang->block->default['scrum']['project']['1']['title'] = 'Project Overall';
$lang->block->default['scrum']['project']['1']['block'] = 'scrumoverview';
$lang->block->default['scrum']['project']['1']['grid']  = 8;

$lang->block->default['scrum']['project']['2']['title'] = 'Project List';
$lang->block->default['scrum']['project']['2']['block'] = 'scrumlist';
$lang->block->default['scrum']['project']['2']['grid']  = 8;

$lang->block->default['scrum']['project']['2']['params']['type']    = 'undone';
$lang->block->default['scrum']['project']['2']['params']['count']   = '20';
$lang->block->default['scrum']['project']['2']['params']['orderBy'] = 'id_desc';

$lang->block->default['scrum']['project']['3']['title'] = 'Test Version';
$lang->block->default['scrum']['project']['3']['block'] = 'scrumtest';
$lang->block->default['scrum']['project']['3']['grid']  = 8;

$lang->block->default['scrum']['project']['3']['params']['type']    = 'wait';
$lang->block->default['scrum']['project']['3']['params']['count']   = '15';
$lang->block->default['scrum']['project']['3']['params']['orderBy'] = 'id_desc';

$lang->block->default['scrum']['project']['4']['title'] = $lang->executionCommon . ' Overview';
$lang->block->default['scrum']['project']['4']['block'] = 'sprint';
$lang->block->default['scrum']['project']['4']['grid']  = 4;

$lang->block->default['scrum']['project']['5']['title'] = 'Dynamic';
$lang->block->default['scrum']['project']['5']['block'] = 'projectdynamic';
$lang->block->default['scrum']['project']['5']['grid']  = 4;

$lang->block->default['product']['1']['title'] = 'Báo cáo '.$lang->productCommon;
$lang->block->default['product']['1']['block'] = 'statistic';
$lang->block->default['product']['1']['grid']  = 8;

$lang->block->default['product']['1']['params']['type']  = 'all';
$lang->block->default['product']['1']['params']['count'] = '20';

$lang->block->default['product']['2']['title'] = 'Tổng quan '.$lang->productCommon;
$lang->block->default['product']['2']['block'] = 'overview';
$lang->block->default['product']['2']['grid']  = 4;

$lang->block->default['product']['3']['title'] = 'Kích hoạt ' . $lang->productCommon;
$lang->block->default['product']['3']['block'] = 'list';
$lang->block->default['product']['3']['grid']  = 8;

$lang->block->default['product']['3']['params']['count'] = 15;
$lang->block->default['product']['3']['params']['type']  = 'noclosed';

$lang->block->default['product']['4']['title'] = 'Câu chuyện của bạn';
$lang->block->default['product']['4']['block'] = 'story';
$lang->block->default['product']['4']['grid']  = 4;

$lang->block->default['product']['4']['params']['count']   = 15;
$lang->block->default['product']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['product']['4']['params']['type']    = 'assignedTo';

$lang->block->default['execution']['1']['title'] = 'Báo cáo '.$lang->executionCommon;
$lang->block->default['execution']['1']['block'] = 'statistic';
$lang->block->default['execution']['1']['grid']  = 8;

$lang->block->default['execution']['1']['params']['type']  = 'all';
$lang->block->default['execution']['1']['params']['count'] = '20';

$lang->block->default['execution']['2']['title'] = 'Tổng quan '.$lang->executionCommon ;
$lang->block->default['execution']['2']['block'] = 'overview';
$lang->block->default['execution']['2']['grid']  = 4;

$lang->block->default['execution']['3']['title'] = 'Kích hoạt ' . $lang->executionCommon;
$lang->block->default['execution']['3']['block'] = 'list';
$lang->block->default['execution']['3']['grid']  = 8;

$lang->block->default['execution']['3']['params']['count']   = 15;
$lang->block->default['execution']['3']['params']['orderBy'] = 'id_desc';
$lang->block->default['execution']['3']['params']['type']    = 'undone';

$lang->block->default['execution']['4']['title'] = 'Nhiệm vụ';
$lang->block->default['execution']['4']['block'] = 'task';
$lang->block->default['execution']['4']['grid']  = 4;

$lang->block->default['execution']['4']['params']['count']   = 15;
$lang->block->default['execution']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['execution']['4']['params']['type']    = 'assignedTo';

$lang->block->default['execution']['5']['title'] = 'Build List';
$lang->block->default['execution']['5']['block'] = 'build';
$lang->block->default['execution']['5']['grid']  = 8;

$lang->block->default['execution']['5']['params']['count']   = 15;
$lang->block->default['execution']['5']['params']['orderBy'] = 'id_desc';

$lang->block->default['qa']['1']['title'] = 'Báo cáo Test';
$lang->block->default['qa']['1']['block'] = 'statistic';
$lang->block->default['qa']['1']['grid']  = 8;

$lang->block->default['qa']['1']['params']['type']  = 'noclosed';
$lang->block->default['qa']['1']['params']['count'] = '20';

//$lang->block->default['qa']['2']['title'] = 'Testcase Overview';
//$lang->block->default['qa']['2']['block'] = 'overview';
//$lang->block->default['qa']['2']['grid']  = 4;

$lang->block->default['qa']['2']['title'] = 'Bugs của bạn';
$lang->block->default['qa']['2']['block'] = 'bug';
$lang->block->default['qa']['2']['grid']  = 4;

$lang->block->default['qa']['2']['params']['count']   = 15;
$lang->block->default['qa']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['2']['params']['type']    = 'assignedTo';

$lang->block->default['qa']['3']['title'] = 'Tình huống của bạn';
$lang->block->default['qa']['3']['block'] = 'case';
$lang->block->default['qa']['3']['grid']  = 4;

$lang->block->default['qa']['3']['params']['count']   = 15;
$lang->block->default['qa']['3']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['3']['params']['type']    = 'assigntome';

$lang->block->default['qa']['4']['title'] = 'Đang đợi bản dựng';
$lang->block->default['qa']['4']['block'] = 'testtask';
$lang->block->default['qa']['4']['grid']  = 8;

$lang->block->default['qa']['4']['params']['count']   = 15;
$lang->block->default['qa']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['4']['params']['type']    = 'wait';

$lang->block->default['full']['my']['1']['title']  = 'Chào mừng';
$lang->block->default['full']['my']['1']['block']  = 'welcome';
$lang->block->default['full']['my']['1']['grid']   = 8;
$lang->block->default['full']['my']['1']['source'] = '';

$lang->block->default['full']['my']['2']['title']  = 'Lịch sử';
$lang->block->default['full']['my']['2']['block']  = 'dynamic';
$lang->block->default['full']['my']['2']['grid']   = 4;
$lang->block->default['full']['my']['2']['source'] = '';

$lang->block->default['full']['my']['3']['title']           = 'My Todos';
$lang->block->default['full']['my']['3']['block']           = 'list';
$lang->block->default['full']['my']['3']['grid']            = 4;
$lang->block->default['full']['my']['3']['source']          = 'todo';
$lang->block->default['full']['my']['3']['params']['count'] = '20';

$lang->block->default['full']['my']['4']['title']           = 'Project Statistic';
$lang->block->default['full']['my']['4']['block']           = 'statistic';
$lang->block->default['full']['my']['4']['source']          = 'project';
$lang->block->default['full']['my']['4']['grid']            = 8;
$lang->block->default['full']['my']['4']['params']['count'] = '20';

$lang->block->default['full']['my']['5']['title']  = 'Personal Contribution';
$lang->block->default['full']['my']['5']['block']  = 'contribute';
$lang->block->default['full']['my']['5']['source'] = '';
$lang->block->default['full']['my']['5']['grid']   = 4;

$lang->block->default['full']['my']['6']['title']  = 'Recent Projects';
$lang->block->default['full']['my']['6']['block']  = 'recentproject';
$lang->block->default['full']['my']['6']['source'] = 'project';
$lang->block->default['full']['my']['6']['grid']   = 8;

$lang->block->default['full']['my']['7']['title']  = 'Todo';
$lang->block->default['full']['my']['7']['block']  = 'assigntome';
$lang->block->default['full']['my']['7']['source'] = '';
$lang->block->default['full']['my']['7']['grid']   = 8;

$lang->block->default['full']['my']['7']['params']['todoCount']    = '20';
$lang->block->default['full']['my']['7']['params']['taskCount']    = '20';
$lang->block->default['full']['my']['7']['params']['bugCount']     = '20';
$lang->block->default['full']['my']['7']['params']['riskCount']    = '20';
$lang->block->default['full']['my']['7']['params']['issueCount']   = '20';
$lang->block->default['full']['my']['7']['params']['storyCount']   = '20';
$lang->block->default['full']['my']['7']['params']['meetingCount'] = '20';

$lang->block->default['full']['my']['8']['title']  = 'Human Input';
$lang->block->default['full']['my']['8']['source'] = 'project';
$lang->block->default['full']['my']['8']['grid']   = 8;

$lang->block->default['full']['my']['9']['title']  = 'Project List';
$lang->block->default['full']['my']['9']['block']  = 'project';
$lang->block->default['full']['my']['9']['source'] = 'project';
$lang->block->default['full']['my']['9']['grid']   = 8;

$lang->block->default['full']['my']['9']['params']['orderBy'] = 'id_desc';
$lang->block->default['full']['my']['9']['params']['count']   = '15';

$lang->block->count   = 'Số';
$lang->block->type    = 'Loại';
$lang->block->orderBy = 'Order by';

$lang->block->availableBlocks            = new stdclass();
$lang->block->availableBlocks->todo      = 'Lịch của tôi';
$lang->block->availableBlocks->task      = 'Nhiệm vụ';
$lang->block->availableBlocks->bug       = 'Bugs của bạn';
$lang->block->availableBlocks->case      = 'Tình huống của bạn';
$lang->block->availableBlocks->story     = 'Câu chuyện của bạn';
$lang->block->availableBlocks->product   = $lang->productCommon;
$lang->block->availableBlocks->execution = $lang->executionCommon;
$lang->block->availableBlocks->plan      = 'Kế hoạch';
$lang->block->availableBlocks->release   = 'Phát hành';
$lang->block->availableBlocks->build     = 'Bản dựng';
$lang->block->availableBlocks->testtask  = 'Yêu cầu';
$lang->block->availableBlocks->risk      = 'My Risks';
$lang->block->availableBlocks->issue     = 'My Issues';
$lang->block->availableBlocks->meeting   = 'My Meetings';
$lang->block->availableBlocks->feedback  = 'My Feedbacks';

$lang->block->moduleList['project']   = 'Project';
$lang->block->moduleList['product']   = $lang->productCommon;
$lang->block->moduleList['execution'] = $lang->execution->common;
$lang->block->moduleList['qa']        = 'QA';
$lang->block->moduleList['todo']      = 'Việc làm';

$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks['overview']      = "{$lang->projectCommon} Overview";
$lang->block->modules['project']->availableBlocks['recentproject'] = "Recent {$lang->projectCommon}";
$lang->block->modules['project']->availableBlocks['statistic']     = "{$lang->projectCommon} Statistic";
$lang->block->modules['project']->availableBlocks['project']       = "{$lang->projectCommon} List";

$lang->block->modules['scrumproject'] = new stdclass();
$lang->block->modules['scrumproject']->availableBlocks['scrumoverview']  = "{$lang->projectCommon} Overview";
$lang->block->modules['scrumproject']->availableBlocks['scrumlist']      = $lang->executionCommon . ' List';
$lang->block->modules['scrumproject']->availableBlocks['sprint']         = $lang->executionCommon . ' Overview';
$lang->block->modules['scrumproject']->availableBlocks['scrumtest']      = 'Test Version';
$lang->block->modules['scrumproject']->availableBlocks['projectdynamic'] = 'Dynamics';

$lang->block->modules['waterfallproject'] = new stdclass();
$lang->block->modules['waterfallproject']->availableBlocks['waterfallgantt'] = "{$lang->projectCommon} Plan";
$lang->block->modules['waterfallproject']->availableBlocks['projectdynamic'] = 'Dynamics';

$lang->block->modules['agileplus']     = $lang->block->modules['scrumproject'];
$lang->block->modules['waterfallplus'] = $lang->block->modules['waterfallproject'];

$lang->block->modules['product'] = new stdclass();
$lang->block->modules['product']->availableBlocks['statistic'] = $lang->productCommon . ' Report';
$lang->block->modules['product']->availableBlocks['overview']  = $lang->productCommon . ' Overview';
$lang->block->modules['product']->availableBlocks['list']      = $lang->productCommon . ' List';
$lang->block->modules['product']->availableBlocks['story']     = 'Story';
$lang->block->modules['product']->availableBlocks['plan']      = 'Plan';
$lang->block->modules['product']->availableBlocks['release']   = 'Release';

$lang->block->modules['execution'] = new stdclass();
$lang->block->modules['execution']->availableBlocks['statistic'] = $lang->execution->common . ' Statistics';
$lang->block->modules['execution']->availableBlocks['overview']  = $lang->execution->common . ' Overview';
$lang->block->modules['execution']->availableBlocks['list']      = $lang->execution->common . ' List';
$lang->block->modules['execution']->availableBlocks['task']      = 'Task';
$lang->block->modules['execution']->availableBlocks['build']     = 'Build';

$lang->block->modules['qa'] = new stdclass();
$lang->block->modules['qa']->availableBlocks['statistic'] = 'Test Report';
$lang->block->modules['qa']->availableBlocks['bug']       = 'Bug';
$lang->block->modules['qa']->availableBlocks['case']      = 'Case';
$lang->block->modules['qa']->availableBlocks['testtask']  = 'Build';

$lang->block->modules['todo'] = new stdclass();
$lang->block->modules['todo']->availableBlocks['list'] = 'Todo';

$lang->block->modules['doc'] = new stdclass();
$lang->block->modules['doc']->availableBlocks['docstatistic']    = 'Statistic';
$lang->block->modules['doc']->availableBlocks['docdynamic']      = 'Dynamic';
$lang->block->modules['doc']->availableBlocks['docmycollection'] = 'My Collection';
$lang->block->modules['doc']->availableBlocks['docrecentupdate'] = 'Recently Update';
$lang->block->modules['doc']->availableBlocks['docviewlist']     = 'Browse Leaderboard';
if($config->vision == 'rnd') $lang->block->modules['doc']->availaableBlocks['productdoc'] = $lang->productCommon . 'Document';
$lang->block->modules['doc']->availableBlocks['doccollectlist']  = 'Favorite Leaderboard';
$lang->block->modules['doc']->availableBlocks['projectdoc']      = $lang->projectCommon . 'Document';

$lang->block->orderByList = new stdclass();

$lang->block->orderByList->product = array();
$lang->block->orderByList->product['id_asc']      = 'ID sản phẩm tăng dần';
$lang->block->orderByList->product['id_desc']     = 'ID sản phẩm giảm dần';
$lang->block->orderByList->product['status_asc']  = 'Tình trạng sản phẩm tăng dần';
$lang->block->orderByList->product['status_desc'] = 'Tình trạng sản phẩm giảm dần';

$lang->block->orderByList->project = array();
$lang->block->orderByList->project['id_asc']      = 'ID dự án tăng dần';
$lang->block->orderByList->project['id_desc']     = 'ID dự án giảm dần';
$lang->block->orderByList->project['status_asc']  = 'Tình trạng dự án tăng dần';
$lang->block->orderByList->project['status_desc'] = 'Tình trạng dự án giảm dần';

$lang->block->orderByList->execution = array();
$lang->block->orderByList->execution['id_asc']      = 'ID dự án tăng dần';
$lang->block->orderByList->execution['id_desc']     = 'ID dự án giảm dần';
$lang->block->orderByList->execution['status_asc']  = 'Tình trạng dự án tăng dần';
$lang->block->orderByList->execution['status_desc'] = 'Tình trạng dự án giảm dần';

$lang->block->orderByList->task = array();
$lang->block->orderByList->task['id_asc']        = 'ID nhiệm vụ tăng dần';
$lang->block->orderByList->task['id_desc']       = 'ID nhiệm vụ giảm dần';
$lang->block->orderByList->task['pri_asc']       = 'Mức độ nhiệm vụ tăng dần';
$lang->block->orderByList->task['pri_desc']      = 'Mức độ nhiệm vụ giảm dần';
$lang->block->orderByList->task['estimate_asc']  = 'Dự kiến nhiệm vụ tăng dần';
$lang->block->orderByList->task['estimate_desc'] = 'Dự kiến nhiệm vụ giảm dần';
$lang->block->orderByList->task['status_asc']    = 'Tình trạng nhiệm vụ tăng dần';
$lang->block->orderByList->task['status_desc']   = 'Tình trạng nhiệm vụ giảm dần';
$lang->block->orderByList->task['deadline_asc']  = 'Hạn chót nhiệm vụ tăng dần';
$lang->block->orderByList->task['deadline_desc'] = 'Hạn chót nhiệm vụ giảm dần';

$lang->block->orderByList->bug = array();
$lang->block->orderByList->bug['id_asc']        = 'ID Bug tăng dần';
$lang->block->orderByList->bug['id_desc']       = 'ID Bug giảm dần';
$lang->block->orderByList->bug['pri_asc']       = 'Ưu tiên Bug tăng dần';
$lang->block->orderByList->bug['pri_desc']      = 'Ưu tiên Bug giảm dần';
$lang->block->orderByList->bug['severity_asc']  = 'Mức độ Bug tăng dần';
$lang->block->orderByList->bug['severity_desc'] = 'Mức độ Bug giảm dần';

$lang->block->orderByList->case = array();
$lang->block->orderByList->case['id_asc']   = 'ID tình huống tăng dần';
$lang->block->orderByList->case['id_desc']  = 'ID tình huống giảm dần';
$lang->block->orderByList->case['pri_asc']  = 'Ưu tiên tình huống tăng dần';
$lang->block->orderByList->case['pri_desc'] = 'Ưu tiên tình huống giảm dần';

$lang->block->orderByList->story = array();
$lang->block->orderByList->story['id_asc']      = 'ID câu chuyện tăng dần';
$lang->block->orderByList->story['id_desc']     = 'ID câu chuyện giảm dần';
$lang->block->orderByList->story['pri_asc']     = 'Ưu tiên câu chuyện tăng dần';
$lang->block->orderByList->story['pri_desc']    = 'Ưu tiên câu chuyện giảm dần';
$lang->block->orderByList->story['status_asc']  = 'Tình trạng câu chuyện tăng dần';
$lang->block->orderByList->story['status_desc'] = 'Tình trạng câu chuyện giảm dần';
$lang->block->orderByList->story['stage_asc']   = 'Giai đoạn câu chuyện tăng dần';
$lang->block->orderByList->story['stage_desc']  = 'Giai đoạn câu chuyện giảm dần';

$lang->block->todoCount     = 'Việc làm';
$lang->block->taskCount     = 'Nhiệm vụ';
$lang->block->bugCount      = 'Bug';
$lang->block->riskCount     = 'Risk';
$lang->block->issueCount    = 'Issues';
$lang->block->storyCount    = 'Stories';
$lang->block->meetingCount  = 'Meetings';
$lang->block->feedbackCount = 'Feedbacks';

$lang->block->typeList = new stdclass();

$lang->block->typeList->task['assignedTo'] = 'Giao cho bạn';
$lang->block->typeList->task['openedBy']   = 'Tạo bởi bạn';
$lang->block->typeList->task['finishedBy'] = 'Kết thúc bởi bạn';
$lang->block->typeList->task['closedBy']   = 'Đóng bởi bạn';
$lang->block->typeList->task['canceledBy'] = 'Hủy bởi bạn';

$lang->block->typeList->bug['assignedTo'] = 'Giao cho bạn';
$lang->block->typeList->bug['openedBy']   = 'Tạo bởi bạn';
$lang->block->typeList->bug['resolvedBy'] = 'Giải quyêt bởi bạn';
$lang->block->typeList->bug['closedBy']   = 'Đóng bởi bạn';

$lang->block->typeList->case['assigntome'] = 'Giao cho bạn';
$lang->block->typeList->case['openedbyme'] = 'Tạo bởi bạn';

$lang->block->typeList->story['assignedTo'] = 'Giao cho bạn';
$lang->block->typeList->story['openedBy']   = 'Tạo bởi bạn';
$lang->block->typeList->story['reviewedBy'] = 'Duyệt bởi bạn';
$lang->block->typeList->story['closedBy']   = 'ClosedByMe' ;

$lang->block->typeList->product['noclosed'] = 'Mở';
$lang->block->typeList->product['closed']   = 'Đã đóng';
$lang->block->typeList->product['all']      = 'Tất cả';
$lang->block->typeList->product['involved'] = 'Liên đới';

$lang->block->typeList->project['undone']   = 'Unfinished';
$lang->block->typeList->project['doing']    = 'Ongoing';
$lang->block->typeList->project['all']      = 'All';
$lang->block->typeList->project['involved'] = 'Involved';

$lang->block->typeList->projectAll['all']      = 'All';
$lang->block->typeList->projectAll['undone']   = 'Undone';
$lang->block->typeList->projectAll['wait']     = 'Wait';
$lang->block->typeList->projectAll['doing']    = 'Doing';
$lang->block->typeList->projectAll['exceeded'] = 'Exceeded';
$lang->block->typeList->projectAll['risky']    = 'Risk';
$lang->block->typeList->projectAll['more']     = 'More';

$lang->block->typeList->execution['undone']   = 'Chưa kết thúc';
$lang->block->typeList->execution['doing']    = 'Đang làm';
$lang->block->typeList->execution['all']      = 'Tất cả';
$lang->block->typeList->execution['involved'] = 'Liên đới';

$lang->block->typeList->scrum['undone']   = 'Unfinished';
$lang->block->typeList->scrum['doing']    = 'Ongoing';
$lang->block->typeList->scrum['all']      = 'All';
$lang->block->typeList->scrum['involved'] = 'Involved';

$lang->block->typeList->testtask['wait']    = 'Đang đợi';
$lang->block->typeList->testtask['doing']   = 'Đang làm';
$lang->block->typeList->testtask['blocked'] = 'Bị khóa';
$lang->block->typeList->testtask['done']    = 'Hoàn thành';
$lang->block->typeList->testtask['all']     = 'Tất cả';

$lang->block->typeList->risk['all']      = 'All';
$lang->block->typeList->risk['active']   = 'Active';
$lang->block->typeList->risk['assignTo'] = 'Assign To';
$lang->block->typeList->risk['assignBy'] = 'Assign By';
$lang->block->typeList->risk['closed']   = 'Closed';
$lang->block->typeList->risk['hangup']   = 'Hangup';
$lang->block->typeList->risk['canceled'] = 'Canceled';

$lang->block->typeList->issue['all']      = 'All';
$lang->block->typeList->issue['open']     = 'Open';
$lang->block->typeList->issue['assignto'] = 'Assign To';
$lang->block->typeList->issue['assignby'] = 'Assign By';
$lang->block->typeList->issue['closed']   = 'Closed';
$lang->block->typeList->issue['resolved'] = 'Resolved';
$lang->block->typeList->issue['canceled'] = 'Canceled';

$lang->block->modules['project']->moreLinkList = new stdclass();
$lang->block->modules['project']->moreLinkList->recentproject  = 'project|browse|';
$lang->block->modules['project']->moreLinkList->statistic      = 'project|browse|';
$lang->block->modules['project']->moreLinkList->project        = 'project|browse|';
$lang->block->modules['project']->moreLinkList->cmmireport     = 'weekly|index|';
$lang->block->modules['project']->moreLinkList->cmmiestimate   = 'workestimation|index|';
$lang->block->modules['project']->moreLinkList->cmmiissue      = 'issue|browse|';
$lang->block->modules['project']->moreLinkList->cmmirisk       = 'risk|browse|';
$lang->block->modules['project']->moreLinkList->scrumlist      = 'project|execution|';
$lang->block->modules['project']->moreLinkList->scrumtest      = 'testtask|browse|';
$lang->block->modules['project']->moreLinkList->scrumproduct   = 'product|all|';
$lang->block->modules['project']->moreLinkList->sprint         = 'project|execution|';
$lang->block->modules['project']->moreLinkList->projectdynamic = 'project|dynamic|';

$lang->block->modules['product']->moreLinkList        = new stdclass();
$lang->block->modules['product']->moreLinkList->list  = 'product|all|';
$lang->block->modules['product']->moreLinkList->story = 'my|story|type=%s';

$lang->block->modules['execution']->moreLinkList       = new stdclass();
$lang->block->modules['execution']->moreLinkList->list = 'execution|all|status=%s&executionID=';
$lang->block->modules['execution']->moreLinkList->task = 'my|task|type=%s';

$lang->block->modules['qa']->moreLinkList           = new stdclass();
$lang->block->modules['qa']->moreLinkList->bug      = 'my|bug|type=%s';
$lang->block->modules['qa']->moreLinkList->case     = 'my|testcase|type=%s';
$lang->block->modules['qa']->moreLinkList->testtask = 'testtask|browse|type=%s';

$lang->block->modules['todo']->moreLinkList       = new stdclass();
$lang->block->modules['todo']->moreLinkList->list = 'my|todo|type=all';

$lang->block->modules['common']                        = new stdclass();
$lang->block->modules['common']->moreLinkList          = new stdclass();
$lang->block->modules['common']->moreLinkList->dynamic = 'company|dynamic|';

$lang->block->welcomeList['06:00'] = 'Chào buổi sáng,</br> <strong>%s</strong>';
$lang->block->welcomeList['11:30'] = 'Một ngày vui, </br><strong>%s</strong>';
$lang->block->welcomeList['13:30'] = 'Chào buổi chiều, </br> <strong>%s</strong>';
$lang->block->welcomeList['19:00'] = 'Chào buổi tối,</br> <strong>%s</strong>';

$lang->block->gridOptions[8] = 'Trái';
$lang->block->gridOptions[4] = 'Phải';

$lang->block->flowchart              = array();
$lang->block->flowchart['admin']     = array('Quản trị viên', 'Phòng/ban', 'Người dùng', 'Phân quyền');
$lang->block->flowchart['product']   = array($lang->productCommon, $lang->productCommon, 'Modules', 'Kế hoạch', 'Câu chuyện', 'Phát hành');
$lang->block->flowchart['execution'] = array('Scrum Master',$lang->executionCommon, 'Đội nhóm', $lang->productCommon, 'Câu chuyện', 'Nhiệm vụ');
$lang->block->flowchart['dev']       = array('Dev Team', 'Nhiệm vụ/Bugs', 'Tình trạng');
$lang->block->flowchart['tester']    = array('QA Team', 'Tình huống', 'Báo cáo Bugs', 'Kiểm tra Bugs', 'Đóng Bugs');

$lang->block->productstatistic = new stdclass();
$lang->block->productstatistic->totalStory      = 'Total Story';
$lang->block->productstatistic->closed          = 'Closed';
$lang->block->productstatistic->notClosed       = 'Not Closed';
$lang->block->productstatistic->storyStatistics = 'Story Statistics';
$lang->block->productstatistic->monthDone       = 'Completed this month <span class="text-success font-bold">%s</span>';
$lang->block->productstatistic->monthOpened     = 'Added this month <span class="text-black font-bold">%s</span>';
$lang->block->productstatistic->news            = 'Latest product advancements';
$lang->block->productstatistic->newPlan         = 'Latest Plan';
$lang->block->productstatistic->newExecution    = 'Latest Execution';
$lang->block->productstatistic->newRelease      = 'Latest Release';
$lang->block->productstatistic->deliveryRate    = 'Delivery Rate';

$lang->block->projectoverview = new stdclass();
$lang->block->projectoverview->totalProject  = 'Total';
$lang->block->projectoverview->thisYear      = 'This Year';
$lang->block->projectoverview->lastThreeYear = 'Done in last three years';

$lang->block->productoverview = new stdclass();
$lang->block->productoverview->totalProductCount       = 'Total Product Count';
$lang->block->productoverview->productReleasedThisYear = 'Number Of Releases This Year';
$lang->block->productoverview->releaseCount            = 'Total Release Count';

$lang->block->productlist = new stdclass();
$lang->block->productlist->unclosedFeedback  = 'Number Of Feedback Not Closed';
$lang->block->productlist->activatedStory    = 'Activate Requirements';
$lang->block->productlist->storyCompleteRate = 'Requirement Completion Rate';
$lang->block->productlist->activatedBug      = 'Activate Bugs';
