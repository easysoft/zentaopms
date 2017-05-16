<?php
/**
 * The product module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: zh-tw.php 5091 2013-07-10 06:06:46Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->product->common      = $lang->productCommon . '視圖';
$lang->product->index       = $lang->productCommon . "首頁";
$lang->product->browse      = "瀏覽{$lang->productCommon}";
$lang->product->dynamic     = "動態";
$lang->product->view        = "{$lang->productCommon}概況";
$lang->product->edit        = "編輯{$lang->productCommon}";
$lang->product->batchEdit   = "批量編輯";
$lang->product->create      = "新增{$lang->productCommon}";
$lang->product->delete      = "刪除{$lang->productCommon}";
$lang->product->deleted     = "已刪除";
$lang->product->close       = "關閉";
$lang->product->select      = "請選擇{$lang->productCommon}";
$lang->product->mine        = '我負責：';
$lang->product->other       = '其他：';
$lang->product->closed      = '已關閉';
$lang->product->updateOrder = "排序";
$lang->product->all         = "所有{$lang->productCommon}";

$lang->product->basicInfo = '基本信息';
$lang->product->otherInfo = '其他信息';

$lang->product->plans    = '計劃數';
$lang->product->releases = '發佈數';
$lang->product->docs     = '文檔數';
$lang->product->bugs     = '相關BUG';
$lang->product->projects = "關聯{$lang->projectCommon}數";
$lang->product->cases    = '用例數';
$lang->product->builds   = 'BUILD數';
$lang->product->roadmap  = '路線圖';
$lang->product->doc      = '文檔列表';
$lang->product->project  = $lang->projectCommon . '列表';
$lang->product->build    = '版本列表';

$lang->product->confirmDelete   = " 您確定刪除該{$lang->productCommon}嗎？";

$lang->product->errorNoProduct = "還沒有創建{$lang->productCommon}！";
$lang->product->accessDenied   = "您無權訪問該{$lang->productCommon}";

$lang->product->name      = "{$lang->productCommon}名稱";
$lang->product->code      = "{$lang->productCommon}代號";
$lang->product->order     = '排序';
$lang->product->type      = "{$lang->productCommon}類型";
$lang->product->status    = '狀態';
$lang->product->desc      = "{$lang->productCommon}描述";
$lang->product->PO        = "{$lang->productCommon}負責人";
$lang->product->QD        = '測試負責人';
$lang->product->RD        = '發佈負責人';
$lang->product->acl       = '訪問控制';
$lang->product->whitelist = '分組白名單';
$lang->product->branch    = '所屬%s';

$lang->product->searchStory  = '搜索';
$lang->product->assignedToMe = '指派給我';
$lang->product->openedByMe   = '由我創建';
$lang->product->reviewedByMe = '由我評審';
$lang->product->closedByMe   = '由我關閉';
$lang->product->draftStory   = '草稿';
$lang->product->activeStory  = '激活';
$lang->product->changedStory = '已變更';
$lang->product->willClose    = '待關閉';
$lang->product->closedStory  = '已關閉';
$lang->product->unclosed     = '未關閉';

$lang->product->allStory    = '全部需求';
$lang->product->allProduct  = '全部' . $lang->productCommon;
$lang->product->allProductsOfProject = '全部關聯' . $lang->productCommon;

$lang->product->typeList['']         = '';
$lang->product->typeList['normal']   = '正常';
$lang->product->typeList['branch']   = '多分支';
$lang->product->typeList['platform'] = '多平台';

$lang->product->typeTips = array();
$lang->product->typeTips['branch']   = '(適用於客戶定製場景)';
$lang->product->typeTips['platform'] = '(適用於跨平台應用開發，比如ios、安卓、pc端等)';

$lang->product->branchName['normal']   = '';
$lang->product->branchName['branch']   = '分支';
$lang->product->branchName['platform'] = '平台';

$lang->product->statusList['']       = '';
$lang->product->statusList['normal'] = '正常';
$lang->product->statusList['closed'] = '結束';

$lang->product->aclList['open']    = "預設設置(有{$lang->productCommon}視圖權限，即可訪問)";
$lang->product->aclList['private'] = "私有{$lang->productCommon}(只有{$lang->productCommon}相關負責人和{$lang->projectCommon}團隊成員才能訪問)";
$lang->product->aclList['custom']  = '自定義白名單(團隊成員和白名單的成員可以訪問)';

$lang->product->storySummary = "本頁共 <strong>%s</strong> 個需求，預計 <strong>%s</strong> 個工時，用例覆蓋率<strong>%s</strong>。";
$lang->product->noMatched    = '找不到包含"%s"的' . $lang->productCommon;

$lang->product->featureBar['browse']['unclosed']     = $lang->product->unclosed;
$lang->product->featureBar['browse']['allstory']     = $lang->product->allStory;
$lang->product->featureBar['browse']['assignedtome'] = $lang->product->assignedToMe;
$lang->product->featureBar['browse']['openedbyme']   = $lang->product->openedByMe;
$lang->product->featureBar['browse']['reviewedbyme'] = $lang->product->reviewedByMe;
$lang->product->featureBar['browse']['closedbyme']   = $lang->product->closedByMe;
$lang->product->featureBar['browse']['draftstory']   = $lang->product->draftStory;
$lang->product->featureBar['browse']['activestory']  = $lang->product->activeStory;
$lang->product->featureBar['browse']['changedstory'] = $lang->product->changedStory;
$lang->product->featureBar['browse']['willclose']    = $lang->product->willClose;
$lang->product->featureBar['browse']['closedstory']  = $lang->product->closedStory;
