<?php
$lang->message->common     = 'Notification';
$lang->message->index      = 'Homepage';
$lang->message->setting    = 'Settings';
$lang->message->browser    = 'System Notification';
$lang->message->blockUser  = 'Blocked Users';
$lang->message->markUnread = 'Mark as Unread';

$lang->message->typeList['mail']     = 'Email';
$lang->message->typeList['message']  = 'System Notification';
$lang->message->typeList['webhook']  = 'Webhook';

$lang->message->browserSetting = new stdclass();
$lang->message->browserSetting->turnon   = 'Notification';
$lang->message->browserSetting->pollTime = 'Polling Interval';

$lang->message->browserSetting->pollTimeTip         = 'Polling interval cannot be less than 30 seconds.';
$lang->message->browserSetting->pollTimePlaceholder = 'Set the interval for checking notifications (in seconds).';

$lang->message->browserSetting->turnonList[1] = 'On';
$lang->message->browserSetting->turnonList[0] = 'Off';

$lang->message->browserSetting->more    = 'More Settings';
$lang->message->browserSetting->show    = 'Browser Notification';
$lang->message->browserSetting->count   = 'Notification Count';
$lang->message->browserSetting->maxDays = 'Retention Days';

$lang->message->unread = 'Unread Messages(%s)';
$lang->message->all    = 'All Messages';

$lang->message->timeLabel['minute'] = '%s minutes ago';
$lang->message->timeLabel['hour']   = '1 hour ago';

$lang->message->notice = new stdclass();
$lang->message->notice->allMarkRead = 'Mark All as Read';
$lang->message->notice->clearRead   = 'Clear read';

$lang->message->error = new stdclass();
$lang->message->error->maxDaysFormat  = 'Retention days must be a positive integer.';
$lang->message->error->maxDaysValue   = 'Retention Days cannot be less than 0.';

$lang->message->label = new stdclass();
$lang->message->label->created      = 'Create';
$lang->message->label->opened       = 'Create';
$lang->message->label->changed      = 'Change';
$lang->message->label->releaseddoc  = 'Release';
$lang->message->label->edited       = 'Edit';
$lang->message->label->assigned     = 'Assign';
$lang->message->label->closed       = 'Close';
$lang->message->label->deleted      = 'Delete';
$lang->message->label->undeleted    = 'Restore';
$lang->message->label->commented    = 'Comment';
$lang->message->label->activated    = 'Activate';
$lang->message->label->resolved     = 'Resolve';
$lang->message->label->submitreview = 'Submit Review';
$lang->message->label->reviewed     = 'Review';
$lang->message->label->confirmed    = "Confirm {$lang->SRCommon}";
$lang->message->label->frombug      = "Convert to {$lang->SRCommon}";
$lang->message->label->started      = 'Start';
$lang->message->label->delayed      = 'Delay';
$lang->message->label->suspended    = 'On Hold';
$lang->message->label->finished     = 'Complete';
$lang->message->label->paused       = 'Pause';
$lang->message->label->canceled     = 'Cancle';
$lang->message->label->restarted    = 'Continue';
$lang->message->label->blocked      = 'Block';
$lang->message->label->bugconfirmed = 'Confirm';
$lang->message->label->compilepass  = 'Build Passed';
$lang->message->label->compilefail  = 'Build Failed';
$lang->message->label->archived     = 'Archive';
$lang->message->label->restore      = 'Restore';
$lang->message->label->moved        = 'Move';
$lang->message->label->nearing      = 'Due Reminder';
$lang->message->label->published    = 'Release';
$lang->message->label->changestatus = 'Change Release Status';
