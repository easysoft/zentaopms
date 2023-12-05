<?php
/**
 * The release module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  release
 * @version  $Id: vi.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link  http://www.zentao.net
 */
$lang->release->common           = 'Product Release';
$lang->release->create           = "Tạo phát hành";
$lang->release->edit             = "Sửa phát hành";
$lang->release->linkStory        = "Liên kết câu chuyện";
$lang->release->linkBug          = "Liên kết Bug";
$lang->release->delete           = "Xóa phát hành";
$lang->release->deleted          = 'Đã xóa';
$lang->release->view             = "Chi tiết phát hành";
$lang->release->browse           = "Danh sách phát hành";
$lang->release->changeStatus     = "Thay đổi tình trạng";
$lang->release->batchUnlink      = "Hủy liên kết hàng loạt";
$lang->release->batchUnlinkStory = "Hủy liên kết câu chuyện hàng loạt";
$lang->release->batchUnlinkBug   = "Hủy liên kết Bug hàng loạt";

$lang->release->confirmDelete      = "Bạn có muốn xóa phát hành này?";
$lang->release->confirmUnlinkStory = "Bạn có muốn gỡ câu chuyện này?";
$lang->release->confirmUnlinkBug   = "Bạn có muốn gỡ bug này?";
$lang->release->existBuild         = '『Bản dựng』 『%s』 đã tồn tại. Bạn có thể thay đổi 『Tên』 hoặc chọn một 『Bản dựng』.';
$lang->release->noRelease          = 'Không có phát hành nào';
$lang->release->errorDate          = 'Ngày phát hành này không nên lớn hơn Hôm nay.';

$lang->release->basicInfo = 'Thông tin cơ bản';

$lang->release->id            = 'ID';
$lang->release->product       = $lang->productCommon;
$lang->release->branch        = 'Platform/Branch';
$lang->release->project       = 'Project';
$lang->release->build         = 'Bản dựng';
$lang->release->name          = 'Tên';
$lang->release->marker        = 'Cột mốc';
$lang->release->date          = 'Ngày';
$lang->release->desc          = 'Mô tả';
$lang->release->files         = 'Chung';
$lang->release->status        = 'Tình trạng';
$lang->release->subStatus     = 'Tình trạng con';
$lang->release->last          = 'Phát hành gần nhất';
$lang->release->unlinkStory   = 'Hủy liên kết câu chuyện';
$lang->release->unlinkBug     = 'Hủy liên kết Bug';
$lang->release->stories       = 'Câu chuyện đã kết thúc';
$lang->release->bugs          = 'Bug đã giải quyết';
$lang->release->leftBugs      = 'Kích hoạt Bug';
$lang->release->generatedBugs = 'Kích hoạt Bug';
$lang->release->finishStories = 'Câu chuyện %s đã kết thúc';
$lang->release->resolvedBugs  = 'Bugs %s đã giải quyết';
$lang->release->createdBugs   = 'Bug %s chưa được giải quyết';
$lang->release->export        = 'Xuất ra HTML';
$lang->release->yesterday     = 'Phát hành hôm qua';
$lang->release->all           = 'Tất cả';
$lang->release->notify        = 'Notify';
$lang->release->notifyUsers   = 'Notify Users';
$lang->release->mailto        = 'Mailto';

$lang->release->filePath = 'Tải về : ';
$lang->release->scmPath  = 'SCM Path : ';

$lang->release->exportTypeList['all']     = 'Tất cả';
$lang->release->exportTypeList['story']   = 'Câu chuyện';
$lang->release->exportTypeList['bug']     = 'Bug';
$lang->release->exportTypeList['leftbug'] = 'Kích hoạt Bug';

$lang->release->statusList['']          = '';
$lang->release->statusList['normal']    = 'Kích hoạt';
$lang->release->statusList['terminate'] = 'Hoàn thành';

$lang->release->changeStatusList['normal']    = 'Kích hoạt';
$lang->release->changeStatusList['terminate'] = 'Hoàn thành';

$lang->release->action = new stdclass();
$lang->release->action->changestatus = array('main' => '$date,  $extra bởi  <strong>$actor</strong>.', 'extra' => 'changeStatusList');
$lang->release->action->notified     = array('main' => '$date, <strong>$actor</strong> send notify.');

$lang->release->notifyList['PO'] = "{$lang->productCommon} Owner";
$lang->release->notifyList['QD'] = 'QA Manager';
$lang->release->notifyList['SC'] = 'Story Creator';
$lang->release->notifyList['ET'] = "{$lang->execution->common} Team Members";
$lang->release->notifyList['PT'] = "Project Team Members";

$lang->release->featureBar['browse']['all']       = $lang->release->all;
$lang->release->featureBar['browse']['normal']    = $lang->release->statusList['normal'];
$lang->release->featureBar['browse']['terminate'] = $lang->release->statusList['terminate'];
