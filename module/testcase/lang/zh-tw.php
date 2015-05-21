<?php
/**
 * The testcase module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: zh-tw.php 4764 2013-05-05 04:07:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->testcase->id               = '用例編號';
$lang->testcase->product          = "所屬{$lang->productcommon}";
$lang->testcase->module           = '所屬模組';
$lang->testcase->story            = '相關需求';
$lang->testcase->storyVersion     = '需求版本';
$lang->testcase->title            = '用例標題';
$lang->testcase->precondition     = '前置條件';
$lang->testcase->pri              = '優先順序';
$lang->testcase->type             = '用例類型';
$lang->testcase->status           = '用例狀態';
$lang->testcase->steps            = '用例步驟';
$lang->testcase->frequency        = '執行頻率';
$lang->testcase->order            = '排序';
$lang->testcase->openedBy         = '由誰創建 ';
$lang->testcase->openedDate       = '創建日期';
$lang->testcase->lastEditedBy     = '最後修改者';
$lang->testcase->lastEditedDate   = '最後修改日期';
$lang->testcase->version          = '用例版本';
$lang->testcase->result           = '測試結果';
$lang->testcase->lastRunResult    = '結果';
$lang->testcase->real             = '實際情況';
$lang->testcase->keywords         = '關鍵詞';
$lang->testcase->files            = '附件';
$lang->testcase->howRun           = '執行方式';
$lang->testcase->scriptedBy       = '由誰編寫';
$lang->testcase->scriptedDate     = '編寫日期';
$lang->testcase->scriptedStatus   = '腳本狀態';
$lang->testcase->scriptedLocation = '腳本位置';
$lang->testcase->linkCase         = '相關用例';
$lang->testcase->stage            = '適用階段';
$lang->testcase->lastEditedByAB   = '修改者';
$lang->testcase->lastEditedDateAB = '修改日期';
$lang->testcase->allProduct       = "所有{$lang->productcommon}";
$lang->testcase->fromBug          = '來源Bug';
$lang->testcase->toBug            = '生成Bug';
$lang->testcase->changed          = '用例變更';
$lang->testcase->createBug        = '轉Bug';
$lang->case = $lang->testcase;  // 用於DAO檢查時使用。因為case是系統關鍵字，所以無法定義該模組為case，只能使用testcase，但表還是使用的case。

$lang->testcase->stepID     = '編號';
$lang->testcase->stepDesc   = '步驟';
$lang->testcase->stepExpect = '預期';

$lang->testcase->common             = '用例';
$lang->testcase->index              = "用例管理首頁";
$lang->testcase->create             = "建用例";
$lang->testcase->batchCreate        = "批量添加";
$lang->testcase->delete             = "刪除用例";
$lang->testcase->view               = "用例詳情";
$lang->testcase->edit               = "編輯";
$lang->testcase->batchEdit          = "批量編輯 ";
$lang->testcase->delete             = "刪除";
$lang->testcase->batchDelete        = "批量刪除 ";
$lang->testcase->deleted            = "已刪除";
$lang->testcase->browse             = "用例列表";
$lang->testcase->groupCase          = "分組瀏覽用例";
$lang->testcase->import             = "導入";
$lang->testcase->importID           = "行號";
$lang->testcase->showImport         = "顯示導入內容";
$lang->testcase->exportTemplet      = "導出模板";
$lang->testcase->export             = "導出數據";
$lang->testcase->confirmChange      = '確認用例變動';
$lang->testcase->confirmStoryChange = '確認需求變動';

$lang->testcase->new = '新增';

$lang->testcase->num = '用例記錄數：';

$lang->testcase->deleteStep   = '刪除';
$lang->testcase->insertBefore = '之前添加';
$lang->testcase->insertAfter  = '之後添加';

$lang->testcase->selectProduct = "請選擇{$lang->productcommon}";
$lang->testcase->byModule      = '按模組';
$lang->testcase->assignToMe    = '給我的用例';
$lang->testcase->openedByMe    = '我建的用例';
$lang->testcase->allCases      = '所有';
$lang->testcase->needConfirm   = '需求變動';
$lang->testcase->moduleCases   = '按模組';
$lang->testcase->bySearch      = '搜索';
$lang->testcase->doneByMe      = '我完成的用例';

$lang->testcase->lblProductAndModule         = "{$lang->productcommon}模組";
$lang->testcase->lblTypeAndPri               = '類型&優先順序';
$lang->testcase->lblSystemBrowserAndHardware = '系統::瀏覽器';
$lang->testcase->lblAssignAndMail            = '指派給::抄送給';
$lang->testcase->lblStory                    = '相關需求';
$lang->testcase->lblLastEdited               = '最後編輯';

$lang->testcase->legendRelated      = '相關信息';
$lang->testcase->legendBasicInfo    = '基本信息';
$lang->testcase->legendMailto       = '抄送給';
$lang->testcase->legendAttatch      = '附件';
$lang->testcase->legendLinkBugs     = '相關Bug';
$lang->testcase->legendOpenAndEdit  = '創建編輯';
$lang->testcase->legendStoryAndTask = '需求::任務';
$lang->testcase->legendCases        = '相關用例';
$lang->testcase->legendSteps        = '用例步驟';
$lang->testcase->legendAction       = '操作';
$lang->testcase->legendHistory      = '歷史記錄';
$lang->testcase->legendComment      = '備註';
$lang->testcase->legendProduct      = $lang->productcommon . '模組';

$lang->testcase->confirmDelete      = '您確認要刪除該測試用例嗎？';
$lang->testcase->confirmBatchDelete = '您確認要批量刪除這些測試用例嗎？';
$lang->testcase->same               = '同上';

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

$lang->testcase->groups['']      = '分組查看';
$lang->testcase->groups['story'] = '需求分組';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['normal']      = '正常';
$lang->testcase->statusList['blocked']     = '被阻塞';
$lang->testcase->statusList['investigate'] = '研究中';

$lang->testcase->resultList['n/a']     = 'N/A';
$lang->testcase->resultList['pass']    = '通過';
$lang->testcase->resultList['fail']    = '失敗';
$lang->testcase->resultList['blocked'] = '阻塞';

$lang->testcase->buttonEdit   = '編輯';
$lang->testcase->buttonToList = '返回';

$lang->testcase->errorEncode = '無數據，請選擇正確的編碼重新上傳！';
$lang->testcase->noFunction  = '不存在iconv和mb_convert_encoding轉碼方法，不能將數據轉成想要的編碼！';
$lang->testcase->noRequire   = "%s行的“%s”是必填欄位，不能為空";

$lang->testcase->searchStories = '鍵入來搜索需求';
