<?php
/**
 * The translate module zh-tw file of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     translate
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->translate->common       = '翻譯';
$lang->translate->index        = '首頁';
$lang->translate->addLang      = '新增語言';
$lang->translate->module       = '翻譯模組';
$lang->translate->review       = '審校';
$lang->translate->reviewAction = '審校翻譯';
$lang->translate->result       = '保存審校結果';
$lang->translate->batchPass    = '批量通過';
$lang->translate->export       = '導出新語言';
$lang->translate->setting      = '設置';
$lang->translate->chooseModule = '選擇翻譯模組';

$lang->translate->name        = '語言名稱';
$lang->translate->code        = '代號';
$lang->translate->key         = '鍵';
$lang->translate->reference   = '參考語言';
$lang->translate->status      = '狀態';
$lang->translate->refreshPage = '刷新';
$lang->translate->reason      = '拒絶原因';

$lang->translate->reviewTurnon = '審校流程';
$lang->translate->reviewTurnonList['1'] = '開啟';
$lang->translate->reviewTurnonList['0'] = '關閉';

$lang->translate->resultList['pass']   = '通過';
$lang->translate->resultList['reject'] = '拒絶';

$lang->translate->group              = '視圖';
$lang->translate->allTotal           = '總條目';
$lang->translate->translatedTotal    = '已翻譯條目數';
$lang->translate->changedTotal       = '已修改條目數';
$lang->translate->reviewedTotal      = '已審校條目數';
$lang->translate->translatedProgress = '翻譯進度';
$lang->translate->reviewedProgress   = '審校進度';

$lang->translate->builtIn  = '內置語言';
$lang->translate->finished = '翻譯完成';
$lang->translate->progress = '完成 %s';
$lang->translate->count    = '（%s 種）';

$lang->translate->finishedLang    = '已經完成的語言';
$lang->translate->translatingLang = '正在翻譯的語言';
$lang->translate->allItems        = '所有的語言條目數：%s條';

$lang->translate->statusList['waiting']    = '未翻譯';
$lang->translate->statusList['translated'] = '已翻譯';
$lang->translate->statusList['reviewed']   = '已審校';
$lang->translate->statusList['rejected']   = '已拒絶';
$lang->translate->statusList['changed']    = '已改變';

$lang->translate->notice = new stdclass();
$lang->translate->notice->failDirPriv  = "目錄沒有寫入權限，請修改權限。<br /><code>%s</code>";
$lang->translate->notice->failCopyFile = "複製檔案%s到%s失敗，請檢查權限！";
$lang->translate->notice->failUnique   = "已經有代號 %s 的記錄";
$lang->translate->notice->failMaxInput = "請修改php.ini的max_input_vars參數，修改為 %s，以保證表單提交。";
$lang->translate->notice->failRuleCode = "『語言代號』應當為字母、數字或下劃線的組合。";
