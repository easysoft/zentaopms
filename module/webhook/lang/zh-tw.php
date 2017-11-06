<?php
$lang->webhook->common   = 'webhook';
$lang->webhook->list     = '鈎子列表';
$lang->webhook->api      = '介面';
$lang->webhook->entry    = '應用';
$lang->webhook->log      = '日誌';
$lang->webhook->assigned = '指派給';

$lang->webhook->browse = '瀏覽鈎子';
$lang->webhook->create = '添加鈎子';
$lang->webhook->edit   = '編輯鈎子';
$lang->webhook->delete = '刪除鈎子';

$lang->webhook->id          = 'ID';
$lang->webhook->type        = '類型';
$lang->webhook->name        = '名稱';
$lang->webhook->url         = 'Hook地址';
$lang->webhook->contentType = '內容類型';
$lang->webhook->sendType    = '發送方式';
$lang->webhook->product     = '關聯產品';
$lang->webhook->project     = '關聯項目';
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

$lang->webhook->typeList['bearychat'] = '倍洽';
$lang->webhook->typeList['dingding']  = '釘釘';
$lang->webhook->typeList['default']   = '預設';

$lang->webhook->sendTypeList['sync']  = '同步';
$lang->webhook->sendTypeList['async'] = '非同步';

$lang->webhook->paramsList['objectType'] = '對象類型';
$lang->webhook->paramsList['objectID']   = '對象ID';
$lang->webhook->paramsList['product']    = '所屬產品';
$lang->webhook->paramsList['project']    = '所屬項目';
$lang->webhook->paramsList['action']     = '動作';
$lang->webhook->paramsList['actor']      = '操作者';
$lang->webhook->paramsList['date']       = '操作日期';
$lang->webhook->paramsList['comment']    = '備註';
$lang->webhook->paramsList['text']       = '操作內容';

$lang->webhook->saveSuccess   = '保存成功';
$lang->webhook->confirmDelete = '您確認要刪除該webhook嗎？';

$lang->webhook->trimWords = '了';

$lang->webhook->note = new stdClass();
$lang->webhook->note->async   = '非同步需要打開計劃任務';
$lang->webhook->note->product = '此項為空時所有產品的動作都會觸發鈎子，否則只有關聯產品的動作才會觸發。';
$lang->webhook->note->project = '此項為空時所有項目的動作都會觸發鈎子，否則只有關聯項目的動作才會觸發。';

$lang->webhook->note->typeList['bearychat'] = '請在倍洽中添加一個禪道機器人，並將其webhook填寫到此處。';
$lang->webhook->note->typeList['dingding']  = '請在釘釘中添加一個自定義機器人，並將其webhook填寫到此處。';
$lang->webhook->note->typeList['default']   = '從第三方系統獲取webhook並填寫到此處。';
