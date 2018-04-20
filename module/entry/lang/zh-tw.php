<?php
$lang->entry->common  = '應用';
$lang->entry->list    = '應用列表';
$lang->entry->api     = '介面';
$lang->entry->webhook = 'Webhook';
$lang->entry->log     = '日誌';
$lang->entry->setting = '設置';

$lang->entry->browse    = '瀏覽應用';
$lang->entry->create    = '添加應用';
$lang->entry->edit      = '編輯應用';
$lang->entry->delete    = '刪除應用';
$lang->entry->createKey = '重新生成密鑰';

$lang->entry->id          = 'ID';
$lang->entry->name        = '名稱';
$lang->entry->code        = '代號';
$lang->entry->key         = '密鑰';
$lang->entry->ip          = 'IP';
$lang->entry->desc        = '描述';
$lang->entry->createdBy   = '由誰創建';
$lang->entry->createdDate = '創建時間';
$lang->entry->editedby    = '最後編輯';
$lang->entry->editedDate  = '編輯時間';
$lang->entry->date        = '請求時間';
$lang->entry->url         = '請求地址';

$lang->entry->confirmDelete = '您確認要刪除該應用嗎？';
$lang->entry->help          = '使用說明';

$lang->entry->note = new stdClass();
$lang->entry->note->name  = '授權應用名稱';
$lang->entry->note->code  = '授權應用代號，必須為字母或數字的組合';
$lang->entry->note->ip    = "允許訪問API的應用ip，多個ip用逗號隔開。支持IP段，如192.168.1.*";
$lang->entry->note->allIP = '無限制';

$lang->entry->errmsg['PARAM_CODE_MISSING']    = '缺少code參數';
$lang->entry->errmsg['PARAM_TOKEN_MISSING']   = '缺少token參數';
$lang->entry->errmsg['SESSION_CODE_MISSING']  = '缺少session code';
$lang->entry->errmsg['EMPTY_KEY']             = '應用未設置密鑰';
$lang->entry->errmsg['INVALID_TOKEN']         = '無效的token參數';
$lang->entry->errmsg['SESSION_VERIFY_FAILED'] = 'session驗證失敗';
$lang->entry->errmsg['IP_DENIED']             = '該IP被限制訪問';
$lang->entry->errmsg['EMPTY_ENTRY']           = '應用不存在';
