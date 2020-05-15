<?php
$lang->entry->common  = 'Application';
$lang->entry->list    = 'Applications';
$lang->entry->api     = 'API';
$lang->entry->webhook = 'Webhook';
$lang->entry->log     = 'Nhật ký';
$lang->entry->setting = 'Thiết lập';

$lang->entry->browse    = 'Browse';
$lang->entry->create    = 'Thêm ứng dụng';
$lang->entry->edit      = 'Sửa';
$lang->entry->delete    = 'Xóa';
$lang->entry->createKey = 'Regenerate Secret Key';

$lang->entry->id          = 'ID';
$lang->entry->name        = 'Tên';
$lang->entry->account     = 'Tài khoản';
$lang->entry->code        = 'Mã';
$lang->entry->freePasswd  = 'Free Password Login';
$lang->entry->key         = 'Khóa';
$lang->entry->ip          = 'IP';
$lang->entry->desc        = 'Mô tả';
$lang->entry->createdBy   = 'Người tạo';
$lang->entry->createdDate = 'CreateDate';
$lang->entry->editedby    = 'Người sửa';
$lang->entry->editedDate  = 'Ngày sửa';
$lang->entry->date        = 'Requesting Time';
$lang->entry->url         = 'Requesting URL';

$lang->entry->confirmDelete = 'Bạn có muốn xóa this entry?';
$lang->entry->help          = 'Trợ giúp';
$lang->entry->notify        = 'Thông báo';

$lang->entry->helpLink   = 'https://www.zentao.pm/book/zentaomanual/scrum-tool-open-source-integrate-third-party-application-221.html';
$lang->entry->notifyLink = 'https://www.zentao.net/book/zentaopmshelp/301.html';

$lang->entry->note = new stdClass();
$lang->entry->note->name    = 'Tên';
$lang->entry->note->code    = 'Code should be chữ và số';
$lang->entry->note->ip      = "Sử dụng comma to seperate IPs. IP segment is supported, ví dụ:  192.168.1.*";
$lang->entry->note->allIP   = 'Tất cả IPs';
$lang->entry->note->account = 'Application tài khoản';

$lang->entry->freePasswdList[1] = 'On';
$lang->entry->freePasswdList[0] = 'Off';

$lang->entry->errmsg['PARAM_CODE_MISSING']    = 'Parameter code is missing.';
$lang->entry->errmsg['PARAM_TOKEN_MISSING']   = 'Parameter token is missing.';
$lang->entry->errmsg['SESSION_CODE_MISSING']  = 'Session code is missing.';
$lang->entry->errmsg['EMPTY_KEY']             = 'Secret key is missing.';
$lang->entry->errmsg['INVALID_TOKEN']         = 'Invalid token.';
$lang->entry->errmsg['SESSION_VERIFY_FAILED'] = 'Session verification thất bại';
$lang->entry->errmsg['IP_DENIED']             = 'IP bị từ chối.';
$lang->entry->errmsg['ACCOUNT_UNBOUND']       = 'Account không là bound.';
$lang->entry->errmsg['INVALID_ACCOUNT']       = 'Invalid account.';
$lang->entry->errmsg['EMPTY_ENTRY']           = 'Application không tồn tại.';
$lang->entry->errmsg['CALLED_TIME']           = 'Token has expired';
$lang->entry->errmsg['ERROR_TIMESTAMP']       = 'Timestamp Error';
