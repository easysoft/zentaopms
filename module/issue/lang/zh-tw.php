<?php
/**
 * The issue module lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     issue
 * @version     $Id
 * @link        http://www.zentao.net
 */
$lang->issue->common            = '問題';
$lang->issue->browse            = '問題列表';
$lang->issue->resolvedBy        = '解決者';
$lang->issue->project           = '所屬項目';
$lang->issue->title             = '標題';
$lang->issue->desc              = '描述';
$lang->issue->pri               = '優先順序';
$lang->issue->severity          = '嚴重程度';
$lang->issue->type              = '類別';
$lang->issue->effectedArea      = '受影響的活動';
$lang->issue->activity          = '活動列表';
$lang->issue->deadline          = '計劃解決日期';
$lang->issue->resolution        = '解決方式';
$lang->issue->resolutionComment = '解決方案';
$lang->issue->resolvedDate      = '實際解決日期';
$lang->issue->status            = '結果';
$lang->issue->createdBy         = '由誰創建';
$lang->issue->createdDate       = '創建日期';
$lang->issue->owner             = '提出人';
$lang->issue->editedBy          = '由誰編輯';
$lang->issue->editedDate        = '編輯日期';
$lang->issue->activateBy        = '由誰激活';
$lang->issue->activateDate      = '激活日期';
$lang->issue->closedBy          = '由誰關閉';
$lang->issue->closedDate        = '關閉日期';
$lang->issue->assignedTo        = '指派給';
$lang->issue->assignedBy        = '由誰指派';
$lang->issue->assignedDate      = '指派時間';
$lang->issue->resolve           = '解決';
$lang->issue->id                = '編號';

$lang->issue->view              = '問題詳情';
$lang->issue->close             = '關閉';
$lang->issue->cancel            = '取消';
$lang->issue->delete            = '刪除';
$lang->issue->search            = '搜索';
$lang->issue->basicInfo         = '基本信息';
$lang->issue->activate          = '激活';
$lang->issue->assignTo          = '指派';
$lang->issue->create            = '新建問題';
$lang->issue->edit              = '編輯';
$lang->issue->batchCreate       = '批量新建';

$lang->issue->labelList['all']       = '全部';
$lang->issue->labelList['open']      = '開放';
$lang->issue->labelList['assignto']  = '指派給我';
$lang->issue->labelList['closed']    = '已關閉';
$lang->issue->labelList['suspended'] = '已掛起';
$lang->issue->labelList['canceled']  = '已取消';

$lang->issue->priList[''] = '';
$lang->issue->priList['1'] = 1;
$lang->issue->priList['2'] = 2;
$lang->issue->priList['3'] = 3;
$lang->issue->priList['4'] = 4;

$lang->issue->severityList[''] = '';
$lang->issue->severityList['1'] = '嚴重';
$lang->issue->severityList['2'] = '較嚴重';
$lang->issue->severityList['3'] = '較小';
$lang->issue->severityList['4'] = '建議';

$lang->issue->typeList[''] = '';
$lang->issue->typeList['design']       = '設計問題';
$lang->issue->typeList['code']         = '程序缺陷';
$lang->issue->typeList['performance']  = '性能問題';
$lang->issue->typeList['version']      = '版本控制';
$lang->issue->typeList['storyadd']     = '需求新增';
$lang->issue->typeList['storychanged'] = '需求修改';
$lang->issue->typeList['storyremoved'] = '需求刪除';
$lang->issue->typeList['data']         = '數據問題';

$lang->issue->resolutionList['resolved'] = '已解決';
$lang->issue->resolutionList['tostory']  = '轉需求';
$lang->issue->resolutionList['tobug']    = '轉BUG';
$lang->issue->resolutionList['torisk']   = '轉風險';
$lang->issue->resolutionList['totask']   = '轉任務';

$lang->issue->statusList['unconfirmed'] = '待確認';
$lang->issue->statusList['confirmed']   = '已確認';
$lang->issue->statusList['unresolved']  = '正解決';
$lang->issue->statusList['resolved']    = '已解決';
$lang->issue->statusList['canceled']    = '取消';
$lang->issue->statusList['closed']      = '已關閉';
$lang->issue->statusList['active']      = '激活';
$lang->issue->statusList['suspended']   = '掛起';

$lang->issue->resolveMethods = array();
$lang->issue->resolveMethods['resolved'] = '已解決';
$lang->issue->resolveMethods['totask']   = '轉任務';
$lang->issue->resolveMethods['tobug']    = '轉BUG';
$lang->issue->resolveMethods['tostory']  = '轉需求';
$lang->issue->resolveMethods['torisk']   = '轉風險';

$lang->issue->confirmDelete = '您確認刪除該問題？';
$lang->issue->typeEmpty     = 'ID：%s的類別不能為空。';
$lang->issue->titleEmpty    = 'ID：%s的標題不能為空。';
$lang->issue->severityEmpty = 'ID：%s的嚴重程度不能為空。';

$lang->issue->logComments = array();
$lang->issue->logComments['totask']  = "創建了任務：%s。";
$lang->issue->logComments['tostory'] = "創建了需求：%s。";
$lang->issue->logComments['tobug']   = "創建了BUG：%s。";
$lang->issue->logComments['torisk']  = "創建了風險：%s。";
