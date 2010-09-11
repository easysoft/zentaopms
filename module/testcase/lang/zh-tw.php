<?php
/**
 * The testcase module zh-tw file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青島易軟天創網絡科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: zh-tw.php 949 2010-07-17 05:39:30Z yuren_@126.com $
 * @link        http://www.zentao.net
 */
$lang->testcase->id             = '用例編號';
$lang->testcase->product        = '所屬產品';
$lang->testcase->module         = '所屬模組';
$lang->testcase->story          = '相關需求';
$lang->testcase->storyVersion   = '需求版本';
$lang->testcase->title          = '用例標題';
$lang->testcase->pri            = '優先順序';
$lang->testcase->type           = '用例類型';
$lang->testcase->status         = '用例狀態';
$lang->testcase->steps          = '用例步驟';
$lang->testcase->frequency      = '執行頻率';
$lang->testcase->order          = '排序';
$lang->testcase->openedBy       = '由誰創建 ';
$lang->testcase->openedDate     = '創建日期';
$lang->testcase->lastEditedBy   = '最後修改者';
$lang->testcase->lastEditedDate = '最後修改日期';
$lang->testcase->version        = '用例版本';
$lang->testcase->result         = '測試結果';
$lang->testcase->real           = '實際情況';
$lang->testcase->keywords       = '關鍵詞';
$lang->testcase->files          = '附件';
$lang->testcase->howRun         = '執行方式';
$lang->testcase->scriptedBy     = '由誰編寫';
$lang->testcase->scriptedDate   = '編寫日期';
$lang->testcase->scriptedStatus = '腳本狀態';
$lang->testcase->scriptedLocation = '腳本位置';
$lang->testcase->linkCase         = '相關用例';
$lang->testcase->stage            = '適用階段';
$lang->testcase->lastEditedByAB   = '修改者';
$lang->testcase->lastEditedDateAB = '修改日期';
$lang->case = $lang->testcase;  // 用於DAO檢查時使用。因為case是系統關鍵字，所以無法定義該模組為case，只能使用testcase，但表還是使用的case。

$lang->testcase->stepID     = '編號';
$lang->testcase->stepDesc   = '步驟';
$lang->testcase->stepExpect = '預期';

$lang->testcase->common         = '用例管理';
$lang->testcase->index          = "用例管理首頁";
$lang->testcase->create         = "創建用例";
$lang->testcase->delete         = "刪除用例";
$lang->testcase->view           = "用例詳情";
$lang->testcase->edit           = "編輯用例";
$lang->testcase->delete         = "刪除用例";
$lang->testcase->browse         = "用例列表";
$lang->testcase->confirmStoryChange = '確認需求變動';

$lang->testcase->deleteStep     = 'ｘ';
$lang->testcase->insertBefore   = '＋↑';
$lang->testcase->insertAfter    = '＋↓';

$lang->testcase->selectProduct  = '請選擇產品';
$lang->testcase->byModule       = '按模組';
$lang->testcase->assignToMe     = '指派給我';
$lang->testcase->openedByMe     = '由我創建';
$lang->testcase->allCases       = '所有Case';
$lang->testcase->needConfirm    = '需求有變動的用例';
$lang->testcase->moduleCases    = '按模組瀏覽';
$lang->testcase->bySearch       = '搜索';

$lang->testcase->lblProductAndModule         = '產品模組';
$lang->testcase->lblTypeAndPri               = '類型&優先順序';
$lang->testcase->lblSystemBrowserAndHardware = '系統::瀏覽器';
$lang->testcase->lblAssignAndMail            = '指派給::抄送給';
$lang->testcase->lblStory                    = '相關需求';
$lang->testcase->lblLastEdited               = '最後編輯';

$lang->testcase->legendRelated     = '相關信息';
$lang->testcase->legendBasicInfo   = '基本信息';
$lang->testcase->legendMailto      = '抄送給';
$lang->testcase->legendAttatch     = '附件';
$lang->testcase->legendLinkBugs    = '相關Bug';
$lang->testcase->legendOpenAndEdit = '創建編輯';
$lang->testcase->legendStoryAndTask= '需求::任務';
$lang->testcase->legendCases       = '相關用例';
$lang->testcase->legendSteps       = '用例步驟';
$lang->testcase->legendAction      = '操作';
$lang->testcase->legendHistory     = '歷史記錄';
$lang->testcase->legendComment     = '備註';
$lang->testcase->legendProduct     = '產品模組';
$lang->testcase->legendVersion     = '版本歷史';

$lang->testcase->confirmDelete     = '您確認要刪除該測試用例嗎？';

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
$lang->testcase->typeList['other']       = '其他';

$lang->testcase->stageList['']            = '';
$lang->testcase->stageList['unittest']    = '單元測試階段';
$lang->testcase->stageList['feature']     = '功能測試階段';
$lang->testcase->stageList['intergrate']  = '整合測試階段';
$lang->testcase->stageList['system']      = '系統測試階段';
$lang->testcase->stageList['smoke']       = '冒煙測試階段';
$lang->testcase->stageList['bvt']         = '版本驗證階段';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['normal']      = '正常';
$lang->testcase->statusList['blocked']     = '被阻塞';
$lang->testcase->statusList['investigate'] = '研究中';

$lang->testcase->resultList['n/a']     = 'N/A';
$lang->testcase->resultList['pass']    = '通過';
$lang->testcase->resultList['fail']    = '失敗';
$lang->testcase->resultList['blocked'] = '阻塞';

$lang->testcase->buttonEdit     = '編輯';
$lang->testcase->buttonToList   = '返回';
