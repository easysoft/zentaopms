<?php
$lang->message->common     = 'Notification';
$lang->message->index      = 'Home';
$lang->message->setting    = 'Settings';
$lang->message->browser    = 'System Notification';
$lang->message->blockUser  = 'Block User';
$lang->message->markUnread = 'Mark unread';

$lang->message->typeList['mail']     = 'Email';
$lang->message->typeList['message']  = 'System Notifications';
$lang->message->typeList['webhook']  = 'Webhook';

$lang->message->browserSetting = new stdclass();
$lang->message->browserSetting->turnon   = 'Notification';
$lang->message->browserSetting->pollTime = 'Polling Time';

$lang->message->browserSetting->pollTimeTip         = 'Polling time can not less than 30 seconds.';
$lang->message->browserSetting->pollTimePlaceholder = 'Notify the time intervals for the search by seconds.';

$lang->message->browserSetting->turnonList[1] = 'On';
$lang->message->browserSetting->turnonList[0] = 'Off';

$lang->message->browserSetting->more    = 'More Settings';
$lang->message->browserSetting->show    = 'Browser Notification';
$lang->message->browserSetting->count   = 'Count Reminder';
$lang->message->browserSetting->maxDays = 'Retention Days';

$lang->message->unread = 'Unread Messages(%s)';
$lang->message->all    = 'All Messages';

$lang->message->timeLabel['minute'] = '%s minute ago';
$lang->message->timeLabel['hour']   = '1 hour ago';

$lang->message->notice = new stdclass();
$lang->message->notice->allMarkRead = 'One-click read';
$lang->message->notice->clearRead   = 'Clear read';

$lang->message->error = new stdclass();
$lang->message->error->maxDaysFormat  = 'Retention Days can only be filled in with positive integer.';
$lang->message->error->maxDaysValue   = 'Retention Days cannot be less than 0.';

$lang->message->label = new stdclass();
$lang->message->label->created      = 'create';
$lang->message->label->opened       = 'open';
$lang->message->label->changed      = 'change';
$lang->message->label->releaseddoc  = 'release';
$lang->message->label->edited       = 'edit';
$lang->message->label->assigned     = 'assign';
$lang->message->label->closed       = 'close';
$lang->message->label->deleted      = 'delete';
$lang->message->label->undeleted    = 'restore';
$lang->message->label->commented    = 'comment';
$lang->message->label->activated    = 'activate';
$lang->message->label->resolved     = 'resolve';
$lang->message->label->submitreview = 'Submit Review';
$lang->message->label->reviewed     = 'review';
$lang->message->label->confirmed    = 'confirm Story';
$lang->message->label->frombug      = 'convert from Bug';
$lang->message->label->started      = 'start';
$lang->message->label->delayed      = 'delay';
$lang->message->label->suspended    = 'suspend';
$lang->message->label->finished     = 'finish';
$lang->message->label->paused       = 'pause';
$lang->message->label->canceled     = 'cancel';
$lang->message->label->restarted    = 'continue';
$lang->message->label->blocked      = 'block';
$lang->message->label->bugconfirmed = 'confirm';
$lang->message->label->compilepass  = 'compile pass';
$lang->message->label->compilefail  = 'compile fail';
$lang->message->label->archived     = 'archive';
$lang->message->label->restore      = 'restore';
$lang->message->label->moved        = 'move';
$lang->message->label->published    = 'publish';
$lang->message->label->changestatus = 'change status';
