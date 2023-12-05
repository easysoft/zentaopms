<?php
/**
 * The user module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: zh-tw.php 5053 2013-07-06 08:17:37Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->user->common           = '用戶';
$lang->user->id               = '用戶編號';
$lang->user->inside           = '內部人員';
$lang->user->outside          = '外部人員';
$lang->user->company          = '所屬公司';
$lang->user->dept             = '部門';
$lang->user->account          = '用戶名';
$lang->user->password         = '密碼';
$lang->user->password2        = '請重複密碼';
$lang->user->role             = '職位';
$lang->user->group            = '權限分組';
$lang->user->realname         = '姓名';
$lang->user->nickname         = '暱稱';
$lang->user->commiter         = '原始碼帳號';
$lang->user->birthyear        = '出生年';
$lang->user->gender           = '性別';
$lang->user->email            = '郵箱';
$lang->user->basicInfo        = '基本信息';
$lang->user->accountInfo      = '帳號信息';
$lang->user->verify           = '安全驗證';
$lang->user->contactInfo      = '聯繫方式';
$lang->user->skype            = 'Skype';
$lang->user->qq               = 'QQ';
$lang->user->mobile           = '手機';
$lang->user->phone            = '電話';
$lang->user->weixin           = '微信';
$lang->user->dingding         = '釘釘';
$lang->user->slack            = 'Slack';
$lang->user->whatsapp         = 'WhatsApp';
$lang->user->address          = '通訊地址';
$lang->user->zipcode          = '郵編';
$lang->user->join             = '入職日期';
$lang->user->visits           = '訪問次數';
$lang->user->ip               = '最後IP';
$lang->user->last             = '最後登錄';
$lang->user->ranzhi           = 'ZDOO帳號';
$lang->user->ditto            = '同上';
$lang->user->originalPassword = '原密碼';
$lang->user->newPassword      = '新密碼';
$lang->user->verifyPassword   = '您的密碼';
$lang->user->resetPassword    = '忘記密碼';
$lang->user->score            = '積分';
$lang->user->name             = '名稱';
$lang->user->type             = '用戶類型';
$lang->user->cropAvatar       = '剪切頭像';
$lang->user->cropAvatarTip    = '拖拽選框來選擇頭像剪切範圍';
$lang->user->cropImageTip     = '所使用的頭像圖片過小，建議圖片大小至少為 48x48，當前圖片大小為 %s';
$lang->user->captcha          = '驗證碼';
$lang->user->avatar           = '用戶頭像';
$lang->user->birthday         = '生日';
$lang->user->nature           = '性格特徵';
$lang->user->analysis         = '影響分析';
$lang->user->strategy         = '應對策略';
$lang->user->fails            = '失敗次數';
$lang->user->locked           = '鎖住日期';
$lang->user->scoreLevel       = '積分等級';
$lang->user->clientStatus     = '登錄狀態';
$lang->user->clientLang       = '客戶端語言';
$lang->user->programs         = '項目集';
$lang->user->products         = $lang->productCommon;
$lang->user->projects         = '項目';
$lang->user->sprints          = $lang->execution->common;
$lang->user->identity         = '身份';

$lang->user->legendBasic        = '基本資料';
$lang->user->legendContribution = '個人貢獻';

$lang->user->index         = "用戶視圖首頁";
$lang->user->view          = "用戶詳情";
$lang->user->create        = "添加用戶";
$lang->user->batchCreate   = "批量添加用戶";
$lang->user->edit          = "編輯用戶";
$lang->user->batchEdit     = "批量編輯";
$lang->user->unlock        = "解鎖用戶";
$lang->user->delete        = "刪除用戶";
$lang->user->unbind        = "解除ZDOO綁定";
$lang->user->login         = "用戶登錄";
$lang->user->bind          = "綁定已有賬戶";
$lang->user->oauthRegister = "註冊新賬號";
$lang->user->mobileLogin   = "手機訪問";
$lang->user->editProfile   = "編輯檔案";
$lang->user->deny          = "訪問受限";
$lang->user->confirmDelete = "您確定刪除該用戶嗎？";
$lang->user->confirmUnlock = "您確定解除該用戶的鎖定狀態嗎？";
$lang->user->confirmUnbind = "您確定解除該用戶跟ZDOO的綁定嗎？";
$lang->user->relogin       = "重新登錄";
$lang->user->asGuest       = "遊客訪問";
$lang->user->goback        = "返回前一頁";
$lang->user->deleted       = '(已刪除)';
$lang->user->search        = '搜索';
$lang->user->else          = '其他';

$lang->user->saveTemplate          = '保存模板';
$lang->user->setPublic             = '設為公共模板';
$lang->user->deleteTemplate        = '刪除模板';
$lang->user->setTemplateTitle      = '請輸入模板標題';
$lang->user->applyTemplate         = '應用模板';
$lang->user->confirmDeleteTemplate = '您確認要刪除該模板嗎？';
$lang->user->setPublicTemplate     = '設為公共模板';
$lang->user->tplContentNotEmpty    = '模板內容不能為空!';

$lang->user->profile   = '檔案';
$lang->user->project   = $lang->executionCommon;
$lang->user->execution = $lang->execution->common;
$lang->user->task      = '任務';
$lang->user->bug       = 'Bug';
$lang->user->test      = '測試';
$lang->user->testTask  = '測試單';
$lang->user->testCase  = '用例';
$lang->user->issue     = '問題';
$lang->user->risk      = '風險';
$lang->user->schedule  = '日程';
$lang->user->todo      = '待辦';
$lang->user->story     = $lang->SRCommon;
$lang->user->dynamic   = '動態';

$lang->user->openedBy    = '由%s創建';
$lang->user->assignedTo  = '指派給%s';
$lang->user->finishedBy  = '由%s完成';
$lang->user->resolvedBy  = '由%s解決';
$lang->user->closedBy    = '由%s關閉';
$lang->user->reviewedBy  = '由%s評審';
$lang->user->canceledBy  = '由%s取消';

$lang->user->testTask2Him = '%s負責的';
$lang->user->case2Him     = '指派給%s';
$lang->user->caseByHim    = '由%s創建';

$lang->user->errorDeny    = "抱歉，您無權訪問『<b>%s</b>』模組的『<b>%s</b>』功能。請聯繫管理員獲取權限。請回到地盤或重新登錄。";
$lang->user->errorView    = "抱歉，您無權訪問『<b>%s</b>』視圖。請聯繫管理員獲取權限。請回到地盤或重新登錄。";
$lang->user->loginFailed  = "登錄失敗，請檢查您的用戶名或密碼是否填寫正確。";
$lang->user->lockWarning  = "您還有%s次嘗試機會。";
$lang->user->loginLocked  = "密碼嘗試次數太多，請聯繫管理員解鎖，或%s分鐘後重試。";
$lang->user->weakPassword = "您的密碼強度小於系統設定。";
$lang->user->errorWeak    = "密碼不能使用【%s】這些常用弱口令。";
$lang->user->errorCaptcha = "驗證碼不正確！";

$lang->user->roleList['']       = '';
$lang->user->roleList['dev']    = '研發';
$lang->user->roleList['qa']     = '測試';
$lang->user->roleList['pm']     = '項目經理';
$lang->user->roleList['po']     = '產品經理';
$lang->user->roleList['td']     = '研發主管';
$lang->user->roleList['pd']     = '產品主管';
$lang->user->roleList['qd']     = '測試主管';
$lang->user->roleList['top']    = '高層管理';
$lang->user->roleList['others'] = '其他';

$lang->user->genderList['m'] = '男';
$lang->user->genderList['f'] = '女';

$lang->user->thirdPerson['m'] = '他';
$lang->user->thirdPerson['f'] = '她';

$lang->user->typeList['inside']  = $lang->user->inside;
$lang->user->typeList['outside'] = $lang->user->outside;

$lang->user->passwordStrengthList[0] = "<span style='color:red'>弱</span>";
$lang->user->passwordStrengthList[1] = "<span style='color:#000'>中</span>";
$lang->user->passwordStrengthList[2] = "<span style='color:green'>強</span>";

$lang->user->statusList['active'] = '正常';
$lang->user->statusList['delete'] = '刪除';

$lang->user->personalData['createdTodos']        = '創建的待辦數';
$lang->user->personalData['createdRequirements'] = "創建的用需/史詩數";
$lang->user->personalData['createdStories']      = "創建的軟需/故事數";
$lang->user->personalData['finishedTasks']       = '完成的任務數';
$lang->user->personalData['createdBugs']         = '提交的Bug數';
$lang->user->personalData['resolvedBugs']        = '解決的Bug數';
$lang->user->personalData['createdCases']        = '創建的用例數';
$lang->user->personalData['createdRisks']        = '創建的風險數';
$lang->user->personalData['resolvedRisks']       = '解決的風險數';
$lang->user->personalData['createdIssues']       = '創建的問題數';
$lang->user->personalData['resolvedIssues']      = '解決的問題數';
$lang->user->personalData['createdDocs']         = '創建的文檔數';

$lang->user->keepLogin['on']   = '保持登錄';
$lang->user->loginWithDemoUser = '使用demo帳號登錄：';
$lang->user->scanToLogin       = '掃一掃登錄';

$lang->user->tpl = new stdclass();
$lang->user->tpl->type    = '類型';
$lang->user->tpl->title   = '模板名';
$lang->user->tpl->content = '內容';
$lang->user->tpl->public  = '是否公開';

$lang->usertpl = new stdclass();
$lang->usertpl->title = '模板名稱';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account   = '英文、數字和下劃線的組合，三位以上';
$lang->user->placeholder->password1 = '六位以上';
$lang->user->placeholder->role      = '職位影響內容和用戶列表的順序。';
$lang->user->placeholder->group     = '分組決定用戶的權限列表。';
$lang->user->placeholder->commiter  = '版本控制系統(subversion)中的帳號';
$lang->user->placeholder->verify    = '請輸入您的系統登錄密碼';

$lang->user->placeholder->loginPassword = '請輸入密碼';
$lang->user->placeholder->loginAccount  = '請輸入用戶名';
$lang->user->placeholder->loginUrl      = '請輸入禪道系統網址';

$lang->user->placeholder->passwordStrength[1] = '6位以上，包含大小寫字母，數字。';
$lang->user->placeholder->passwordStrength[2] = '10位以上，包含大小寫字母，數字，特殊字元。';

$lang->user->error = new stdclass();
$lang->user->error->account        = "【ID %s】的用戶名應該為：三位以上的英文、數字或下劃線的組合";
$lang->user->error->accountDupl    = "【ID %s】的用戶名已經存在";
$lang->user->error->realname       = "【ID %s】的真實姓名必須填寫";
$lang->user->error->password       = "【ID %s】的密碼必須為六位及以上";
$lang->user->error->mail           = "【ID %s】的郵箱地址不正確";
$lang->user->error->reserved       = "【ID %s】的用戶名已被系統預留";
$lang->user->error->weakPassword   = "【ID %s】的密碼強度小於系統設定。";
$lang->user->error->dangerPassword = "【ID %s】的密碼不能使用【%s】這些常用若口令。";

$lang->user->error->url              = "網址不正確，請聯繫管理員";
$lang->user->error->verify           = "用戶名或密碼錯誤";
$lang->user->error->verifyPassword   = "驗證失敗，請檢查您的系統登錄密碼是否正確";
$lang->user->error->originalPassword = "原密碼不正確";
$lang->user->error->companyEmpty     = "公司名稱不能為空！";
$lang->user->error->noAccess         = "該人員和你不是同一部門，你無權訪問該人員的工作信息。";

$lang->user->contactFieldList['phone']    = $lang->user->phone;
$lang->user->contactFieldList['mobile']   = $lang->user->mobile;
$lang->user->contactFieldList['qq']       = $lang->user->qq;
$lang->user->contactFieldList['dingding'] = $lang->user->dingding;
$lang->user->contactFieldList['weixin']   = $lang->user->weixin;
$lang->user->contactFieldList['skype']    = $lang->user->skype;
$lang->user->contactFieldList['slack']    = $lang->user->slack;
$lang->user->contactFieldList['whatsapp'] = $lang->user->whatsapp;

$lang->user->executionTypeList['stage']  = '階段';
$lang->user->executionTypeList['sprint'] = $lang->iterationCommon;

$lang->user->contacts = new stdclass();
$lang->user->contacts->common   = '聯繫人';
$lang->user->contacts->listName = '列表名稱';
$lang->user->contacts->userList = '用戶列表';

$lang->user->contacts->manage        = '維護列表';
$lang->user->contacts->contactsList  = '已有列表';
$lang->user->contacts->selectedUsers = '選擇用戶';
$lang->user->contacts->selectList    = '選擇列表';
$lang->user->contacts->createList    = '創建新列表';
$lang->user->contacts->noListYet     = '還沒有創建任何列表，請先創建聯繫人列表。';
$lang->user->contacts->confirmDelete = '您確定要刪除這個列表嗎？';
$lang->user->contacts->or            = ' 或者 ';

$lang->user->resetFail        = "重置密碼失敗，檢查用戶名是否存在！";
$lang->user->resetSuccess     = "重置密碼成功，請用新密碼登錄。";
$lang->user->noticeDelete     = "你確認要把“%s”從系統中刪除嗎？";
$lang->user->noticeHasDeleted = "該人員已經刪除，如需查看，請到資源回收筒還原後再查看。";
$lang->user->noticeResetFile  = "<h5>普通用戶請聯繫管理員重置密碼</h5>
    <h5>管理員請登錄禪道所在的伺服器，創建<span> '%s' </span>檔案。</h5>
    <p>注意：</p>
    <ol>
    <li>檔案內容為空。</li>
    <li>如果之前檔案存在，刪除之後重新創建。</li>
    </ol>";
$lang->user->notice4Safe = "警告：檢測到一鍵安裝包密碼口令弱";
$lang->user->process4DIR = "檢測到您可能在使用一鍵安裝包環境，該環境中其他站點還在用簡單密碼，安全起見，如果不使用其他站點，請及時處理。將 %s 目錄刪除或改名。詳情查看：<a href='https://www.zentao.net/book/zentaopmshelp/467.html' target='_blank'>https://www.zentao.net/book/zentaopmshelp/467.html</a>";
$lang->user->process4DB  = "檢測到您可能在使用一鍵安裝包環境，該環境中其他站點還在用簡單密碼，安全起見，如果不使用其他站點，請及時處理。請登錄資料庫，修改 %s 資料庫的zt_user表的password欄位。詳情查看：<a href='https://www.zentao.net/book/zentaopmshelp/467.html' target='_blank'>https://www.zentao.net/book/zentaopmshelp/467.html</a>";
$lang->user->mkdirWin = <<<EOT
    <html><head><meta charset='utf-8'></head>
    <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>
    <div style='margin-bottom:8px;'>不能創建臨時目錄，請確認目錄<strong style='color:#ed980f'>%s</strong>是否存在並有操作權限。</div>
    <div>Can't create tmp directory, make sure the directory <strong style='color:#ed980f'>%s</strong> exists and has permission to operate.</div>
    </td></tr></table></body></html>
EOT;
$lang->user->mkdirLinux = <<<EOT
    <html><head><meta charset='utf-8'></head>
    <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>
    <div style='margin-bottom:8px;'>不能創建臨時目錄，請確認目錄<strong style='color:#ed980f'>%s</strong>是否存在並有操作權限。</div>
    <div style='margin-bottom:8px;'>命令為：<strong style='color:#ed980f'>chmod 777 -R %s</strong>。</div>
    <div>Can't create tmp directory, make sure the directory <strong style='color:#ed980f'>%s</strong> exists and has permission to operate.</div>
    <div style='margin-bottom:8px;'>Command: <strong style='color:#ed980f'>chmod 777 -R %s</strong>.</div>
    </td></tr></table></body></html>
EOT;

$lang->user->zentaoapp = new stdclass();
$lang->user->zentaoapp->logout = '退出登錄';
