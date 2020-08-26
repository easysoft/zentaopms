<?php
$lang->webhook->common     = 'Webhook';
$lang->webhook->list       = 'Hook danh sách';
$lang->webhook->api        = 'API';
$lang->webhook->entry      = 'Entry';
$lang->webhook->log        = 'Nhật ký';
$lang->webhook->bind       = 'Bind người dùng';
$lang->webhook->chooseDept = 'Choose department';
$lang->webhook->assigned   = 'Giao cho';
$lang->webhook->setting    = 'Thiết lập';

$lang->webhook->browse       = 'Browse';
$lang->webhook->create       = 'Tạo';
$lang->webhook->edit         = 'Sửa';
$lang->webhook->delete       = 'Xóa';

$lang->webhook->id          = 'ID';
$lang->webhook->type        = 'Loại';
$lang->webhook->name        = 'Tên';
$lang->webhook->url         = 'Webhook URL';
$lang->webhook->domain      = 'ZenTao Domain';
$lang->webhook->contentType = 'Content loại';
$lang->webhook->sendType    = 'Sending loại';
$lang->webhook->secret      = 'Secret';
$lang->webhook->product     = "{$lang->productCommon}";
$lang->webhook->project     = "{$lang->projectCommon}";
$lang->webhook->params      = 'Thông số';
$lang->webhook->action      = 'Trigger hành động';
$lang->webhook->desc        = 'Mô tả';
$lang->webhook->createdBy   = 'Người tạo';
$lang->webhook->createdDate = 'Ngày tạo';
$lang->webhook->editedby    = 'Người sửa';
$lang->webhook->editedDate  = 'Ngày sửa';
$lang->webhook->date        = 'Đã gửi';
$lang->webhook->data        = 'Dữ liệu';
$lang->webhook->result      = 'Kết quả';

$lang->webhook->typeList[''] = '';
$lang->webhook->typeList['dinggroup']   = 'Dingding Robot';
$lang->webhook->typeList['dinguser']    = 'Dingding Notifier';
$lang->webhook->typeList['wechatgroup'] = 'Enterprise WeChat Robot';
$lang->webhook->typeList['wechatuser']  = 'Enterprise WeChat Notifier';
$lang->webhook->typeList['default']     = 'Khác';

$lang->webhook->sendTypeList['sync']  = 'Synchronous';
$lang->webhook->sendTypeList['async'] = 'Asynchronous';

$lang->webhook->dingAgentId     = 'AgentID';
$lang->webhook->dingAppKey      = 'AppKey';
$lang->webhook->dingAppSecret   = 'AppSecret';
$lang->webhook->dingUserid      = 'Ding UserID';
$lang->webhook->dingBindStatus  = 'Bind tình trạng';
$lang->webhook->chooseDeptAgain = 'Rechoose department';

$lang->webhook->wechatCorpId     = 'Corp ID';
$lang->webhook->wechatCorpSecret = 'Corp Secret';
$lang->webhook->wechatAgentId    = 'Agent ID';
$lang->webhook->wechatUserid     = 'Wechat Userid';
$lang->webhook->wechatBindStatus = 'Bind tình trạng';

$lang->webhook->zentaoUser = 'Zentao người dùng';

$lang->webhook->dingBindStatusList['0'] = 'Không';
$lang->webhook->dingBindStatusList['1'] = 'Có';

$lang->webhook->paramsList['objectType'] = 'Loại đối tượng';
$lang->webhook->paramsList['objectID']   = 'ID đối tượng';
$lang->webhook->paramsList['product']    = "{$lang->productCommon}";
$lang->webhook->paramsList['project']    = "{$lang->projectCommon}";
$lang->webhook->paramsList['action']     = 'Hành động';
$lang->webhook->paramsList['actor']      = 'ActedBy';
$lang->webhook->paramsList['date']       = 'ActedDate';
$lang->webhook->paramsList['comment']    = 'Nhận xét';
$lang->webhook->paramsList['text']       = 'Action Description';

$lang->webhook->confirmDelete = 'Bạn có muốn xóa this hook?';

$lang->webhook->trimWords = '';

$lang->webhook->note = new stdClass();
$lang->webhook->note->async   = 'If it is asynchronous, you have to go to Admin-System to bật CRON này.';
$lang->webhook->note->bind    = 'Bind User is only required for Dingding Notifier.';
$lang->webhook->note->product = "Tất cả actions will trigger the hook if {$lang->productCommon} trống, or only actions of selected {$lang->productCommon} will trigger it.";
$lang->webhook->note->project = "Tất cả actions will trigger the hook if {$lang->projectCommon} trống, or only actions of selected {$lang->projectCommon} will trigger it.";

$lang->webhook->note->dingHelp   = " <a href='http://www.zentao.net/book/zentaopmshelp/358.html' target='_blank'><i class='icon-help'></i></a>";
$lang->webhook->note->wechatHelp = " <a href='http://www.zentao.net/book/zentaopmshelp/367.html' target='_blank'><i class='icon-help'></i></a>";

$lang->webhook->note->typeList['bearychat'] = 'Thêm a ZenTao bot in bearychat and get the webhook url.';
$lang->webhook->note->typeList['dingding']  = 'Thêm a customized bot in dingding and get the webhook url.';
$lang->webhook->note->typeList['weixin']    = 'Thêm a customized bot in WeChat and get the webhook url.';
$lang->webhook->note->typeList['default']   = 'Nhận a webhook url from others';

$lang->webhook->error = new stdclass();
$lang->webhook->error->curl   = 'Load php-curl in php.ini.';
$lang->webhook->error->noDept = 'There is no department selected. Please choose department first.';
