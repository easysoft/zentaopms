<?php
/**
 * The build module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  build
 * @version  $Id: vi.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link  http://www.zentao.net
 */
$lang->build->common           = "Bản dựng";
$lang->build->create           = "Tạo bản dựng";
$lang->build->edit             = "Sửa bản dựng";
$lang->build->linkStory        = "Liên kết {$lang->storyCommon}";
$lang->build->linkBug          = "Liên kết Bug";
$lang->build->delete           = "Xóa bản dựng";
$lang->build->deleted          = "Đã xóa";
$lang->build->view             = "Chi tiết bản dựng";
$lang->build->batchUnlink      = 'Hủy liên kết hàng loạt';
$lang->build->batchUnlinkStory = "Hủy liên kết {$lang->storyCommon} hàng loạt";
$lang->build->batchUnlinkBug   = 'Hủy liên kết Bug hàng loạt';

$lang->build->confirmDelete      = "Bạn có muốn xóa bản dựng này?";
$lang->build->confirmUnlinkStory = "Bạn có muốn hủy liên kết {$lang->storyCommon} này?";
$lang->build->confirmUnlinkBug   = "Bạn có muốn hủy liên kết this Bug?";

$lang->build->basicInfo = 'Thông tin cơ bản';

$lang->build->id            = 'ID';
$lang->build->product       = $lang->productCommon;
$lang->build->branch        = 'Platform/Branch';
$lang->build->project       = $lang->projectCommon;
$lang->build->name          = 'Tên';
$lang->build->date          = 'Ngày';
$lang->build->builder       = 'Builder';
$lang->build->scmPath       = 'SCM Path';
$lang->build->filePath      = 'File Path';
$lang->build->desc          = 'Mô tả';
$lang->build->files         = 'Files';
$lang->build->last          = 'Bản dựng cuối';
$lang->build->packageType   = 'Loại gói';
$lang->build->unlinkStory   = "Hủy liên kết {$lang->storyCommon}";
$lang->build->unlinkBug     = 'Hủy liên kết Bug';
$lang->build->stories       = "{$lang->storyCommon} đã kết thúc";
$lang->build->bugs          = 'Bugs đã giải quyết';
$lang->build->generatedBugs = 'Bugs đã báo cáo';
$lang->build->noProduct     = " <span style='color:red'>{$lang->projectCommon} này chưa liên kết tới {$lang->productCommon}, bởi vậy Bản dựng này không thể tạo. Vui lòng liên kết <a href='%s'> {$lang->productCommon} trước</a></span>";
$lang->build->noBuild       = 'Không có bản dựng nào';

$lang->build->notice = new stdclass();
$lang->build->notice->changeProduct = "{$lang->storyCommon} này  hoặc Bug đã được kết hợp, và sản phẩm của nó không thể bị sửa đổi";

$lang->build->finishStories = "  {$lang->storyCommon} đã kết thúc %s";
$lang->build->resolvedBugs  = '  Bug đã giải quyết %s';
$lang->build->createdBugs   = '  Bug đã báo cáo %s';

$lang->build->placeholder = new stdclass();
$lang->build->placeholder->scmPath  = ' Source code repository, ví dụ:  Subversion/Git Library path';
$lang->build->placeholder->filePath = ' Đường dẫn tải về cho bản dựng này.';

$lang->build->action = new stdclass();
$lang->build->action->buildopened = '$date, được tạo bởi <strong>$actor</strong>, Bản dựng <strong>$extra</strong>.' . "\n";
$lang->backhome = 'Trở lại';
