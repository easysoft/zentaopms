<?php
/**
 * The admin module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: zh-tw.php 3742 2012-12-10 08:41:09Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->admin->common  = '後台管理';
$lang->admin->index   = '後台管理首頁';
$lang->admin->checkDB = '修正數據';
$lang->admin->company = '公司管理';
$lang->admin->user    = '用戶管理';
$lang->admin->group   = '分組管理';
$lang->admin->welcome = '歡迎使用禪道管理軟件後台管理系統';

$lang->admin->browseCompany = '瀏覽公司';

$lang->admin->clearData        = '清除數據';
$lang->admin->confirmClearData = '您確認要清除數據嗎？';
$lang->admin->clearDataFailed  = '清除數據失敗！';
$lang->admin->clearDataSucceed = '清除數據成功！';
$lang->admin->clearDataDesc    = <<<EOT
<strong><font color='red'>清除數據存在一定的風險，清楚數據之前，我們強烈建議您備份資料庫及相應的數據檔案，並保證清楚數據的時候，沒有其他人進行操作。</font></strong>\n
清除數據對資料庫的影響如下：
1、清除數據不會對company, group, groupPriv表進行操作。
2、如果安裝的時候有導入demo數據，則會刪除config表key=showDemoUsers的記錄，並刪除user表中的所有demo用戶。
3、對於其他表則進行全部清除操作。
EOT;

$lang->admin->info = new stdclass();
$lang->admin->info->caption = '禪道系統信息';
$lang->admin->info->version = '當前系統的版本是%s，';
$lang->admin->info->links   = '您可以訪問以下連結：';
$lang->admin->info->account = "您的禪道社區賬戶為%s。";

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "友情提示：您還未在禪道社區(www.zentao.net)登記，%s進行登記，以及時獲得禪道最新信息。";
$lang->admin->notice->ignore   = "不再提示";

$lang->admin->register = new stdclass();
$lang->admin->register->caption    = '禪道社區登記';
$lang->admin->register->click      = '點擊此處';
$lang->admin->register->lblAccount = '請設置您的用戶名，英文字母和數字的組合，三位以上。';
$lang->admin->register->lblPasswd  = '請設置您的密碼。數字和字母的組合，六位以上。';
$lang->admin->register->submit     = '登記';
$lang->admin->register->bind       = "如果您已經擁有社區賬號，%s關聯賬戶";
$lang->admin->register->success    = "登記賬戶成功";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption  = '關聯社區賬號';
$lang->admin->bind->action   = '關聯';
$lang->admin->bind->success  = "關聯賬戶成功";
