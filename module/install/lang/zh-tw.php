<?php
/**
 * The install module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id: zh-tw.php 1914 2011-06-24 10:11:25Z yidong@cnezsoft.com $
 * @link        http://www.zentao.net
 */
$lang->install->common  = '安裝';
$lang->install->next    = '下一步';
$lang->install->pre     = '返回';
$lang->install->reload  = '刷新';
$lang->install->error   = '錯誤 ';

$lang->install->start            = '開始安裝';
$lang->install->keepInstalling   = '繼續安裝當前版本';
$lang->install->seeLatestRelease = '看看最新的版本';
$lang->install->welcome          = '歡迎使用禪道項目管理軟件！';
$lang->install->desc             = <<<EOT
禪道項目管理軟件(ZenTaoPMS)是一款國產的，基于LGPL協議，開源免費的項目管理軟件，它集產品管理、項目管理、測試管理於一體，同時還包含了事務管理、組織管理等諸多功能，是中小型企業項目管理的首選。

禪道項目管理軟件使用PHP + MySQL開發，基于自主的PHP開發框架──ZenTaoPHP而成。第三方開發者或者企業可以非常方便的開發插件或者進行定製。

禪道項目管理軟件由<strong><a href='http://www.cnezsoft.com' target='_blank' class='red'>青島易軟天創網絡科技有限公司</a>開發</strong>。
官方網站：<a href='http://www.zentao.net' target='_blank'>http://www.zentao.net</a>
技術支持: <a href='http://www.zentao.net/ask/' target='_blank'>http://www.zentao.net/ask/</a>
新浪微博：<a href='http://t.sina.com.cn/zentaopms' target='_blank'>t.sina.com.cn/zentaopms</a>
騰訊微博：<a href='http://t.qq.com/zentaopms/' target='_blank'>t.qq.com/zentaopms</a>
QQ交流群：102807460

您現在正在安裝的版本是 <strong class='red'>%s</strong>。
EOT;

$lang->install->newReleased= "<strong class='red'>提示</strong>：官網網站已有最新版本<strong class='red'>%s</strong>, 發佈日期于 %s。";
$lang->install->choice     = '您可以選擇：';
$lang->install->checking   = '系統檢查';
$lang->install->ok         = '檢查通過(√)';
$lang->install->fail       = '檢查失敗(×)';
$lang->install->loaded     = '已加載';
$lang->install->unloaded   = '未加載';
$lang->install->exists     = '目錄存在 ';
$lang->install->notExists  = '目錄不存在 ';
$lang->install->writable   = '目錄可寫 ';
$lang->install->notWritable= '目錄不可寫 ';
$lang->install->phpINI     = 'PHP配置檔案';
$lang->install->checkItem  = '檢查項';
$lang->install->current    = '當前配置';
$lang->install->result     = '檢查結果';
$lang->install->action     = '如何修改';

$lang->install->phpVersion = 'PHP版本';
$lang->install->phpFail    = 'PHP版本必須大於5.2.0';

$lang->install->pdo          = 'PDO擴展';
$lang->install->pdoFail      = '修改PHP配置檔案，加載PDO擴展。';
$lang->install->pdoMySQL     = 'PDO_MySQL擴展';
$lang->install->pdoMySQLFail = '修改PHP配置檔案，加載pdo_mysql擴展。';
$lang->install->tmpRoot      = '臨時檔案目錄';
$lang->install->dataRoot     = '上傳檔案目錄';
$lang->install->mkdir        = '<p>需要創建目錄%s。<br /> linux下面命令為：<br /> mkdir -p %s</p>';
$lang->install->chmod        = '需要修改目錄 "%s" 的權限。<br />linux下面命令為：<br />chmod o=rwx -R %s';

$lang->install->settingDB   = '設置資料庫';
$lang->install->webRoot     = 'PMS所在網站目錄';
$lang->install->requestType = 'URL方式';
$lang->install->defaultLang = '預設語言';
$lang->install->dbHost      = '資料庫伺服器';
$lang->install->dbHostNote  = '如果localhost無法訪問，嘗試使用127.0.0.1';
$lang->install->dbPort      = '伺服器連接埠';
$lang->install->dbUser      = '資料庫用戶名';
$lang->install->dbPassword  = '資料庫密碼';
$lang->install->dbName      = 'PMS使用的庫';
$lang->install->dbPrefix    = '建表使用的首碼';
$lang->install->createDB    = '自動創建資料庫';
$lang->install->clearDB     = '清空現有數據';

$lang->install->requestTypes['GET']       = '普通方式';
$lang->install->requestTypes['PATH_INFO'] = '靜態友好方式';

$lang->install->errorConnectDB     = '資料庫連接失敗 ';
$lang->install->errorCreateDB      = '資料庫創建失敗';
$lang->install->errorDBExists      = '資料庫已經存在，繼續安裝請選擇清空數據';
$lang->install->errorCreateTable   = '創建表失敗';

$lang->install->setConfig  = '生成配置檔案';
$lang->install->key        = '配置項';
$lang->install->value      = '值';
$lang->install->saveConfig = '保存配置檔案';
$lang->install->save2File  = '<div class="a-center"><span class="fail">嘗試寫入配置檔案，失敗！</span></div>拷貝上面文本框中的內容，將其保存到 "<strong> %s </strong>"中。您以後還可繼續修改此配置檔案。';
$lang->install->saved2File = '配置信息已經成功保存到" <strong>%s</strong> "中。您後面還可繼續修改此檔案。';
$lang->install->errorNotSaveConfig = '還沒有保存配置檔案';

$lang->install->getPriv  = '設置帳號';
$lang->install->company  = '公司名稱';
$lang->install->pms      = 'PMS地址';
$lang->install->pmsNote  = '即通過什麼地址可以訪問到禪道項目管理，設置域名或者IP地址即可，不需要http';
$lang->install->account  = '管理員帳號';
$lang->install->password = '管理員密碼';
$lang->install->errorEmptyPassword = '密碼不能為空';

$lang->install->success = "安裝成功！請刪除install.php，登錄禪道管理系統，設置用戶及分組！";

