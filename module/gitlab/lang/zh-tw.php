<?php
$lang->gitlab = new stdclass;
$lang->gitlab->common        = 'GitLab';
$lang->gitlab->browse        = '瀏覽GitLab';
$lang->gitlab->create        = '添加GitLab';
$lang->gitlab->edit          = '編輯GitLab';
$lang->gitlab->bindUser      = '綁定用戶';
$lang->gitlab->webhook       = 'webhook';
$lang->gitlab->bindProduct   = '關聯產品';
$lang->gitlab->importIssue   = '關聯issue';
$lang->gitlab->delete        = '刪除GitLab';
$lang->gitlab->confirmDelete = '確認刪除該GitLab嗎？';
$lang->gitlab->gitlabAccount = 'GitLab用戶';
$lang->gitlab->zentaoAccount = '禪道用戶';

$lang->gitlab->browseAction  = 'GitLab列表';
$lang->gitlab->deleteAction  = '刪除GitLab';
$lang->gitlab->gitlabProject = "{$lang->gitlab->common}項目";
$lang->gitlab->gitlabIssue   = "{$lang->gitlab->common} issue";
$lang->gitlab->zentaoProduct = '禪道產品';
$lang->gitlab->objectType    = '類型'; // task, bug, story

$lang->gitlab->id             = 'ID';
$lang->gitlab->name           = "{$lang->gitlab->common}名稱";
$lang->gitlab->url            = '服務地址';
$lang->gitlab->token          = 'Token';
$lang->gitlab->defaultProject = '預設項目';
$lang->gitlab->private        = 'MD5驗證';

$lang->gitlab->lblCreate  = '添加GitLab伺服器';
$lang->gitlab->desc       = '描述';
$lang->gitlab->tokenFirst = 'Token不為空時，優先使用Token。';
$lang->gitlab->tips       = '使用密碼時，請在GitLab全局安全設置中禁用"防止跨站點請求偽造"選項。';

$lang->gitlab->placeholder = new stdclass;
$lang->gitlab->placeholder->name  = '';
$lang->gitlab->placeholder->url   = "請填寫Gitlab Server首頁的訪問地址，如：https://gitlab.zentao.net。";
$lang->gitlab->placeholder->token = "請填寫具有admin權限賬戶的access token";

$lang->gitlab->noImportableIssues = "目前沒有可供導入的issue。";
$lang->gitlab->tokenError         = "當前token非管理員權限。";
$lang->gitlab->hostError          = "無效的GitLab服務地址。";
$lang->gitlab->bindUserError      = "不能重複綁定用戶 %s";
$lang->gitlab->importIssueError   = "未選擇該issue所屬的執行。";
$lang->gitlab->importIssueWarn    = "存在導入失敗的issue，可再次嘗試導入。";
