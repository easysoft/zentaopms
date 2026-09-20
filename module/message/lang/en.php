<?php
$lang->message->common     = 'System Notification';
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
$lang->message->noticeText->moved          = '{actor} has moved kanban card《{objectTitle}》, ';
$lang->message->noticeText->skipColSuffix  = 'from {fromColTitle} to {toColTitle}.';
$lang->message->noticeText->skipLaneSuffix = "from {fromLaneTitle} lane's {fromColTitle} to {toLaneTitle} lane's {toColTitle}.";
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
$lang->message->label->mentioned    = '@Mention';
