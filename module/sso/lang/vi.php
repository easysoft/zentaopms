<?php
/**
 * The sso module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Yidong Wang <yidong@cnezsoft.com>
 * @package  sso
 * @version  $Id$
 * @link  https://www.zentao.net
 */
$lang->sso = new stdclass();
$lang->sso->settings   = 'Thiết lập';
$lang->sso->turnon     = 'Zdoo';
$lang->sso->redirect   = 'Tự động chuyển tới Zdoo';
$lang->sso->code       = 'Mã';
$lang->sso->key        = 'Mã bí mật';
$lang->sso->addr       = 'Địa chỉ';
$lang->sso->bind       = 'Liên kết người dùng';
$lang->sso->addrNotice = 'Ví dụ http://www.ranzhi.com/sys/sso-check.html';

$lang->sso->turnonList    = array();
$lang->sso->turnonList[1] = 'On';
$lang->sso->turnonList[0] = 'Off';

$lang->sso->bindType = 'Loại liên kết';
$lang->sso->bindUser = 'Liên kết người dùng';

$lang->sso->bindTypeList['bind'] = 'Liên kết tới người dùng tồn tại';
$lang->sso->bindTypeList['add']  = 'Thêm người dùng';

$lang->sso->help = <<<EOD
<p>1. Địa chỉ Zdoo là bắt buộc. Nếu sử dụng PATH_INFO, nó là http://YOUR ZDOO ADDRESS/sys/sso-check.html nếu GET, nó là http://YOUR ZDOO ADDRESS/sys/index.php?m=sso&f=check</p>
<p>2. Mã nguồn và khóa bí mật phải giống như đã thiết lập trên Zdoo.</p>
EOD;
$lang->sso->bindNotice     = 'Người dùng vừa được thêm vào không có quyền. Bạn phải yêu cầu Quản trị viên ZenTao cấp quyền cho Người dùng.';
$lang->sso->bindNoPassword = 'Mật khẩu không nên trống.';
$lang->sso->bindNoUser     = 'Mật khẩu sai hoặc người dùng không tìm thấy!';
$lang->sso->bindHasAccount = 'Tên người dùng đã tồn tại. Thay đổi tên người dùng hoặc liên kết nó.';
