<?php
/**
 * The install module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id: zh-tw.php 4972 2013-07-02 06:50:10Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->install = new stdclass();

$lang->install->common  = '安裝';
$lang->install->next    = '下一步';
$lang->install->pre     = '返回';
$lang->install->reload  = '刷新';
$lang->install->error   = '錯誤 ';

$lang->install->officeDomain     = 'https://www.zentao.net';

$lang->install->start            = '開始安裝';
$lang->install->keepInstalling   = '繼續安裝當前版本';
$lang->install->seeLatestRelease = '看看最新的版本';
$lang->install->welcome          = '歡迎使用禪道項目管理軟件！';
$lang->install->license          = '禪道項目管理軟件使用 Z PUBLIC LICENSE(ZPL) 1.2 授權協議';
$lang->install->desc             = <<<EOT
禪道項目管理軟件(ZenTaoPMS)是一款國產的，基于<a href='http://zpl.pub' target='_blank'>ZPL</a>協議，開源免費的項目管理軟件，它集產品管理、項目管理、測試管理於一體，同時還包含了事務管理、組織管理等諸多功能，是中小型企業項目管理的首選。

禪道項目管理軟件使用PHP + MySQL開發，基于自主的PHP開發框架──ZenTaoPHP而成。第三方開發者或者企業可以非常方便的開發插件或者進行定製。
EOT;
$lang->install->links = <<<EOT
禪道項目管理軟件由<strong><a href='http://www.cnezsoft.com' target='_blank' class='text-danger'>青島易軟天創網絡科技有限公司</a>開發</strong>。
官方網站：<a href='https://www.zentao.net' target='_blank'>https://www.zentao.net</a>
技術支持：<a href='https://www.zentao.net/ask/' target='_blank'>https://www.zentao.net/ask/</a>
新浪微博：<a href='http://weibo.com/easysoft' target='_blank'>http://weibo.com/easysoft</a>



您現在正在安裝的版本是 <strong class='text-danger'>%s</strong>。
EOT;

$lang->install->newReleased= "<strong class='text-danger'>提示</strong>：官網網站已有最新版本<strong class='text-danger'>%s</strong>, 發佈日期于 %s。";
$lang->install->or         = '或者';
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
$lang->install->json         = 'JSON擴展';
$lang->install->jsonFail     = '修改PHP配置檔案，加載JSON擴展。';
$lang->install->openssl      = 'OPENSSL擴展';
$lang->install->opensslFail  = '修改PHP配置檔案，加載OPENSSL擴展。';
$lang->install->mbstring     = 'MBSTRING擴展';
$lang->install->mbstringFail = '修改PHP配置檔案，加載MBSTRING擴展。';
$lang->install->zlib         = 'ZLIB擴展';
$lang->install->zlibFail     = '修改PHP配置檔案，加載ZLIB擴展。';
$lang->install->curl         = 'CURL擴展';
$lang->install->curlFail     = '修改PHP配置檔案，加載CURL擴展。';
$lang->install->filter       = 'FILTER擴展';
$lang->install->filterFail   = '修改PHP配置檔案，加載FILTER擴展。';
$lang->install->gd           = 'GD擴展';
$lang->install->gdFail       = '修改PHP配置檔案，加載GD擴展。';
$lang->install->iconv        = 'ICONV擴展';
$lang->install->iconvFail    = '修改PHP配置檔案，加載ICONV擴展。';
$lang->install->tmpRoot      = '臨時檔案目錄';
$lang->install->dataRoot     = '上傳檔案目錄';
$lang->install->session      = 'Session存儲目錄';
$lang->install->sessionFail  = '修改PHP配置檔案，設置session.save_path。<br />如果使用寶塔面板，可以到寶塔Web面板中“軟件商店”，打開PHP設置，到“Session配置”項，選擇files，點擊保存。老版本需要修改php配置檔案。';
$lang->install->mkdirWin     = '<p>需要創建目錄%s。命令為：<br /> mkdir %s</p>';
$lang->install->chmodWin     = '需要修改目錄 "%s" 的權限。';
$lang->install->mkdirLinux   = '<p>需要創建目錄%s。<br /> 命令為：<br /> mkdir -p %s</p>';
$lang->install->chmodLinux   = '需要修改目錄 "%s" 的權限。<br />命令為：<br />chmod o=rwx -R %s';

$lang->install->timezone       = '時區設置';
$lang->install->defaultLang    = '預設語言';
$lang->install->dbHost         = '資料庫伺服器';
$lang->install->dbHostNote     = '如果127.0.0.1無法訪問，嘗試使用localhost';
$lang->install->dbPort         = '伺服器連接埠';
$lang->install->dbEncoding     = '資料庫編碼';
$lang->install->dbUser         = '資料庫用戶名';
$lang->install->dbPassword     = '資料庫密碼';
$lang->install->dbName         = 'PMS使用的庫';
$lang->install->dbPrefix       = '建表使用的首碼';
$lang->install->clearDB        = '清空現有數據';
$lang->install->importDemoData = '導入demo數據';
$lang->install->working        = '工作方式';

$lang->install->requestTypes['GET']       = '普通方式';
$lang->install->requestTypes['PATH_INFO'] = '靜態友好方式';

$lang->install->workingList['full']      = '完整研發管理工具';
$lang->install->workingList['onlyTest']  = '測試管理工具';
$lang->install->workingList['onlyStory'] = "需求管理工具";
$lang->install->workingList['onlyTask']  = '任務管理工具';

$lang->install->errorConnectDB      = '資料庫連接失敗 ';
$lang->install->errorDBName         = '資料庫名不能含有 “.” ';
$lang->install->errorCreateDB       = '資料庫創建失敗';
$lang->install->errorTableExists    = '數據表已經存在，您之前應該有安裝過禪道，繼續安裝請返回前頁並選擇清空數據';
$lang->install->errorCreateTable    = '創建表失敗';
$lang->install->errorImportDemoData = '導入demo數據失敗';

$lang->install->setConfig  = '生成配置檔案';
$lang->install->key        = '配置項';
$lang->install->value      = '值';
$lang->install->saveConfig = '保存配置檔案';
$lang->install->save2File  = '<div class="alert alert-warning">拷貝上面文本框中的內容，將其保存到 "<strong> %s </strong>"中。您以後還可繼續修改此配置檔案。</div>';
$lang->install->saved2File = '配置信息已經成功保存到" <strong>%s</strong> "中。您後面還可繼續修改此檔案。';
$lang->install->errorNotSaveConfig = '還沒有保存配置檔案';

$lang->install->getPriv  = '設置帳號';
$lang->install->company  = '公司名稱';
$lang->install->account  = '管理員帳號';
$lang->install->password = '管理員密碼';
$lang->install->errorEmptyPassword = '密碼不能為空';

$lang->install->groupList['ADMIN']['name']   = '管理員';
$lang->install->groupList['ADMIN']['desc']   = '系統管理員';
$lang->install->groupList['DEV']['name']     = '研發';
$lang->install->groupList['DEV']['desc']     = '研發人員';
$lang->install->groupList['QA']['name']      = '測試';
$lang->install->groupList['QA']['desc']      = '測試人員';
$lang->install->groupList['PM']['name']      = '項目經理';
$lang->install->groupList['PM']['desc']      = '項目經理';
$lang->install->groupList['PO']['name']      = '產品經理';
$lang->install->groupList['PO']['desc']      = '產品經理';
$lang->install->groupList['TD']['name']      = '研發主管';
$lang->install->groupList['TD']['desc']      = '研發主管';
$lang->install->groupList['PD']['name']      = '產品主管';
$lang->install->groupList['PD']['desc']      = '產品主管';
$lang->install->groupList['QD']['name']      = '測試主管';
$lang->install->groupList['QD']['desc']      = '測試主管';
$lang->install->groupList['TOP']['name']     = '高層管理';
$lang->install->groupList['TOP']['desc']     = '高層管理';
$lang->install->groupList['OTHERS']['name']  = '其他';
$lang->install->groupList['OTHERS']['desc']  = '其他';
$lang->install->groupList['LIMITED']['name'] = '受限用戶';
$lang->install->groupList['LIMITED']['desc'] = '受限用戶分組(只能編輯與自己相關的內容)';

$lang->install->cronList[''] = '監控定時任務';
$lang->install->cronList['moduleName=project&methodName=computeburn']   = '更新燃盡圖';
$lang->install->cronList['moduleName=report&methodName=remind']         = '每日任務提醒';
$lang->install->cronList['moduleName=svn&methodName=run']               = '同步SVN';
$lang->install->cronList['moduleName=git&methodName=run']               = '同步GIT';
$lang->install->cronList['moduleName=backup&methodName=backup']         = '備份數據和附件';
$lang->install->cronList['moduleName=mail&methodName=asyncSend']        = '非同步發信';
$lang->install->cronList['moduleName=webhook&methodName=asyncSend']     = '非同步發送Webhook';
$lang->install->cronList['moduleName=admin&methodName=deleteLog']       = '刪除過期日誌';
$lang->install->cronList['moduleName=todo&methodName=createCycle']      = '生成周期性待辦';
$lang->install->cronList['moduleName=ci&methodName=initQueue']          = '創建周期性任務';
$lang->install->cronList['moduleName=ci&methodName=checkCompileStatus'] = '同步Jenkins任務狀態';
$lang->install->cronList['moduleName=ci&methodName=exec']               = '執行Jenkins任務';

$lang->install->success  = "安裝成功";
$lang->install->login    = '登錄禪道管理系統';
$lang->install->register = '禪道社區註冊';

$lang->install->joinZentao = <<<EOT
<p>您已經成功安裝禪道管理系統%s，<strong class='text-danger'>請及時刪除install.php</strong>。</p><p>友情提示：為了您及時獲得禪道的最新動態，請在禪道社區(<a href='https://www.zentao.net' class='alert-link' target='_blank'>www.zentao.net</a>)進行登記。</p>
EOT;

$lang->install->product = array('chanzhi', 'zdoo', 'xuanxuan', 'ydisk', 'meshiot');

$lang->install->promotion      = "為您推薦易軟天創旗下其他產品：";
$lang->install->chanzhi        = new stdclass();
$lang->install->chanzhi->name  = '蟬知門戶';
$lang->install->chanzhi->logo  = 'images/main/chanzhi.ico';
$lang->install->chanzhi->url   = 'http://www.chanzhi.org';
$lang->install->chanzhi->desc  = <<<EOD
<ul>
  <li>專業的企業營銷門戶系統</li>
  <li>功能豐富，操作簡潔方便</li>
  <li>大量細節針對SEO優化</li>
  <li>開源免費，不限商用！</li>
</ul>
EOD;

$lang->install->zdoo = new stdclass();
$lang->install->zdoo->name  = 'ZDOO';
$lang->install->zdoo->logo  = 'images/main/zdoo.ico';
$lang->install->zdoo->url   = 'http://www.zdoo.com';
$lang->install->zdoo->desc  = <<<EOD
<ul>
  <li>客戶管理，訂單跟蹤</li>
  <li>項目任務，公告文檔</li>
  <li>收入支出，出帳入賬</li>
  <li>論壇博客，動態消息</li>
</ul>
EOD;

$lang->install->xuanxuan = new stdclass();
$lang->install->xuanxuan->name  = '喧喧聊天';
$lang->install->xuanxuan->logo  = 'images/main/xuanxuan.ico';
$lang->install->xuanxuan->url   = 'http://www.xuan.im';
$lang->install->xuanxuan->desc  = <<<EOD
<ul>
  <li>輕：輕量級架構，容易部署</li>
  <li>跨：真正完整跨平台解決方案</li>
  <li>美：基于Html5開發，界面美觀</li>
  <li>開：開放架構，方便二開整合</li>
</ul>
EOD;

$lang->install->ydisk = new stdclass();
$lang->install->ydisk->name  = '悅庫網盤';
$lang->install->ydisk->logo  = 'images/main/ydisk.ico';
$lang->install->ydisk->url   = 'http://www.ydisk.cn';
$lang->install->ydisk->desc  = <<<EOD
<ul>
  <li>絶對私有：只部署在自己的機器上</li>
  <li>海量存儲：只取決於您的硬碟大小</li>
  <li>極限傳輸：只取決於您的網絡頻寬</li>
  <li>極度安全：十二種權限組合</li>
</ul>
EOD;

$lang->install->meshiot = new stdclass();
$lang->install->meshiot->name  = '易天物聯';
$lang->install->meshiot->logo  = 'images/main/meshiot.ico';
$lang->install->meshiot->url   = 'https://www.meshiot.com';
$lang->install->meshiot->desc  = <<<EOD
<ul>
  <li>超性能網關，一個可管6萬個設備</li>
  <li>自研通訊協議，2.5公里穿牆無障礙</li>
  <li>上百款感測器控製器，獨創調光系統</li>
  <li>可配電池，對既有場地無任何要求</li>
</ul>
EOD;
