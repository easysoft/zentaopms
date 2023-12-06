<?php
/**
 * The api module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  api
 * @version  $Id: English.php 824 2010-05-02 15:32:06Z wwccss $
 * @link  https://www.zentao.net
 */
$lang->api->common   = 'API';
$lang->api->getModel = 'Super Model API';
$lang->api->sql      = 'SQL Query API';

$lang->api->position  = 'Vị trí';
$lang->api->startLine = "%s,%s";
$lang->api->desc      = 'Mô tả';
$lang->api->debug     = 'Debug';
$lang->api->submit    = 'Gửi';
$lang->api->url       = 'Request URL';
$lang->api->result    = 'Kết quả';
$lang->api->status    = 'Tình trạng';
$lang->api->data      = 'Dữ liệu';
$lang->api->noParam   = 'Không có tham số bắt buộc nếu GET Debug';
$lang->api->post      = 'Tham chiếu tới danh sách trang nếu POST Debug';

$lang->api->error = new stdclass();
$lang->api->error->onlySelect = 'Giao diện SQL chỉ cho phép truy vấn SELECT.';
$lang->api->error->disabled   = 'For security reasons, this feature is disabled. You can go to the config directory and modify the configuration item %s to open this function.';
