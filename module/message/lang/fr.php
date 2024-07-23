<?php
$lang->message->common    = 'Notification';
$lang->message->index     = 'Accueil';
$lang->message->setting   = 'Paramétrage';
$lang->message->browser   = 'System Notification';
$lang->message->blockUser = 'Block User';

$lang->message->typeList['mail']     = 'Email';
$lang->message->typeList['message']  = 'System Notifications';
$lang->message->typeList['webhook']  = 'Webhook';

$lang->message->browserSetting = new stdclass();
$lang->message->browserSetting->turnon   = 'Notification';
$lang->message->browserSetting->pollTime = 'Intervalle';

$lang->message->browserSetting->pollTimeTip         = 'Polling time can not less than 30 seconds.';
$lang->message->browserSetting->pollTimePlaceholder = 'Notifier les intervalles de temps pour la recherche par secondes.';

$lang->message->browserSetting->turnonList[1] = 'On';
$lang->message->browserSetting->turnonList[0] = 'Off';

$lang->message->unread = 'Unread Messages(%s)';
$lang->message->all    = 'All Messages';

$lang->message->timeLabel['minute'] = '%s minute ago';
$lang->message->timeLabel['hour']   = '1 hour ago';

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
