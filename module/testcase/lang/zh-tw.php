<?php
/**
 * The testcase module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: zh-tw.php 4764 2013-05-05 04:07:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->testcase->id               = '用例編號';
$lang->testcase->product          = "所屬{$lang->productCommon}";
$lang->testcase->module           = '所屬模組';
$lang->testcase->lib              = "所屬庫";
$lang->testcase->branch           = "分支/平台";
$lang->testcase->moduleAB         = '模組';
$lang->testcase->story            = '相關需求';
$lang->testcase->title            = '用例標題';
$lang->testcase->precondition     = '前置條件';
$lang->testcase->pri              = '優先順序';
$lang->testcase->type             = '用例類型';
$lang->testcase->status           = '用例狀態';
$lang->testcase->steps            = '用例步驟';
$lang->testcase->openedBy         = '由誰創建';
$lang->testcase->openedDate       = '創建日期';
$lang->testcase->lastEditedBy     = '最後修改者';
$lang->testcase->result           = '測試結果';
$lang->testcase->real             = '實際情況';
$lang->testcase->keywords         = '關鍵詞';
$lang->testcase->files            = '附件';
$lang->testcase->linkCase         = '相關用例';
$lang->testcase->linkCases        = '關聯相關用例';
$lang->testcase->unlinkCase       = '移除相關用例';
$lang->testcase->stage            = '適用階段';
$lang->testcase->reviewedBy       = '由誰評審';
$lang->testcase->reviewedDate     = '評審時間';
$lang->testcase->reviewResult     = '評審結果';
$lang->testcase->lastEditedByAB   = '修改者';
$lang->testcase->lastEditedDateAB = '修改日期';
$lang->testcase->lastEditedDate   = '修改日期';
$lang->testcase->version          = '用例版本';
$lang->testcase->lastRunner       = '執行人';
$lang->testcase->lastRunDate      = '執行時間';
$lang->testcase->assignedTo       = '指派給';
$lang->testcase->colorTag         = '顏色標籤';
$lang->testcase->lastRunResult    = '結果';
$lang->testcase->allProduct       = "所有{$lang->productCommon}";
$lang->testcase->fromBug          = '來源Bug';
$lang->testcase->toBug            = '生成Bug';
$lang->testcase->changed          = '用例變更';
$lang->testcase->bugs             = '產生Bug數';
$lang->testcase->bugsAB           = 'B';
$lang->testcase->results          = '執行結果數';
$lang->testcase->resultsAB        = 'R';
$lang->testcase->stepNumber       = '用例步驟數';
$lang->testcase->stepNumberAB     = 'S';
$lang->testcase->createBug        = '轉Bug';
$lang->testcase->fromModule       = '來源模組';
$lang->case = $lang->testcase;  // 用於DAO檢查時使用。因為case是系統關鍵字，所以無法定義該模組為case，只能使用testcase，但表還是使用的case。

$lang->testcase->stepID      = '編號';
$lang->testcase->stepDesc    = '步驟';
$lang->testcase->stepExpect  = '預期';
$lang->testcase->stepVersion = '版本';

$lang->testcase->common                  = '用例';
$lang->testcase->index                   = "用例管理首頁";
$lang->testcase->create                  = "建用例";
$lang->testcase->batchCreate             = "批量添加";
$lang->testcase->delete                  = "刪除用例";
$lang->testcase->view                    = "用例詳情";
$lang->testcase->review                  = "評審";
$lang->testcase->batchReview             = "批量評審";
$lang->testcase->edit                    = "編輯";
$lang->testcase->batchEdit               = "批量編輯 ";
$lang->testcase->batchChangeModule       = "批量修改模組";
$lang->testcase->delete                  = "刪除";
$lang->testcase->batchDelete             = "批量刪除 ";
$lang->testcase->batchConfirmStoryChange = "批量確認變更";
$lang->testcase->batchCaseTypeChange     = "批量修改類型";
$lang->testcase->browse                  = "用例列表";
$lang->testcase->groupCase               = "分組瀏覽用例";
$lang->testcase->import                  = "導入";
$lang->testcase->importFile              = "導入CSV";
$lang->testcase->importFromLib           = "從用例庫中導入";
$lang->testcase->showImport              = "顯示導入內容";
$lang->testcase->exportTemplet           = "導出模板";
$lang->testcase->export                  = "導出數據";
$lang->testcase->reportChart             = '報表統計';
$lang->testcase->confirmChange           = '確認用例變動';
$lang->testcase->confirmStoryChange      = '確認需求變動';
$lang->testcase->copy                    = '複製用例';
$lang->testcase->group                   = '分組';
$lang->testcase->groupName               = '分組名稱';
$lang->testcase->step                    = '步驟';
$lang->testcase->stepChild               = '子步驟';

$lang->testcase->new = '新增';

$lang->testcase->num = '用例記錄數：';

$lang->testcase->deleteStep   = '刪除';
$lang->testcase->insertBefore = '之前添加';
$lang->testcase->insertAfter  = '之後添加';

$lang->testcase->assignToMe    = '給我的用例';
$lang->testcase->openedByMe    = '我建的用例';
$lang->testcase->allCases      = '所有';
$lang->testcase->needConfirm   = '需求變動';
$lang->testcase->bySearch      = '搜索';

$lang->testcase->lblStory                    = '相關需求';
$lang->testcase->lblLastEdited               = '最後編輯';
$lang->testcase->lblTypeValue                = '類型可選值列表';
$lang->testcase->lblStageValue               = '階段可選值列表';
$lang->testcase->lblStatusValue              = '狀態可選值列表';

$lang->testcase->legendBasicInfo    = '基本信息';
$lang->testcase->legendAttatch      = '附件';
$lang->testcase->legendLinkBugs     = '相關Bug';
$lang->testcase->legendOpenAndEdit  = '創建編輯';
$lang->testcase->legendComment      = '備註';

$lang->testcase->confirmDelete      = '您確認要刪除該測試用例嗎？';
$lang->testcase->confirmBatchDelete = '您確認要批量刪除這些測試用例嗎？';
$lang->testcase->ditto              = '同上';
$lang->testcase->dittoNotice        = '該用例與上一用例不屬於同一產品！';

$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['feature']     = '功能測試';
$lang->testcase->typeList['performance'] = '性能測試';
$lang->testcase->typeList['config']      = '配置相關';
$lang->testcase->typeList['install']     = '安裝部署';
$lang->testcase->typeList['security']    = '安全相關';
$lang->testcase->typeList['interface']   = '介面測試';
$lang->testcase->typeList['other']       = '其他';

$lang->testcase->stageList['']           = '';
$lang->testcase->stageList['unittest']   = '單元測試階段';
$lang->testcase->stageList['feature']    = '功能測試階段';
$lang->testcase->stageList['intergrate'] = '整合測試階段';
$lang->testcase->stageList['system']     = '系統測試階段';
$lang->testcase->stageList['smoke']      = '冒煙測試階段';
$lang->testcase->stageList['bvt']        = '版本驗證階段';

$lang->testcase->reviewResultList['']        = '';
$lang->testcase->reviewResultList['pass']    = '確認通過';
$lang->testcase->reviewResultList['clarify'] = '繼續完善';

$lang->testcase->groups['']      = '分組查看';
$lang->testcase->groups['story'] = '需求分組';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['wait']        = '待評審';
$lang->testcase->statusList['normal']      = '正常';
$lang->testcase->statusList['blocked']     = '被阻塞';
$lang->testcase->statusList['investigate'] = '研究中';

$lang->testcase->resultList['n/a']     = '忽略';
$lang->testcase->resultList['pass']    = '通過';
$lang->testcase->resultList['fail']    = '失敗';
$lang->testcase->resultList['blocked'] = '阻塞';

$lang->testcase->buttonToList = '返回';

$lang->testcase->errorEncode      = '無數據，請選擇正確的編碼重新上傳！';
$lang->testcase->noFunction       = '不存在iconv和mb_convert_encoding轉碼方法，不能將數據轉成想要的編碼！';
$lang->testcase->noRequire        = "%s行的“%s”是必填欄位，不能為空";
$lang->testcase->noLibrary        = "現在還沒有公共庫，請先創建！";
$lang->testcase->mustChooseResult = '必須選擇評審結果';

$lang->testcase->searchStories = '鍵入來搜索需求';
$lang->testcase->selectLib     = '請選擇庫';

$lang->testcase->action = new stdclass();
$lang->testcase->action->fromlib  = array('main' => '$date, 由 <strong>$actor</strong> 從用例庫 <strong>$extra</strong>導入。');
$lang->testcase->action->reviewed = array('main' => '$date, 由 <strong>$actor</strong> 記錄評審結果，結果為 <strong>$extra</strong>。', 'extra' => 'reviewResultList');

$lang->testcase->featureBar['browse']['all']         = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['wait']        = '待評審';
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;
$lang->testcase->featureBar['browse']['group']       = '分組查看';
$lang->testcase->featureBar['browse']['suite']       = '套件';
$lang->testcase->featureBar['browse']['zerocase']    = '零用例需求';
$lang->testcase->featureBar['groupcase']             = $lang->testcase->featureBar['browse'];

