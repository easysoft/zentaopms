<?php
/**
 * The en file of crm block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Yidong Wang <yidong@cnezsoft.com>
 * @package  block 
 * @version  $Id$
 * @link  http://www.zentao.net
 */
$lang->block = new stdclass();
$lang->block->common = 'Block';
$lang->block->name   = 'Tên';
$lang->block->style  = 'Kiểu';
$lang->block->grid   = 'Vị trí';
$lang->block->color  = 'Màu';
$lang->block->reset  = 'Thiết lập lại giao diện';

$lang->block->account = 'Tài khoản';
$lang->block->module  = 'Module';
$lang->block->title   = 'Tiêu đề';
$lang->block->source  = 'Nguồn Module';
$lang->block->block   = 'Nguồn Block';
$lang->block->order   = 'Sắp xếp';
$lang->block->height  = 'Chiều cao';
$lang->block->role    = 'Vai trò';

$lang->block->lblModule    = 'Module';
$lang->block->lblBlock     = 'Block';
$lang->block->lblNum       = 'Số';
$lang->block->lblHtml      = 'HTML';
$lang->block->dynamic      = 'Lịch sử';
$lang->block->assignToMe   = 'Giao cho bạn';
$lang->block->lblFlowchart = 'Biểu đồ';
$lang->block->welcome      = 'Welcome';
$lang->block->lblTesttask  = 'Chi tiết yêu cầu Test';

$lang->block->leftToday = 'Việc hôm nay';
$lang->block->myTask    = 'Nhiệm vụ';
$lang->block->myStory   = 'Câu chuyện';
$lang->block->myBug     = 'Bugs';
$lang->block->myProject = '' . $lang->projectCommon;
$lang->block->myProduct = '' . $lang->productCommon;
$lang->block->delayed   = 'Tạm ngưng';
$lang->block->noData    = 'Không có dữ liệu trên loại báo cáo này.';
$lang->block->emptyTip  = 'Không có dữ liệu.';

$lang->block->params = new stdclass();
$lang->block->params->name  = 'Tên';
$lang->block->params->value = 'Giá trị';

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

$lang->block->default['product']['1']['title'] = 'Báo cáo '.$lang->productCommon;
$lang->block->default['product']['1']['block'] = 'statistic';
$lang->block->default['product']['1']['grid']  = 8;

$lang->block->default['product']['1']['params']['type'] = 'all';
$lang->block->default['product']['1']['params']['num']  = '20';

$lang->block->default['product']['2']['title'] = 'Tổng quan '.$lang->productCommon;
$lang->block->default['product']['2']['block'] = 'overview';
$lang->block->default['product']['2']['grid']  = 4;

$lang->block->default['product']['3']['title'] = 'Kích hoạt ' . $lang->productCommon;
$lang->block->default['product']['3']['block'] = 'list';
$lang->block->default['product']['3']['grid']  = 8;

$lang->block->default['product']['3']['params']['num']  = 15;
$lang->block->default['product']['3']['params']['type'] = 'noclosed';

$lang->block->default['product']['4']['title'] = 'Câu chuyện của bạn';
$lang->block->default['product']['4']['block'] = 'story';
$lang->block->default['product']['4']['grid']  = 4;

$lang->block->default['product']['4']['params']['num']  = 15;
$lang->block->default['product']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['product']['4']['params']['type'] = 'assignedTo';

$lang->block->default['project']['1']['title'] = 'Báo cáo '.$lang->projectCommon;
$lang->block->default['project']['1']['block'] = 'statistic';
$lang->block->default['project']['1']['grid']  = 8;

$lang->block->default['project']['1']['params']['type'] = 'all';
$lang->block->default['project']['1']['params']['num']  = '20';

$lang->block->default['project']['2']['title'] = 'Tổng quan '.$lang->projectCommon ;
$lang->block->default['project']['2']['block'] = 'overview';
$lang->block->default['project']['2']['grid']  = 4;

$lang->block->default['project']['3']['title'] = 'Kích hoạt ' . $lang->projectCommon;
$lang->block->default['project']['3']['block'] = 'list';
$lang->block->default['project']['3']['grid']  = 8;

$lang->block->default['project']['3']['params']['num']  = 15;
$lang->block->default['project']['3']['params']['orderBy'] = 'id_desc';
$lang->block->default['project']['3']['params']['type'] = 'undone';

$lang->block->default['project']['4']['title'] = 'Nhiệm vụ';
$lang->block->default['project']['4']['block'] = 'task';
$lang->block->default['project']['4']['grid']  = 4;

$lang->block->default['project']['4']['params']['num']  = 15;
$lang->block->default['project']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['project']['4']['params']['type'] = 'assignedTo';

$lang->block->default['qa']['1']['title'] = 'Báo cáo Test';
$lang->block->default['qa']['1']['block'] = 'statistic';
$lang->block->default['qa']['1']['grid']  = 8;

$lang->block->default['qa']['1']['params']['type'] = 'noclosed';
$lang->block->default['qa']['1']['params']['num']  = '20';

//$lang->block->default['qa']['2']['title'] = 'Testcase Overview';
//$lang->block->default['qa']['2']['block'] = 'overview';
//$lang->block->default['qa']['2']['grid']  = 4;

$lang->block->default['qa']['2']['title'] = 'Bugs của bạn';
$lang->block->default['qa']['2']['block'] = 'bug';
$lang->block->default['qa']['2']['grid']  = 4;

$lang->block->default['qa']['2']['params']['num']     = 15;
$lang->block->default['qa']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['2']['params']['type']    = 'assignedTo';

$lang->block->default['qa']['3']['title'] = 'Tình huống của bạn';
$lang->block->default['qa']['3']['block'] = 'case';
$lang->block->default['qa']['3']['grid']  = 4;

$lang->block->default['qa']['3']['params']['num']     = 15;
$lang->block->default['qa']['3']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['3']['params']['type']    = 'assigntome';

$lang->block->default['qa']['4']['title'] = 'Đang đợi bản dựng';
$lang->block->default['qa']['4']['block'] = 'testtask';
$lang->block->default['qa']['4']['grid']  = 4;

$lang->block->default['qa']['4']['params']['num']     = 15;
$lang->block->default['qa']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['4']['params']['type']    = 'wait';

$lang->block->default['full']['my']['1']['title']         = 'Chào mừng';
$lang->block->default['full']['my']['1']['block']         = 'welcome';
$lang->block->default['full']['my']['1']['grid']          = 8;
$lang->block->default['full']['my']['1']['source']        = '';
$lang->block->default['full']['my']['2']['title']         = 'Lịch sử';
$lang->block->default['full']['my']['2']['block']         = 'dynamic';
$lang->block->default['full']['my']['2']['grid']          = 4;
$lang->block->default['full']['my']['2']['source']        = '';
$lang->block->default['full']['my']['3']['title']         = 'Quy trình';
$lang->block->default['full']['my']['3']['block']         = 'flowchart';
$lang->block->default['full']['my']['3']['grid']          = 8;
$lang->block->default['full']['my']['3']['source']        = '';
$lang->block->default['full']['my']['4']['title']         = 'Việc của bạn';
$lang->block->default['full']['my']['4']['block']         = 'list';
$lang->block->default['full']['my']['4']['grid']          = 4;
$lang->block->default['full']['my']['4']['source']        = 'todo';
$lang->block->default['full']['my']['4']['params']['num'] = '20';
$lang->block->default['full']['my']['5']                  = $lang->block->default['project']['1'];
$lang->block->default['full']['my']['5']['source']        = 'project';
$lang->block->default['full']['my']['6']                  = $lang->block->default['project']['2'];
$lang->block->default['full']['my']['6']['source']        = 'project';
$lang->block->default['full']['my']['7']                  = $lang->block->default['product']['1'];
$lang->block->default['full']['my']['7']['source']        = 'product';
$lang->block->default['full']['my']['8']                  = $lang->block->default['product']['2'];
$lang->block->default['full']['my']['8']['source']        = 'product';
$lang->block->default['full']['my']['9']                  = $lang->block->default['qa']['2'];
$lang->block->default['full']['my']['9']['source']        = 'qa';

$lang->block->default['onlyTest']['my']['1'] = $lang->block->default['qa']['1'];
$lang->block->default['onlyTest']['my']['1']['source']        = 'qa';
$lang->block->default['onlyTest']['my']['1']['grid']          = '8';
$lang->block->default['onlyTest']['my']['2']['title']         = 'Lịch sử';
$lang->block->default['onlyTest']['my']['2']['block']         = 'dynamic';
$lang->block->default['onlyTest']['my']['2']['grid']          = 4;
$lang->block->default['onlyTest']['my']['2']['source']        = '';
$lang->block->default['onlyTest']['my']['3']['title']         = 'Việc của bạn';
$lang->block->default['onlyTest']['my']['3']['block']         = 'list';
$lang->block->default['onlyTest']['my']['3']['grid']          = 6;
$lang->block->default['onlyTest']['my']['3']['source']        = 'todo';
$lang->block->default['onlyTest']['my']['3']['params']['num'] = '20';
$lang->block->default['onlyTest']['my']['4']                  = $lang->block->default['qa']['2'];
$lang->block->default['onlyTest']['my']['4']['source']        = 'qa';
$lang->block->default['onlyTest']['my']['4']['grid']          = 6;

$lang->block->default['onlyStory']['my']['1'] = $lang->block->default['project']['1'];
$lang->block->default['onlyStory']['my']['1']['source'] = 'project';
$lang->block->default['onlyStory']['my']['1']['grid']   = 8;
$lang->block->default['onlyStory']['my']['2']['title']  = 'Lịch sử';
$lang->block->default['onlyStory']['my']['2']['block']  = 'dynamic';
$lang->block->default['onlyStory']['my']['2']['grid']   = 4;
$lang->block->default['onlyStory']['my']['2']['source'] = '';
$lang->block->default['onlyStory']['my']['3']['title']  = 'Việc của bạn';
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
$lang->block->default['onlyTask']['my']['2']['title']  = 'Lịch sử';
$lang->block->default['onlyTask']['my']['2']['block']  = 'dynamic';
$lang->block->default['onlyTask']['my']['2']['grid']   = 4;
$lang->block->default['onlyTask']['my']['2']['source'] = '';
$lang->block->default['onlyTask']['my']['3']['title']  = 'Việc của bạn';
$lang->block->default['onlyTask']['my']['3']['block']  = 'list';
$lang->block->default['onlyTask']['my']['3']['grid']   = 6;
$lang->block->default['onlyTask']['my']['3']['source'] = 'todo';
$lang->block->default['onlyTask']['my']['3']['params']['num'] = '20';
$lang->block->default['onlyTask']['my']['4'] = $lang->block->default['project']['2'];
$lang->block->default['onlyTask']['my']['4']['source'] = 'project';
$lang->block->default['onlyTask']['my']['4']['grid']   = 6;

$lang->block->num     = 'Số';
$lang->block->type    = 'Loại';
$lang->block->orderBy = 'Order by';

$lang->block->availableBlocks = new stdclass();

$lang->block->availableBlocks->todo     = 'Việc của bạn';
$lang->block->availableBlocks->task     = 'Nhiệm vụ';
$lang->block->availableBlocks->bug      = 'Bugs của bạn';
$lang->block->availableBlocks->case     = 'Tình huống của bạn';
$lang->block->availableBlocks->story    = 'Câu chuyện của bạn';
$lang->block->availableBlocks->product  = $lang->productCommon;
$lang->block->availableBlocks->project  = $lang->projectCommon;
$lang->block->availableBlocks->plan     = 'Kế hoạch';
$lang->block->availableBlocks->release  = 'Phát hành';
$lang->block->availableBlocks->build    = 'Bản dựng';
$lang->block->availableBlocks->testtask = 'Yêu cầu';

$lang->block->moduleList['product'] = $lang->productCommon;
$lang->block->moduleList['project'] = $lang->projectCommon;
$lang->block->moduleList['qa']      = 'QA';
$lang->block->moduleList['todo']    = 'Việc làm';

$lang->block->modules['product'] = new stdclass();
$lang->block->modules['product']->availableBlocks = new stdclass();
$lang->block->modules['product']->availableBlocks->statistic = 'Báo cáo '.$lang->productCommon;
$lang->block->modules['product']->availableBlocks->overview  = 'Tổng quan '.$lang->productCommon;
$lang->block->modules['product']->availableBlocks->list      = 'Danh sách '.$lang->productCommon;
$lang->block->modules['product']->availableBlocks->story     = 'Câu chuyện';
$lang->block->modules['product']->availableBlocks->plan      = 'Kế hoạch';
$lang->block->modules['product']->availableBlocks->release   = 'Phát hành';
$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks = new stdclass();
$lang->block->modules['project']->availableBlocks->statistic = 'Báo cáo '.$lang->projectCommon;
$lang->block->modules['project']->availableBlocks->overview  = 'Tổng quan '.$lang->projectCommon ;
$lang->block->modules['project']->availableBlocks->list      = $lang->projectCommon . ' danh sách';
$lang->block->modules['project']->availableBlocks->task      = 'Nhiệm vụ';
$lang->block->modules['project']->availableBlocks->build     = 'Bản dựng';
$lang->block->modules['qa'] = new stdclass();
$lang->block->modules['qa']->availableBlocks = new stdclass();
$lang->block->modules['qa']->availableBlocks->statistic = 'Báo cáo Test';
//$lang->block->modules['qa']->availableBlocks->overview  = 'Testcase Overview';
$lang->block->modules['qa']->availableBlocks->bug       = 'Bug';
$lang->block->modules['qa']->availableBlocks->case      = 'Tình huống';
$lang->block->modules['qa']->availableBlocks->testtask  = 'Bản dựng';
$lang->block->modules['todo'] = new stdclass();
$lang->block->modules['todo']->availableBlocks = new stdclass();
$lang->block->modules['todo']->availableBlocks->list = 'Việc làm';

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

$lang->block->todoNum = 'Việc làm';
$lang->block->taskNum = 'Nhiệm vụ';
$lang->block->bugNum  = 'Bug';

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

$lang->block->typeList->project['undone']   = 'Chưa kết thúc';
$lang->block->typeList->project['doing']    = 'Đang làm';
$lang->block->typeList->project['all']      = 'Tất cả';
$lang->block->typeList->project['involved'] = 'Liên đới';

$lang->block->typeList->testtask['wait']    = 'Đang đợi';
$lang->block->typeList->testtask['doing']   = 'Đang làm';
$lang->block->typeList->testtask['blocked'] = 'Bị khóa';
$lang->block->typeList->testtask['done']    = 'Hoàn thành';
$lang->block->typeList->testtask['all']     = 'Tất cả';

$lang->block->modules['product']->moreLinkList        = new stdclass();
$lang->block->modules['product']->moreLinkList->list  = 'product|all|product=&line=0&status=%s';
$lang->block->modules['product']->moreLinkList->story = 'my|story|type=%s';

$lang->block->modules['project']->moreLinkList        = new stdclass();
$lang->block->modules['project']->moreLinkList->list  = 'project|all|status=%s&project=';
$lang->block->modules['project']->moreLinkList->task  = 'my|task|type=%s';

$lang->block->modules['qa']->moreLinkList           = new stdclass();
$lang->block->modules['qa']->moreLinkList->bug      = 'my|bug|type=%s';
$lang->block->modules['qa']->moreLinkList->case     = 'my|testcase|type=%s';
$lang->block->modules['qa']->moreLinkList->testtask = 'testtask|browse|type=%s';

$lang->block->modules['todo']->moreLinkList       = new stdclass();
$lang->block->modules['todo']->moreLinkList->list = 'my|todo|type=all';

$lang->block->modules['common'] = new stdclass();
$lang->block->modules['common']->moreLinkList = new stdclass();
$lang->block->modules['common']->moreLinkList->dynamic = 'company|dynamic|';

$lang->block->welcomeList['06:00'] = 'Chào buổi sáng,</br> <strong>%s</strong>';
$lang->block->welcomeList['11:30'] = 'Một ngày vui, </br><strong>%s</strong>';
$lang->block->welcomeList['13:30'] = 'Chào buổi chiều, </br> <strong>%s</strong>';
$lang->block->welcomeList['19:00'] = 'Chào buổi tối,</br> <strong>%s</strong>';

$lang->block->gridOptions[8] = 'Trái';
$lang->block->gridOptions[4] = 'Phải';

$lang->block->flowchart   = array();
$lang->block->flowchart['admin']   = array('Quản trị viên', 'Phòng/ban', 'Người dùng', 'Phân quyền');
$lang->block->flowchart['product'] = array($lang->productCommon, $lang->productCommon, 'Modules', 'Kế hoạch', 'Câu chuyện', 'Phát hành');
$lang->block->flowchart['project'] = array('Scrum Master',$lang->projectCommon, 'Đội nhóm', $lang->productCommon, 'Câu chuyện', 'Nhiệm vụ');
$lang->block->flowchart['dev']     = array('Dev Team', 'Nhiệm vụ/Bugs', 'Tình trạng');
$lang->block->flowchart['tester']  = array('QA Team', 'Tình huống', 'Báo cáo Bugs', 'Kiểm tra Bugs', 'Đóng Bugs');
