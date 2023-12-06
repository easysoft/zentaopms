<?php
/**
 * The productplan module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: zh-tw.php 4659 2013-04-17 06:45:08Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->productplan->common     = $lang->productCommon . '計劃';
$lang->productplan->browse     = "瀏覽計劃";
$lang->productplan->index      = "計劃列表";
$lang->productplan->create     = "創建計劃";
$lang->productplan->edit       = "編輯計劃";
$lang->productplan->delete     = "刪除計劃";
$lang->productplan->view       = "計劃詳情";
$lang->productplan->bugSummary = "本頁共 <strong>%s</strong> 個Bug";
$lang->productplan->basicInfo  = '基本信息';
$lang->productplan->batchEdit  = '批量編輯';
$lang->productplan->project    = '項目';
$lang->productplan->plan       = '計劃';

$lang->productplan->batchUnlink      = "批量移除";
$lang->productplan->unlinkAB         = "移除";
$lang->productplan->linkStory        = "關聯{$lang->SRCommon}";
$lang->productplan->unlinkStory      = "移除{$lang->SRCommon}";
$lang->productplan->unlinkStoryAB    = "移除";
$lang->productplan->batchUnlinkStory = "批量移除{$lang->SRCommon}";
$lang->productplan->linkedStories    = $lang->SRCommon;
$lang->productplan->unlinkedStories  = "未關聯{$lang->SRCommon}";
$lang->productplan->updateOrder      = '排序';
$lang->productplan->createChildren   = "創建子計劃";
$lang->productplan->createExecution  = "創建{$lang->execution->common}";

$lang->productplan->linkBug          = "關聯Bug";
$lang->productplan->unlinkBug        = "移除Bug";
$lang->productplan->batchUnlinkBug   = "批量移除Bug";
$lang->productplan->linkedBugs       = 'Bug';
$lang->productplan->unlinkedBugs     = '未關聯Bug';
$lang->productplan->unexpired        = "未過期計劃";
$lang->productplan->all              = "所有計劃";

$lang->productplan->confirmDelete      = "您確認刪除該計劃嗎？";
$lang->productplan->confirmUnlinkStory = "您確認移除該{$lang->SRCommon}嗎？";
$lang->productplan->confirmUnlinkBug   = "您確認移除該Bug嗎？";
$lang->productplan->noPlan             = "暫時沒有計劃。";
$lang->productplan->cannotDeleteParent = "不能刪除父計劃";
$lang->productplan->selectProjects     = "請選擇所屬項目";
$lang->productplan->projectNotEmpty    = '所屬項目不能為空。';
$lang->productplan->nextStep           = "下一步";

$lang->productplan->id         = '編號';
$lang->productplan->product    = $lang->productCommon;
$lang->productplan->branch     = '平台/分支';
$lang->productplan->title      = '名稱';
$lang->productplan->desc       = '描述';
$lang->productplan->begin      = '開始日期';
$lang->productplan->end        = '結束日期';
$lang->productplan->last       = "上次計劃";
$lang->productplan->future     = '待定';
$lang->productplan->stories    = "{$lang->SRCommon}數";
$lang->productplan->bugs       = 'Bug數';
$lang->productplan->hour       = $lang->hourCommon;
$lang->productplan->execution  = $lang->execution->common;
$lang->productplan->parent     = "父計劃";
$lang->productplan->parentAB   = "父";
$lang->productplan->children   = "子計劃";
$lang->productplan->childrenAB = "子";
$lang->productplan->order      = "排序";
$lang->productplan->deleted    = "已刪除";
$lang->productplan->mailto     = "抄送給";

$lang->productplan->endList[7]   = '一星期';
$lang->productplan->endList[14]  = '兩星期';
$lang->productplan->endList[31]  = '一個月';
$lang->productplan->endList[62]  = '兩個月';
$lang->productplan->endList[93]  = '三個月';
$lang->productplan->endList[186] = '半年';
$lang->productplan->endList[365] = '一年';

$lang->productplan->errorNoTitle      = 'ID %s 標題不能為空';
$lang->productplan->errorNoBegin      = 'ID %s 開始時間不能為空';
$lang->productplan->errorNoEnd        = 'ID %s 結束時間不能為空';
$lang->productplan->beginGeEnd        = 'ID %s 開始時間不能大於結束時間';
$lang->productplan->beginLessThanParent = "父計劃的開始日期：%s，開始日期不能小於父計劃的開始日期";
$lang->productplan->endGreatThanParent  = "父計劃的完成日期：%s，完成日期不能大於父計劃的完成日期";
$lang->productplan->noLinkedProject   = "當前產品還未關聯項目，請進入產品的項目列表關聯或創建一個項目";
$lang->productplan->enterProjectList  = "進入產品的項目列表";

$lang->productplan->featureBar['browse']['all']       = '全部';
$lang->productplan->featureBar['browse']['unexpired'] = '未過期';
$lang->productplan->featureBar['browse']['overdue']   = '已過期';
