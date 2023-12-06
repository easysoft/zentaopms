<?php
/**
 * The productplan module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  productplan
 * @version  $Id: vi.php 4659 2013-04-17 06:45:08Z quocnho@gmail.com $
 * @link  https://www.zentao.net
 */
$lang->productplan->common     = 'Kế hoạch '.$lang->productCommon;
$lang->productplan->browse     = "Danh sách kế hoạch";
$lang->productplan->index      = "Danh sách";
$lang->productplan->create     = "Tạo kế hoạch";
$lang->productplan->edit       = "Sửa kế hoạch";
$lang->productplan->delete     = "Xóa kế hoạch";
$lang->productplan->view       = "Chi tiết kế hoạch";
$lang->productplan->bugSummary = "Tổng <strong>%s</strong> Bugs trên trang này.";
$lang->productplan->basicInfo  = 'Thông tin cơ bản';
$lang->productplan->batchEdit  = 'Sửa hàng loạt';
$lang->productplan->project    = 'Project';

$lang->productplan->batchUnlink      = "Hủy liên kết hàng loạt";
$lang->productplan->linkStory        = "Liên kết câu chuyện";
$lang->productplan->unlinkStory      = "Hủy liên kết câu chuyện";
$lang->productplan->unlinkStoryAB    = "Hủy liên kết";
$lang->productplan->batchUnlinkStory = "Hủy liên kết hàng loạt";
$lang->productplan->linkedStories    = 'Câu chuyện liên kết';
$lang->productplan->unlinkedStories  = 'Câu chuyện chưa liên kết';
$lang->productplan->updateOrder      = 'Sắp xếp';
$lang->productplan->createChildren   = "Tạo kế hoạch con";
$lang->productplan->createExecution  = "Create {$lang->executionCommon}";

$lang->productplan->linkBug        = "Liên kết Bug";
$lang->productplan->unlinkBug      = "Hủy liên kết Bug";
$lang->productplan->batchUnlinkBug = "Hủy liên kết Bug hàng loạt";
$lang->productplan->linkedBugs     = 'Bugs liên kết';
$lang->productplan->unlinkedBugs   = 'Bugs chưa liên kết';
$lang->productplan->unexpired      = 'Kế hoạch chưa quá hạn';
$lang->productplan->all            = 'Tất cả kế hoạch';

$lang->productplan->confirmDelete      = "Bạn có muốn xóa kế hoạch này?";
$lang->productplan->confirmUnlinkStory = "Bạn có muốn hủy liên kết câu chuyện này?";
$lang->productplan->confirmUnlinkBug   = "Bạn có muốn hủy liên kết bug này?";
$lang->productplan->noPlan             = 'Không có kế hoạch nào';
$lang->productplan->cannotDeleteParent = 'Không thể xóa kế hoạch mẹ';
$lang->productplan->selectProjects     = "Please select the project";
$lang->productplan->nextStep           = "Next step";

$lang->productplan->id         = 'ID';
$lang->productplan->product    = $lang->productCommon;
$lang->productplan->branch     = 'Platform/Branch';
$lang->productplan->title      = 'Tiêu đề';
$lang->productplan->desc       = 'Mô tả';
$lang->productplan->begin      = 'Bắt đầu';
$lang->productplan->end        = 'Kết thúc';
$lang->productplan->last       = 'Kế hoạch gần nhất';
$lang->productplan->future     = 'TBD';
$lang->productplan->stories    = 'Câu chuyện';
$lang->productplan->bugs       = 'Bug';
$lang->productplan->hour       = $lang->hourCommon;
$lang->productplan->execution  = $lang->executionCommon;
$lang->productplan->parent     = "Kế hoạch mẹ";
$lang->productplan->parentAB   = "Mẹ";
$lang->productplan->children   = "Kế hoạch con";
$lang->productplan->childrenAB = "C";
$lang->productplan->order      = "Thứ tự";
$lang->productplan->deleted    = "Đã xóa";

$lang->productplan->endList[7]    = '1 tuần';
$lang->productplan->endList[14]   = '2 tuần';
$lang->productplan->endList[31]   = '1 tháng';
$lang->productplan->endList[62]   = '2 tháng';
$lang->productplan->endList[93]   = '3 tháng';
$lang->productplan->endList[186]  = '6 tháng';
$lang->productplan->endList[365]  = '1 năm';

$lang->productplan->errorNoTitle = 'Tiêu đề ID %s không nên trống.';
$lang->productplan->errorNoBegin = 'Thời gian bắt đầu ID %s không nên trống.';
$lang->productplan->errorNoEnd   = 'Thời gian kết thúc ID %s không nên trống.';
$lang->productplan->beginGeEnd   = 'ID %s thời gian bắt đầu không nên >= thời gian kết thúc.';

$lang->productplan->featureBar['browse']['all']       = 'Tất cả';
$lang->productplan->featureBar['browse']['unexpired'] = 'Chưa quá hạn';
$lang->productplan->featureBar['browse']['overdue']   = 'Quá hạn';
