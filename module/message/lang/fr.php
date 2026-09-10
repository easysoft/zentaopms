<?php
$lang->message->common     = 'Notification';
$lang->message->index      = 'Accueil';
$lang->message->setting    = 'Paramétrage';
$lang->message->browser    = 'System Notification';
$lang->message->blockUser  = 'Block User';
$lang->message->markUnread = 'Mark unread';

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

$lang->message->browserSetting->more    = 'More Settings';
$lang->message->browserSetting->show    = 'Browser Notification';
$lang->message->browserSetting->count   = 'Count Reminder';
$lang->message->browserSetting->maxDays = 'Retention Days';

$lang->message->unread = 'Unread Messages(%s)';
$lang->message->all    = 'All Messages';

$lang->message->timeLabel['minute'] = '%s minute ago';
$lang->message->timeLabel['hour']   = '1 hour ago';

$lang->message->mention = '%s mentioned you in %s《%s》. Please check it in time.';

$lang->message->noticeText = new stdclass();
$lang->message->noticeText->opened         = '{actor} has created a new {objectType} 《{objectTitle}》.';
$lang->message->noticeText->created        = $lang->message->noticeText->opened;
$lang->message->noticeText->added          = $lang->message->noticeText->opened;
$lang->message->noticeText->edited         = '{actor} edited the content of {objectType} 《{objectTitle}》.';
$lang->message->noticeText->commented      = '{actor} has added a new comment under {objectType}《{objectTitle}》.';
$lang->message->noticeText->changed        = '{actor} has changed the content of {objectType} 《{objectTitle}》.';
$lang->message->noticeText->submitreview   = '{actor} has submitted a review for {objectType}《{objectTitle}》, please approve it in a timely manner.';
$lang->message->noticeText->reviewed       = 'The {objectType} 《{objectTitle}》 you submitted has been processed by {actor}, with the result being: {reviewResult}. {reviewComment}';
$lang->message->noticeText->otherreviewed  = 'The {objectType} 《{objectTitle}》 you submitted has been processed by {actor}, with the result being: {reviewResult}';
$lang->message->noticeText->reverted       = '{actor} has rolled back {objectType} 《{objectTitle}》 and requested you to make modifications and resubmit it.';
$lang->message->noticeText->forwarded      = '{actor} has transferred {objectType}《{objectTitle}》to you. Please handle it promptly.';
$lang->message->noticeText->assigned       = '{actor} has assigned {objectType}《{objectTitle}》to you. Please handle it promptly.';
$lang->message->noticeText->mentioned      = '{actor} mentioned you in {objectType}《{objectTitle}》, please check it in time.';
$lang->message->noticeText->converttotask  = '{actor} converts {objectType}《{objectTitle}》 into a task.';
$lang->message->noticeText->totask         = $lang->message->noticeText->converttotask;
$lang->message->noticeText->tobug          = '{actor} converts {objectType}《{objectTitle}》into a bug.';
$lang->message->noticeText->frombug        = "{actor} converts bug into a {$lang->SRCommon}《{objectTitle}》.";
$lang->message->noticeText->tostory        = "{actor} converts {objectType}《{objectTitle}》into a {$lang->SRCommon}.";
$lang->message->noticeText->touserstory    = "{actor} converts {objectType}《{objectTitle}》into a {$lang->URCommon}.";
$lang->message->noticeText->toepic         = "{actor} converts {objectType}《{objectTitle}》into a {$lang->ERCommon}.";
$lang->message->noticeText->totodo         = '{actor} converts {objectType}《{objectTitle}》into a todo.';
$lang->message->noticeText->toticket       = '{actor} converts {objectType}《{objectTitle}》into a ticket.';
$lang->message->noticeText->todemand       = '{actor} converts {objectType}《{objectTitle}》into a demand.';
$lang->message->noticeText->confirmed      = "{actor}  has confirmed the {$lang->SRCommon} change for {objectType} 《{objectTitle}》.";
$lang->message->noticeText->aitaskfinished = 'Your AI task 《{objectTitle}》has been completed.';
$lang->message->noticeText->aitaskfailed   = 'Your AI task 《{objectTitle}》has failed.';
$lang->message->noticeText->minuted        = '{actor} recorded the meeting minutes titled 《{objectTitle}》.';
$lang->message->noticeText->generic        = '{actor} {action}{objectType}《{objectTitle}》。';
$lang->message->noticeText->ccSuffix       = "You have been CC'd.";

$lang->message->noticeText->extend['submitreview']['cc']     = '{actor} has submitted a review for {objectType}《{objectTitle}》, and you have been CCed.';
$lang->message->noticeText->extend['submitreview']['other']  = '{actor} submitted a review for {objectType}《{objectTitle}》.';
$lang->message->noticeText->extend['reviewed']['cc']         = 'The {objectType} submitted by {submitter}, titled 《{objectTitle}》, has been processed by {actor}, with the result being: {reviewResult}. {reviewComment} You have been copied.';
$lang->message->noticeText->extend['reviewed']['other']      = 'The {objectType} submitted by {submitter}, titled 《{objectTitle}》, has been processed by {actor}, with the result being: {reviewResult}. {reviewComment}';
$lang->message->noticeText->extend['otherreviewed']['cc']    = 'The {objectType} submitted by {submitter}, titled 《{objectTitle}》, has been processed by {actor}, with the result being: {reviewResult}. You have been copied.';
$lang->message->noticeText->extend['otherreviewed']['other'] = 'The {objectType} submitted by {submitter}, titled 《{objectTitle}》, has been processed by {actor}, with the result being: {reviewResult}.';
$lang->message->noticeText->extend['reverted']['cc']         = '{actor} has rolled back {objectType}《{objectTitle}》and you have been CCed.';
$lang->message->noticeText->extend['forwarded']['cc']        = '{actor} has transferred the {objectType} 《{objectTitle}》 to {assignedTo} for processing. You have been CCed.';
$lang->message->noticeText->extend['assigned']['cc']         = '{actor} has assigned {objectType}《{objectTitle}》to {assignedTo}. You have been CCed.';
$lang->message->noticeText->extend['assigned']['other']      = '{actor} has assigned {objectType}《{objectTitle}》to {assignedTo}.';

$lang->message->noticeTitle = new stdclass();
$lang->message->noticeTitle->opened         = 'The new {objectType} has been created';
$lang->message->noticeTitle->created        = $lang->message->noticeTitle->opened;
$lang->message->noticeTitle->added          = $lang->message->noticeTitle->opened;
$lang->message->noticeTitle->edited         = '{objectType} information has been updated';
$lang->message->noticeTitle->commented      = 'New note on {objectType}';
$lang->message->noticeTitle->changed        = 'Change in {objectType}';
$lang->message->noticeTitle->submitreview   = '{objectType} Submission Review';
$lang->message->noticeTitle->reviewed       = '{objectType} Review Result Notice';
$lang->message->noticeTitle->reverted       = 'The project review has been rolled back';
$lang->message->noticeTitle->forwarded      = 'The project review has been handed over';
$lang->message->noticeTitle->assigned       = 'You have been assigned to handle {objectType}';
$lang->message->noticeTitle->mentioned      = 'Someone mentioned you in {objectType}';
$lang->message->noticeTitle->converttotask  = '{objectType} has been converted into a task';
$lang->message->noticeTitle->totask         = $lang->message->noticeTitle->converttotask;
$lang->message->noticeTitle->tobug          = '{objectType} has been converted into a bug';
$lang->message->noticeTitle->frombug        = "Bug has been converted into a {$lang->SRCommon}";
$lang->message->noticeTitle->tostory        = "{objectType} has been converted into a {$lang->SRCommon}";
$lang->message->noticeTitle->touserstory    = "{objectType} has been converted into a {$lang->URCommon}";
$lang->message->noticeTitle->toepic         = "{objectType} has been converted into a {$lang->ERCommon}";
$lang->message->noticeTitle->totodo         = '{objectType} has been converted into a todo';
$lang->message->noticeTitle->toticket       = '{objectType} has been converted into a ticket';
$lang->message->noticeTitle->todemand       = '{objectType} has been converted into a demand';
$lang->message->noticeTitle->confirmed      = "{$lang->SRCommon} change has been confirmed";
$lang->message->noticeTitle->aitaskfinished = 'AI task has been completed';
$lang->message->noticeTitle->aitaskfailed   = 'AI task has failed';
$lang->message->noticeTitle->generic        = '{objectType} has been {action}';
$lang->message->noticeTitle->minuted        = $lang->message->noticeTitle->generic;

$lang->message->noticeTitle->extend['assigned']['cc']    = '{assignedTo} is assigned to handle {objectType}';
$lang->message->noticeTitle->extend['assigned']['other'] = $lang->message->noticeTitle->extend['assigned']['cc'];

$lang->message->reviewResultList['pass']    = 'Pass';
$lang->message->reviewResultList['reject']  = 'Reject';
$lang->message->reviewResultList['revert']  = 'Revert';
$lang->message->reviewResultList['clarify'] = 'Clarify';

$lang->message->storyReviewResultList['pass']    = 'Pass';
$lang->message->storyReviewResultList['revert']  = 'Revoke';
$lang->message->storyReviewResultList['clarify'] = 'To Be Clarified';
$lang->message->storyReviewResultList['reject']  = 'Reject';

$lang->message->reviewCommentTemplate = 'Approval comment: %s.';

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
$lang->message->label->nearing      = 'Due Reminder';
$lang->message->label->published    = 'publish';
$lang->message->label->changestatus = 'change status';
$lang->message->label->mentioned    = '@Mention';
