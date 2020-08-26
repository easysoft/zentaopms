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
$lang->product->common       = $lang->productCommon . '視圖';
$lang->product->index        = $lang->productCommon . '主頁';
$lang->product->browse       = "{$lang->storyCommon}列表";
$lang->product->dynamic      = '產品動態';
$lang->product->view         = "{$lang->productCommon}概況";
$lang->product->edit         = "編輯{$lang->productCommon}";
$lang->product->batchEdit    = '批量編輯';
$lang->product->create       = "添加{$lang->productCommon}";
$lang->product->delete       = "刪除{$lang->productCommon}";
$lang->product->deleted      = '已刪除';
$lang->product->close        = '關閉';
$lang->product->closeAction  = "關閉{$lang->productCommon}";
$lang->product->select       = "請選擇{$lang->productCommon}";
$lang->product->mine         = '我負責：';
$lang->product->other        = '其他：';
$lang->product->closed       = '已關閉';
$lang->product->updateOrder  = '排序';
$lang->product->orderAction  = "{$lang->productCommon}排序";
$lang->product->all          = "所有{$lang->productCommon}";
$lang->product->export       = '導出數據';
$lang->product->exportAction = "導出{$lang->productCommon}";

$lang->product->basicInfo = '基本信息';
$lang->product->otherInfo = '其他信息';

$lang->product->plans       = '計劃數';
$lang->product->releases    = '發佈數';
$lang->product->docs        = '文檔數';
$lang->product->bugs        = '相關Bug';
$lang->product->projects    = "關聯{$lang->projectCommon}數";
$lang->product->cases       = '用例數';
$lang->product->builds      = 'BUILD數';
$lang->product->roadmap     = "{$lang->productCommon}路線圖";
$lang->product->doc         = '文檔列表';
$lang->product->project     = $lang->projectCommon . '列表';
$lang->product->build       = '版本列表';
$lang->product->projectInfo = "所有與此產品關聯的{$lang->projectCommon}";

$lang->product->currentProject        = '當前' . $lang->projectCommon;
$lang->product->activeStories         = "激活{$lang->storyCommon}";
$lang->product->activeStoriesTitle    = "激活{$lang->storyCommon}";
$lang->product->changedStories        = "已變更{$lang->storyCommon}";
$lang->product->changedStoriesTitle   = "已變更{$lang->storyCommon}";
$lang->product->draftStories          = "草稿{$lang->storyCommon}";
$lang->product->draftStoriesTitle     = "草稿{$lang->storyCommon}";
$lang->product->closedStories         = "已關閉{$lang->storyCommon}";
$lang->product->closedStoriesTitle    = "已關閉{$lang->storyCommon}";
$lang->product->unResolvedBugs        = '未解決Bug';
$lang->product->unResolvedBugsTitle   = '未解決Bug';
$lang->product->assignToNullBugs      = '未指派Bug';
$lang->product->assignToNullBugsTitle = '未指派Bug';

$lang->product->confirmDelete  = " 您確定刪除該{$lang->productCommon}嗎？";
$lang->product->errorNoProduct = "還沒有創建{$lang->productCommon}！";
$lang->product->accessDenied   = "您無權訪問該{$lang->productCommon}";

$lang->product->id             = '編號';
$lang->product->name           = "{$lang->productCommon}名稱";
$lang->product->code           = "{$lang->productCommon}代號";
$lang->product->line           = "{$lang->productCommon}綫";
$lang->product->order          = '排序';
$lang->product->type           = "{$lang->productCommon}類型";
$lang->product->typeAB         = "類型";
$lang->product->status         = '狀態';
$lang->product->subStatus      = '子狀態';
$lang->product->desc           = "{$lang->productCommon}描述";
$lang->product->manager        = '負責人';
$lang->product->PO             = "{$lang->productCommon}負責人";
$lang->product->QD             = '測試負責人';
$lang->product->RD             = '發佈負責人';
$lang->product->acl            = '訪問控制';
$lang->product->whitelist      = '分組白名單';
$lang->product->branch         = '所屬%s';
$lang->product->qa             = '測試';
$lang->product->release        = '發佈';
$lang->product->allRelease     = '所有發佈';
$lang->product->maintain       = '維護中';
$lang->product->latestDynamic  = '最新動態';
$lang->product->plan           = '計劃';
$lang->product->iteration      = '版本迭代';
$lang->product->iterationInfo  = '迭代 %s 次';
$lang->product->iterationView  = '查看詳情';
$lang->product->createdBy      = '由誰創建';
$lang->product->createdDate    = '創建日期';

$lang->product->searchStory  = '搜索';
$lang->product->assignedToMe = '指給我';
$lang->product->openedByMe   = '我創建';
$lang->product->reviewedByMe = '我評審';
$lang->product->closedByMe   = '我關閉';
$lang->product->draftStory   = '草稿';
$lang->product->activeStory  = '激活';
$lang->product->changedStory = '已變更';
$lang->product->willClose    = '待關閉';
$lang->product->closedStory  = '已關閉';
$lang->product->unclosed     = '未關閉';
$lang->product->unplan       = '未計劃';
$lang->product->viewByUser   = '按用戶查看';

$lang->product->allStory             = '所有';
$lang->product->allProduct           = '全部' . $lang->productCommon;
$lang->product->allProductsOfProject = '全部關聯' . $lang->productCommon;

$lang->product->typeList['']         = '';
$lang->product->typeList['normal']   = '正常';
$lang->product->typeList['branch']   = '多分支';
$lang->product->typeList['platform'] = '多平台';

$lang->product->typeTips = array();
$lang->product->typeTips['branch']   = '(適用於客戶定製場景)';
$lang->product->typeTips['platform'] = '(適用於跨平台應用開發，比如IOS、安卓、PC端等)';

$lang->product->branchName['normal']   = '';
$lang->product->branchName['branch']   = '分支';
$lang->product->branchName['platform'] = '平台';

$lang->product->statusList['']       = '';
$lang->product->statusList['normal'] = '正常';
$lang->product->statusList['closed'] = '結束';

$lang->product->aclList['open']    = "預設設置(有{$lang->productCommon}視圖權限，即可訪問)";
$lang->product->aclList['private'] = "私有{$lang->productCommon}(只有{$lang->productCommon}相關負責人和{$lang->projectCommon}團隊成員才能訪問)";
$lang->product->aclList['custom']  = '自定義白名單(團隊成員和白名單的成員可以訪問)';

$lang->product->storySummary   = "本頁共 <strong>%s</strong> 個%s，預計 <strong>%s</strong> 個{$lang->hourCommon}，用例覆蓋率 <strong>%s</strong>。";
$lang->product->checkedSummary = "選中 <strong>%total%</strong> 個%storyCommon%，預計 <strong>%estimate%</strong> 個{$lang->hourCommon}，用例覆蓋率 <strong>%rate%</strong>。";
$lang->product->noModule       = "<div>您現在還沒有模組信息</div><div>請維護{$lang->productCommon}模組</div>";
$lang->product->noProduct      = "暫時沒有{$lang->productCommon}。";
$lang->product->noMatched      = '找不到包含"%s"的' . $lang->productCommon;

$lang->product->featureBar['browse']['allstory']     = $lang->product->allStory;
$lang->product->featureBar['browse']['unclosed']     = $lang->product->unclosed;
$lang->product->featureBar['browse']['assignedtome'] = $lang->product->assignedToMe;
$lang->product->featureBar['browse']['openedbyme']   = $lang->product->openedByMe;
$lang->product->featureBar['browse']['reviewedbyme'] = $lang->product->reviewedByMe;
$lang->product->featureBar['browse']['draftstory']   = $lang->product->draftStory;
$lang->product->featureBar['browse']['more']         = $lang->more;

$lang->product->featureBar['all']['noclosed'] = $lang->product->unclosed;
$lang->product->featureBar['all']['closed']   = $lang->product->statusList['closed'];
$lang->product->featureBar['all']['all']      = $lang->product->allProduct;

$lang->product->moreSelects['closedbyme']   = $lang->product->closedByMe;
$lang->product->moreSelects['activestory']  = $lang->product->activeStory;
$lang->product->moreSelects['changedstory'] = $lang->product->changedStory;
$lang->product->moreSelects['willclose']    = $lang->product->willClose;
$lang->product->moreSelects['closedstory']  = $lang->product->closedStory;
