<?php
/**
 * The user module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: zh-tw.php 3885 2012-12-24 08:55:41Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->user->common    = '用戶';
$lang->user->id        = '用戶編號';
$lang->user->company   = '所屬公司';
$lang->user->dept      = '所屬部門';
$lang->user->account   = '用戶名';
$lang->user->password  = '密碼';
$lang->user->password2 = '請重複密碼';
$lang->user->role      = '角色';
$lang->user->realname  = '真實姓名';
$lang->user->nickname  = '暱稱';
$lang->user->commiter  = '原始碼帳號';
$lang->user->avatar    = '頭像';
$lang->user->birthyear = '出生年';
$lang->user->gender    = '性別';
$lang->user->email     = '郵箱';
$lang->user->msn       = 'MSN';
$lang->user->qq        = 'QQ';
$lang->user->yahoo     = '雅虎通';
$lang->user->gtalk     = 'GTalk';
$lang->user->wangwang  = '旺旺';
$lang->user->mobile    = '手機';
$lang->user->phone     = '電話';
$lang->user->address   = '通訊地址';
$lang->user->zipcode   = '郵編';
$lang->user->join      = '加入日期';
$lang->user->visits    = '訪問次數';
$lang->user->ip        = '最後IP';
$lang->user->last      = '最後登錄';
$lang->user->status    = '狀態';
$lang->user->ditto     = '同上';

$lang->user->index           = "用戶視圖首頁";
$lang->user->view            = "用戶詳情";
$lang->user->create          = "添加用戶";
$lang->user->batchCreate     = "批量添加用戶";
$lang->user->read            = "查看用戶";
$lang->user->edit            = "編輯用戶";
$lang->user->batchEdit       = "批量編輯";
$lang->user->unlock          = "解鎖用戶";
$lang->user->update          = "編輯用戶";
$lang->user->delete          = "刪除用戶";
$lang->user->browse          = "瀏覽用戶";
$lang->user->login           = "用戶登錄";
$lang->user->userView        = "人員視圖";
$lang->user->editProfile     = "修改個人信息";
$lang->user->editPassword    = "修改密碼";
$lang->user->deny            = "訪問受限";
$lang->user->confirmDelete   = "您確定刪除該用戶嗎？";
$lang->user->confirmActivate = "您確定激活該用戶嗎？";
$lang->user->confirmUnlock   = "您確定解除該用戶的鎖定狀態嗎？";
$lang->user->relogin         = "重新登錄";
$lang->user->asGuest         = "遊客訪問";
$lang->user->goback          = "返回前一頁";
$lang->user->allUsers        = '全部用戶';
$lang->user->deleted         = '(已刪除)';
$lang->user->select          = '--請選擇用戶--';

$lang->user->profile     = '檔案';
$lang->user->project     = '項目';
$lang->user->task        = '任務';
$lang->user->bug         = '缺陷';
$lang->user->todo        = '待辦';
$lang->user->story       = '需求';
$lang->user->team        = '團隊';
$lang->user->dynamic     = '動態';
$lang->user->ajaxGetUser = '介面:獲得用戶';
$lang->user->editProfile = '修改信息';

$lang->user->errorDeny   = "抱歉，您無權訪問『<b>%s</b>』模組的『<b>%s</b>』功能。請聯繫管理員獲取權限。點擊後退返回上頁。";
$lang->user->loginFailed = "登錄失敗，請檢查您的用戶名或密碼是否填寫正確。";
$lang->user->lockWarning = "您還有%s次嘗試機會。";
$lang->user->loginLocked = "密碼嘗試次數太多，請聯繫管理員解鎖，或%s分鐘後重試。";

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

$lang->user->statusList['active'] = '正常';
$lang->user->statusList['delete'] = '刪除';

$lang->user->keepLogin['on']      = '保持登錄';
$lang->user->loginWithDemoUser    = '使用demo賬號登錄：';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account   = '英文、數字和下劃線的組合，三位以上';
$lang->user->placeholder->password1 = '六位以上';
$lang->user->placeholder->join      = '入職日期';
$lang->user->placeholder->commiter  = '版本控制系統(subversion)中的帳號';

$lang->user->error = new stdclass();
$lang->user->error->account       = "ID %s，用戶名必須三位以上";
$lang->user->error->accountDupl   = "ID %s，該用戶名已經存在";
$lang->user->error->realname      = "ID %s，必須填寫真實姓名";
$lang->user->error->password      = "ID %s，密碼必須六位以上";
$lang->user->error->mail          = "ID %s，請填寫正確的郵箱地址";
$lang->user->error->role          = "ID %s，角色不能為空";

$lang->user->contacts = new stdclass();
$lang->user->contacts->common   = '聯繫人';
$lang->user->contacts->listName = '列表名稱';
$lang->user->contacts->userList = '聯繫人列表';

$lang->user->contacts->manage       = '維護列表';
$lang->user->contacts->contactsList = '已有列表';
$lang->user->contacts->selectedUsers= '選擇用戶';
$lang->user->contacts->selectList   = '選擇列表';
$lang->user->contacts->appendToList = '追加至已有列表：';
$lang->user->contacts->createList   = '創建新列表：';
$lang->user->contacts->noListYet    = '還沒有創建任何列表。';
$lang->user->contacts->confirmDelete= '您確定要刪除這個列表嗎？';
$lang->user->contacts->or           = ' 或者 ';
