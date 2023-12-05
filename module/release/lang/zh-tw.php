<?php
/**
 * The release module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: zh-tw.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->release->common           = '產品發佈';
$lang->release->create           = "創建發佈";
$lang->release->edit             = "編輯發佈";
$lang->release->linkStory        = "關聯{$lang->SRCommon}";
$lang->release->linkBug          = "關聯Bug";
$lang->release->delete           = "刪除發佈";
$lang->release->deleted          = '已刪除';
$lang->release->view             = "發佈詳情";
$lang->release->browse           = "瀏覽發佈";
$lang->release->changeStatus     = "修改狀態";
$lang->release->batchUnlink      = "批量移除";
$lang->release->batchUnlinkStory = "批量移除{$lang->SRCommon}";
$lang->release->batchUnlinkBug   = "批量移除Bug";

$lang->release->confirmDelete      = "您確認刪除該發佈嗎？";
$lang->release->confirmLink        = "是否將版本中完成的{$lang->SRCommon}和已解決的bug關聯到發佈下？";
$lang->release->confirmUnlinkStory = "您確認移除該{$lang->SRCommon}嗎？";
$lang->release->confirmUnlinkBug   = "您確認移除該Bug嗎？";
$lang->release->existBuild         = '『版本』已經有『%s』這條記錄了。您可以更改『發佈名稱』或者選擇一個『版本』。';
$lang->release->noRelease          = '暫時沒有發佈。';
$lang->release->errorDate          = '發佈日期不能大於今天。';

$lang->release->basicInfo = '基本信息';

$lang->release->id            = 'ID';
$lang->release->product       = $lang->productCommon;
$lang->release->branch        = '平台/分支';
$lang->release->project       = '所屬項目';
$lang->release->build         = '版本';
$lang->release->name          = '發佈名稱';
$lang->release->marker        = '里程碑';
$lang->release->date          = '發佈日期';
$lang->release->desc          = '描述';
$lang->release->files         = '附件';
$lang->release->status        = '狀態';
$lang->release->subStatus     = '子狀態';
$lang->release->last          = '上次發佈';
$lang->release->unlinkStory   = "移除{$lang->SRCommon}";
$lang->release->unlinkBug     = '移除Bug';
$lang->release->stories       = "完成的{$lang->SRCommon}";
$lang->release->bugs          = '解決的Bug';
$lang->release->leftBugs      = '遺留的Bug';
$lang->release->generatedBugs = '遺留的Bug';
$lang->release->finishStories = "本次共完成 %s 個{$lang->SRCommon}";
$lang->release->resolvedBugs  = '本次共解決 %s 個Bug';
$lang->release->createdBugs   = '本次共遺留 %s 個Bug';
$lang->release->export        = '導出HTML';
$lang->release->yesterday     = '昨日發佈';
$lang->release->all           = '所有';
$lang->release->notify        = '發送通知';
$lang->release->notifyUsers   = '通知人員';
$lang->release->mailto        = '抄送給';
$lang->release->mailContent   = '<p>尊敬的用戶，您好！</p><p style="margin-left: 30px;">您反饋的如下需求和Bug已經在 %s版本中發佈，請聯繫客戶經理查看最新版本。</p>';
$lang->release->storyList     = '<p style="margin-left: 30px;">需求列表：%s。</p>';
$lang->release->bugList       = '<p style="margin-left: 30px;">Bug列表：%s。</p>';

$lang->release->filePath = '下載地址：';
$lang->release->scmPath  = '版本庫地址：';

$lang->release->exportTypeList['all']     = '所有';
$lang->release->exportTypeList['story']   = $lang->SRCommon;
$lang->release->exportTypeList['bug']     = 'Bug';
$lang->release->exportTypeList['leftbug'] = '遺留Bug';

$lang->release->statusList['']          = '';
$lang->release->statusList['normal']    = '正常';
$lang->release->statusList['terminate'] = '停止維護';

$lang->release->changeStatusList['normal']    = '激活';
$lang->release->changeStatusList['terminate'] = '停止維護';

$lang->release->action = new stdclass();
$lang->release->action->changestatus = array('main' => '$date, 由 <strong>$actor</strong> $extra。', 'extra' => 'changeStatusList');
$lang->release->action->notified     = array('main' => '$date, 由 <strong>$actor</strong> 發送通知。');

$lang->release->notifyList['FB'] = "反饋者";
$lang->release->notifyList['PO'] = "{$lang->productCommon}負責人";
$lang->release->notifyList['QD'] = '測試負責人';
$lang->release->notifyList['SC'] = '需求提交人';
$lang->release->notifyList['ET'] = "所在{$lang->execution->common}團隊成員";
$lang->release->notifyList['PT'] = "所在項目團隊成員";
