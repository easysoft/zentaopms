<?php
$lang->webhook->common     = 'Webhook';
$lang->webhook->list       = 'Webhook列表';
$lang->webhook->api        = '介面';
$lang->webhook->entry      = '應用';
$lang->webhook->log        = '日誌';
$lang->webhook->bind       = '綁定用戶';
$lang->webhook->chooseDept = '選擇同步部門';
$lang->webhook->assigned   = '指派給';
$lang->webhook->setting    = '設置';

$lang->webhook->browse = '瀏覽Webhook';
$lang->webhook->create = '添加Webhook';
$lang->webhook->edit   = '編輯Webhook';
$lang->webhook->delete = '刪除Webhook';

$lang->webhook->id          = 'ID';
$lang->webhook->type        = '類型';
$lang->webhook->name        = '名稱';
$lang->webhook->url         = 'Hook地址';
$lang->webhook->domain      = '禪道域名';
$lang->webhook->contentType = '內容類型';
$lang->webhook->sendType    = '發送方式';
$lang->webhook->secret      = '密鑰';
$lang->webhook->product     = "關聯{$lang->productCommon}";
$lang->webhook->project     = "關聯{$lang->projectCommon}";
$lang->webhook->params      = '參數';
$lang->webhook->action      = '觸發動作';
$lang->webhook->desc        = '描述';
$lang->webhook->createdBy   = '由誰創建';
$lang->webhook->createdDate = '創建時間';
$lang->webhook->editedby    = '最後編輯';
$lang->webhook->editedDate  = '編輯時間';
$lang->webhook->date        = '發送時間';
$lang->webhook->data        = '數據';
$lang->webhook->result      = '結果';

$lang->webhook->typeList['']            = '';
$lang->webhook->typeList['dinggroup']   = '釘釘群通知機器人';
$lang->webhook->typeList['dinguser']    = '釘釘工作消息通知';
$lang->webhook->typeList['wechatgroup'] = '企業微信群機器人';
$lang->webhook->typeList['wechatuser']  = '企業微信應用消息';
$lang->webhook->typeList['default']     = '其他';

$lang->webhook->sendTypeList['sync']  = '同步';
$lang->webhook->sendTypeList['async'] = '非同步';

$lang->webhook->dingAgentId     = '釘釘AgentId';
$lang->webhook->dingAppKey      = '釘釘AppKey';
$lang->webhook->dingAppSecret   = '釘釘AppSecret';
$lang->webhook->dingUserid      = '釘釘用戶';
$lang->webhook->dingBindStatus  = '釘釘綁定狀態';
$lang->webhook->chooseDeptAgain = '重選部門';

$lang->webhook->wechatCorpId     = '企業ID';
$lang->webhook->wechatCorpSecret = '應用的憑證密鑰';
$lang->webhook->wechatAgentId    = '企業應用的ID';
$lang->webhook->wechatUserid     = '微信用戶';
$lang->webhook->wechatBindStatus = '微信綁定狀態';

$lang->webhook->zentaoUser  = '禪道用戶';

$lang->webhook->dingBindStatusList['0'] = '未綁定';
$lang->webhook->dingBindStatusList['1'] = '已綁定';

$lang->webhook->paramsList['objectType'] = '對象類型';
$lang->webhook->paramsList['objectID']   = '對象ID';
$lang->webhook->paramsList['product']    = "所屬{$lang->productCommon}";
$lang->webhook->paramsList['project']    = "所屬{$lang->projectCommon}";
$lang->webhook->paramsList['action']     = '動作';
$lang->webhook->paramsList['actor']      = '操作者';
$lang->webhook->paramsList['date']       = '操作日期';
$lang->webhook->paramsList['comment']    = '備註';
$lang->webhook->paramsList['text']       = '操作內容';

$lang->webhook->confirmDelete = '您確認要刪除該webhook嗎？';

$lang->webhook->trimWords = '了';

$lang->webhook->note = new stdClass();
$lang->webhook->note->async   = '非同步需要打開計劃任務。';
$lang->webhook->note->bind    = '只有[釘釘/微信]工作通知類型才需要綁定用戶。';
$lang->webhook->note->product = "此項為空時所有{$lang->productCommon}的動作都會觸發鈎子，否則只有關聯{$lang->productCommon}的動作才會觸發。";
$lang->webhook->note->project = "此項為空時所有{$lang->projectCommon}的動作都會觸發鈎子，否則只有關聯{$lang->projectCommon}的動作才會觸發。";

$lang->webhook->note->dingHelp   = " <a href='http://www.zentao.net/book/zentaopmshelp/358.html' target='_blank'><i class='icon-help'></i></a>";
$lang->webhook->note->wechatHelp = " <a href='http://www.zentao.net/book/zentaopmshelp/367.html' target='_blank'><i class='icon-help'></i></a>";

$lang->webhook->note->typeList['bearychat'] = '請在倍洽中添加一個禪道機器人，並將其webhook填寫到此處。';
$lang->webhook->note->typeList['dingding']  = '請在釘釘中添加一個自定義機器人，並將其webhook填寫到此處。';
$lang->webhook->note->typeList['weixin']    = '請在企業微信中添加一個自定義機器人，並將其webhook填寫到此處。';
$lang->webhook->note->typeList['default']   = '從第三方系統獲取webhook並填寫到此處。';

$lang->webhook->error = new stdclass();
$lang->webhook->error->curl   = '需要加載php-curl擴展。';
$lang->webhook->error->noDept = '沒有選擇部門，請先選擇同步部門。';
