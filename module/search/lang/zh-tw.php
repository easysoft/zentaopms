<?php
/**
 * The search module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id: zh-tw.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->search->common        = '搜索';
$lang->search->id            = '編號';
$lang->search->editedDate    = '編輯時間';
$lang->search->key           = '鍵';
$lang->search->value         = '值';
$lang->search->reset         = '重置';
$lang->search->saveQuery     = '保存查詢';
$lang->search->myQuery       = '我的查詢';
$lang->search->group1        = '第一組';
$lang->search->group2        = '第二組';
$lang->search->buildForm     = '搜索表單';
$lang->search->buildQuery    = '執行搜索';
$lang->search->savedQuery    = '已保存的查詢條件';
$lang->search->deleteQuery   = '刪除查詢';
$lang->search->setQueryTitle = '請輸入查詢標題（保存之前請先查詢）：';
$lang->search->select        = "{$lang->SRCommon}/任務篩選";
$lang->search->me            = '自己';
$lang->search->noQuery       = '還沒有保存查詢！';
$lang->search->onMenuBar     = '顯示在菜單欄';
$lang->search->custom        = '自定義';
$lang->search->setCommon     = '設為公共查詢條件';
$lang->search->saveCondition = '保存搜索條件';
$lang->search->setCondName   = '請輸入保存條件名稱';

$lang->search->account  = '用戶名';
$lang->search->module   = '模組';
$lang->search->title    = '名稱';
$lang->search->form     = '表單欄位';
$lang->search->sql      = 'SQL條件';
$lang->search->shortcut = $lang->search->onMenuBar;

$lang->search->operators['=']          = '=';
$lang->search->operators['!=']         = '!=';
$lang->search->operators['>']          = '>';
$lang->search->operators['>=']         = '>=';
$lang->search->operators['<']          = '<';
$lang->search->operators['<=']         = '<=';
$lang->search->operators['include']    = '包含';
$lang->search->operators['between']    = '介於';
$lang->search->operators['notinclude'] = '不包含';
$lang->search->operators['belong']     = '從屬於';

$lang->search->andor['and']         = '並且';
$lang->search->andor['or']          = '或者';

$lang->search->null = '空';

$lang->userquery        = new stdclass();
$lang->userquery->title = '標題';

$lang->searchObjects['todo']      = '待辦';
$lang->searchObjects['effort']    = '日誌';
$lang->searchObjects['testsuite'] = '套件';

$lang->search->objectType = '對象類型';
$lang->search->objectID   = '對象編號';
$lang->search->content    = '內容';
$lang->search->addedDate  = '添加時間';

$lang->search->index      = '全文檢索';
$lang->search->buildIndex = '重建索引';
$lang->search->preview    = '預覽';

$lang->search->inputWords        = '请输入检索内容';
$lang->search->result            = '搜索結果';
$lang->search->resultCount       = '共计 <strong>%s</strong> 条';
$lang->search->buildSuccessfully = '初始化搜索索引成功';
$lang->search->executeInfo       = '為您找到相關結果%s個，耗時%s秒';
$lang->search->buildResult       = "創建 %s 索引, 已創建  <strong class='%scount'>%s</strong> 條記錄；";
$lang->search->queryTips         = "多個id可用英文逗號分隔";

$lang->search->modules['all']         = '全部';
$lang->search->modules['task']        = '任務';
$lang->search->modules['bug']         = 'Bug';
$lang->search->modules['case']        = '用例';
$lang->search->modules['doc']         = '文檔';
$lang->search->modules['todo']        = '待辦';
$lang->search->modules['build']       = '版本';
$lang->search->modules['effort']      = '日誌';
$lang->search->modules['caselib']     = '測試庫';
$lang->search->modules['product']     = $lang->productCommon;
$lang->search->modules['release']     = '發佈';
$lang->search->modules['testtask']    = '測試單';
$lang->search->modules['testsuite']   = '測試套件';
$lang->search->modules['testreport']  = '報告';
$lang->search->modules['productplan'] = '計劃';
$lang->search->modules['program']     = '項目集';
$lang->search->modules['project']     = '項目';
$lang->search->modules['execution']   = $lang->executionCommon;
$lang->search->modules['story']       = $lang->SRCommon;
$lang->search->modules['requirement'] = $lang->URCommon;

$lang->search->objectTypeList['story']            = $lang->SRCommon;
$lang->search->objectTypeList['requirement']      = $lang->URCommon;
$lang->search->objectTypeList['stage']            = '階段';
$lang->search->objectTypeList['sprint']           = $lang->executionCommon;
$lang->search->objectTypeList['kanban']           = '看板';
$lang->search->objectTypeList['commonIssue']      = '問題';
$lang->search->objectTypeList['stakeholderIssue'] = '干係人問題';
