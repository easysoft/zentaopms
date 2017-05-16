<?php
/**
 * The admin module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: zh-tw.php 4767 2013-05-05 06:10:13Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->admin->common        = '後台管理';
$lang->admin->index         = '後台管理首頁';
$lang->admin->checkDB       = '檢查資料庫';
$lang->admin->sso           = '然之整合';
$lang->admin->safeIndex     = '安全';
$lang->admin->checkWeak     = '弱口令檢查';
$lang->admin->certifyMobile = '認證手機';
$lang->admin->certifyEmail  = '認證郵箱';
$lang->admin->ztCompany     = '認證公司';
$lang->admin->captcha       = '驗證碼';
$lang->admin->getCaptcha    = '獲取驗證碼';

$lang->admin->info = new stdclass();
$lang->admin->info->version = '當前系統的版本是%s，';
$lang->admin->info->links   = '您可以訪問以下連結：';
$lang->admin->info->account = "您的禪道社區賬戶為%s。";

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "友情提示：您還未在禪道社區(www.zentao.net)登記，%s進行登記，以及時獲得禪道最新信息。";
$lang->admin->notice->ignore   = "不再提示";

$lang->admin->register = new stdclass();
$lang->admin->register->common     = '註冊新帳號綁定';
$lang->admin->register->caption    = '禪道社區登記';
$lang->admin->register->click      = '點擊此處';
$lang->admin->register->lblAccount = '請設置您的用戶名，英文字母和數字的組合，三位以上。';
$lang->admin->register->lblPasswd  = '請設置您的密碼。數字和字母的組合，六位以上。';
$lang->admin->register->submit     = '登記';
$lang->admin->register->bind       = "綁定已有帳號";
$lang->admin->register->success    = "登記賬戶成功";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = '關聯社區帳號';
$lang->admin->bind->success = "關聯賬戶成功";

$lang->admin->safe = new stdclass();
$lang->admin->safe->common     = '安全策略';
$lang->admin->safe->set        = '密碼安全設置';
$lang->admin->safe->password   = '密碼安全';
$lang->admin->safe->weak       = '常用弱口令';
$lang->admin->safe->reason     = '類型';
$lang->admin->safe->checkWeak  = '弱口令掃瞄';
$lang->admin->safe->changeWeak = '修改弱口令密碼';
$lang->admin->safe->modifyPasswordFirstLogin = '首次登陸修改密碼';

$lang->admin->safe->modeList[0] = '不檢查';
$lang->admin->safe->modeList[1] = '中';
$lang->admin->safe->modeList[2] = '強';

$lang->admin->safe->modeRuleList[1] = '6位以上，包含大小寫字母，數字。';
$lang->admin->safe->modeRuleList[2] = '10位以上，包含字母，數字，特殊字元。';

$lang->admin->safe->reasonList['weak']     = '常用弱口令';
$lang->admin->safe->reasonList['account']  = '與帳號相同';
$lang->admin->safe->reasonList['mobile']   = '與手機相同';
$lang->admin->safe->reasonList['phone']    = '與電話相同';
$lang->admin->safe->reasonList['birthday'] = '與生日相同';

$lang->admin->safe->modifyPasswordList[1] = '必須修改';
$lang->admin->safe->modifyPasswordList[0] = '不強制';

$lang->admin->safe->noticeMode   = '系統會在登錄、創建和修改用戶、修改密碼的時候檢查用戶口令。';
$lang->admin->safe->noticeStrong = '密碼長度越長，含有大寫字母或數字或特殊符號越多，密碼字母越不重複，安全度越強！';
