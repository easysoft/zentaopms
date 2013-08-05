<?php
$lang->sso->common    = '單點登錄';
$lang->sso->browse    = '應用列表';
$lang->sso->create    = '添加應用';
$lang->sso->edit      = '編輯應用';
$lang->sso->delete    = '刪除應用';
$lang->sso->code      = '代號';
$lang->sso->title     = '名稱';
$lang->sso->key       = '密鑰';
$lang->sso->ip        = 'IP列表';
$lang->sso->createKey = '重新生成密鑰';

$lang->sso->confirmDelete = '您確定刪除該應用嗎？';

$lang->sso->note = new stdClass();
$lang->sso->note->title = '授權應用名稱';
$lang->sso->note->code  = '授權應用代號';
$lang->sso->note->ip    = "允許該應用使用這些ip訪問，多個ip使用逗號隔開。支持IP段，如192.168.1.*";

$lang->sso->error = new stdClass();
$lang->sso->error->title = '名稱不能為空';
$lang->sso->error->code  = '代號不能為空';
$lang->sso->error->key   = '密鑰不能為空';
$lang->sso->error->ip    = 'IP列表不能為空';

$lang->sso->instruction = <<<EOT
<p><strong>示例應用</strong>：名稱為"測試"，代號為"test"，密鑰為"20c8eb0d522d2e1a09d4ea18e4df3a59"，IP列表為"192.168.11.*,127.0.0.1"。</p>
<p><strong>1.用戶驗證</strong></p>
<p>授權應用請求禪道的用戶驗證API，檢查用戶在該應用輸入的用戶名和密碼是否正確，實現單點登錄。</p>
<p>API地址為sso模組的auth方法，POST數據為登錄用戶的用戶名account和密碼與密鑰形成的加密字元串md5(md5(password) + key)，成功則返回用戶信息（json格式），失敗則返回fail。</p>
<p>示例：請求地址 http:://www.demo.com/sso-auth-test，POST字元串 account=admin&authcode=c44c577432230ad8e67160d3f9f0b91c。</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;註：test為應用代號，c44c577432230ad8e67160d3f9f0b91為md5(md5('123456') + '20c8eb0d522d2e1a09d4ea18e4df3a59')</p>
<p><strong>2.獲取用戶列表</strong></p>
<p>授權應用訪問禪道的用戶列表API，獲取禪道所有用戶信息。</p>
<p>API地址為sso模組的users方法，POST數據為應用密鑰，成功返回用戶列表（json格式），失敗返回fail。</p>
<p>示例：請求地址 http:://www.demo.com/sso-users-test，POST字元串 key=20c8eb0d522d2e1a09d4ea18e4df3a59。</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;註：test為應用代號，20c8eb0d522d2e1a09d4ea18e4df3a59為應用密鑰</p>
<p><strong>3.獲取部門列表</strong></p>
<p>授權應用訪問禪道的部門列表API，獲取禪道所有部門信息。</p>
<p>API地址為sso模組的depts方法，POST數據為應用密鑰，成功返回用戶列表（json格式），失敗返回fail。</p>
<p>示例：請求地址 http:://www.demo.com/sso-depts-test，POST字元串 key=20c8eb0d522d2e1a09d4ea18e4df3a59。</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;註：test為應用代號，20c8eb0d522d2e1a09d4ea18e4df3a59為應用密鑰</p>
EOT;
