<?php
/**
 * The ajaxGetDropmenuForOld view file of message module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        http://www.zentao.net
 */

$noDataHtml       = "<div class='text-gray text-center nodata'>{$lang->noData}</div>";
$browserSetting   = $config->message->browser;
$buildMessageList = function($messageGroup) use ($lang, $noDataHtml)
{
    if(empty($messageGroup)) return $noDataHtml;

    $dateList = array();
    foreach($messageGroup as $date => $messages)
    {
        $itemList = array();
        foreach($messages as $message)
        {
            $isUnread = $message->status != 'read';
            $dotColor = $isUnread ? 'danger' : 'gray';

            $li  = "<li class='message-item border rounded-lg p-2 mt-2" . ($isUnread ? ' unread' : '') . "' data-msgid='{$message->id}' style='word-break: break-all;' onclick='markRead(this)'>\n";
            $li .= "<div class='text-gray relative'>";
            $li .= "<div><span class='label label-dot mr-2 {$dotColor}'></span><span>{$lang->message->browser}</span></div>\n";
            $li .= "<div class='absolute' style='top:0px; right:0px;'><span>{$message->showTime}</span><i class='icon icon-close ml-2 cursor-pointer delete-message-btn' onclick='deleteMessage(this)'></i></div>\n";
            $li .= "</div>\n";
            $li .= "<div class='pt-1'>{$message->data}</div>\n";
            $li .= "</li>\n";
            $itemList[] = $li;
        }
        $dateList[] = "<li class='message-date mt-2'>{$date}\n <ul class='list-unstyled'>" . implode("\n", $itemList) . "</ul>";
    }
    return "<ul class='list-unstyled'>" . implode("\n", $dateList) . "</ul>";
};
?>

<style>
#messageTabs.text-black {color: rgb(49, 60, 82);}
#messageTabs.pt-10px {padding-top:10px;}
#messageTabs.px-5 {padding-left: 20px; padding-right: 20px;}
#messageTabs.pb-5 {padding-bottom: 20px;}
#messageTabs .border {border-width: 1px; border-color: rgb(235, 237, 243); border-style: solid;}
#messageTabs .rounded-lg {border-radius: 6px;}
#messageTabs .p-2 {padding:8px;}
#messageTabs .pt-2 {padding-top:8px;}
#messageTabs .pl-2 { padding-left: 8px; }
#messageTabs .pb-2 { padding-bottom: 8px; }
#messageTabs .pt-2 { padding-top: 8px; }
#messageTabs .mt-2 {margin-top:8px;}
#messageTabs .mr-2 {margin-right:8px;}
#messageTabs .ml-2 {margin-left:8px;}
#messageTabs .cursor-pointer {cursor: pointer;}
#messageTabs .tabs-header{border-bottom-width: 1px;}
#messageTabs .label-dot{width:5px; height:5px; vertical-align: middle;}
#messageTabs .label-dot.gray { background-color: rgb(100, 117, 139); box-shadow: rgb(255, 255, 255) 0px 0px 0px 0px, rgb(100, 117, 139) 0px 0px 0px 1px, rgba(0, 0, 0, 0) 0px 0px 0px 0px;}
#messageTabs .w-52{width:208px;}
#messageTabs .top-3{top:12px;}
#messageTabs .right-5{right:20px;}
#messageTabs .btn-link{background: 0 0; border-color:rgba(0, 0, 0, 0);}
#messageTabs .w-5\/6 { width: 83.333333%; }
#messageTabs .border-b { border-bottom: 1px solid rgb(235, 237, 243); }
#messageTabs .font-bold { font-weight: 700; }
#messageTabs .justify-center { justify-content: center; }
#messageTabs #messageSettingDropdown { padding: 8px;}

#messageTabs .form, #messageTabs .form-label { display: flex;}
#messageTabs .form {flex-direction: column; gap: 4px;}
#messageTabs .form-horz .form-group,
#messageTabs .form-horz .form-row { align-items: flex-start; display: flex; flex-direction: row;}
#messageTabs .form-horz .form-group { flex-grow: 1; flex-wrap: wrap; min-height: 32px; min-width: 0; padding-left: 96px; position: relative; }
#messageTabs .form-horz .form-group.no-label { padding-left: 0; }
#messageTabs .form-label { align-items: center; color: #3d4667; flex-direction: row; gap: 4px; height: 32px; overflow: hidden; position: relative; text-overflow: ellipsis; white-space: nowrap; }
#messageTabs .form-horz .form-label { justify-content: flex-end; left: 0; padding-left: 16px; padding-right: 8px; position: absolute; top: 0; width: 96px; }

.switch { position: relative; }
.switch>input { position: absolute; top: 0; left: 0; display: block; width: 100%; height: 100%; margin: 0; opacity: 0; }
.switch>label { display: block; padding: 5px 0 5px 35px; margin: 0; font-weight: 400; line-height: 20px; }
.switch>label:after, .switch>label:before {
    position: absolute;
    top: 5px;
    left: 0;
    display: block;
    width: 30px;
    height: 20px;
    pointer-events: none;
    content: ' ';
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    -webkit-transition: all .4s cubic-bezier(.175,.885,.32,1);
    -o-transition: all .4s cubic-bezier(.175, .885, .32, 1);
    transition: all .4s cubic-bezier(.175,.885,.32,1);
}
.switch>label:after {
    top: 6px;
    width: 18px;
    height: 18px;
    border-color: #ccc;
    border-radius: 9px;
    -webkit-box-shadow: rgba(0, 0, 0, .05) 0 1px 4px, rgba(0, 0, 0, .12) 0 1px 2px;
    box-shadow: rgba(0, 0, 0, .05) 0 1px 4px, rgba(0, 0, 0, .12) 0 1px 2px;
}
.switch>input:checked+label:before { background-color: #3280fc; border-color: #3280fc; }
.switch>input:checked+label:after { left: 11px; border-color: #fff; }
#header .messageDropdownBox{z-index:100 !important;}
</style>

<div id='messageTabs' class='text-black pt-10px px-5 pb-5 relative' style='width:400px; background-color:#fff; color: rgb(49, 60, 82)'>
  <ul class="nav nav-tabs">
    <li class='<?php echo $active == 'unread' ? 'active' : '';?>'><a data-tab href="#unread-messages"><span><?php printf($lang->message->unread, $unreadCount)?></span></a></li>
    <li class='<?php echo $active == 'all'    ? 'active' : '';?>'><a data-tab href="#all-messages"><span><?php echo $lang->message->all;?></span></a></li>
  </ul>
  <div class="tab-content" style='padding-top: 10px;'>
    <div class="tab-pane <?php echo $active == 'unread' ? 'active' : '';?>" id="unread-messages">
      <?php echo $buildMessageList($unreadMessages);?>
    </div>
    <div class="tab-pane <?php echo $active == 'all' ? 'active' : '';?>" id="all-messages">
      <?php echo $buildMessageList($allMessages);?>
    </div>
  </div>
  <div class="absolute top-3 right-5" style="z-index: 100;">
    <?php echo html::commonButton("", "title='{$lang->message->notice->allMarkRead}' onclick='markAllRead()'", 'allMarkRead btn btn-sm btn-link', 'clear');?>
    <?php echo html::commonButton("", "title='{$lang->message->notice->clearRead}' onclick='clearRead()'", 'clearRead btn btn-sm btn-link', 'trash');?>
    <span class='messageSettingBox relative'>
      <?php echo html::commonButton("", "id='messageSettingDropdown-toggle' title={$lang->message->browserSetting->more} onclick='toggleSettingDropdown()'", 'btn btn-sm btn-link', 'cog-outline');?>
      <div class="dropdown-menu w-52 absolute" id="messageSettingDropdown" style='left:-170px;top:25px;'>
        <form class="form ajaxForm form-horz" action="<?php echo inlink('ajaxSetOneself');?>" method="post">
          <div class="form-row font-bold border-b pb-2 pl-2 pt-2"><?php echo $lang->message->browserSetting->more;?></div>
          <div class="form-group justify-center form-actions mt-2 no-label">
            <?php echo html::submitButton($lang->save,   "style='min-width:20px;'", "btn btn-sm btn-primary");?>
            <?php echo html::commonButton($lang->cancel, "style='min-width:20px;' onclick='closeSettingDropdown()'", "btn btn-sm");?>
          </div>
        </form>
      </div>
    </span>
  </div>
</div>
