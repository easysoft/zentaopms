<?php
$lang->message->common     = '系统通知';
$lang->message->index      = '首页';
$lang->message->setting    = '设置';
$lang->message->browser    = '系统通知';
$lang->message->blockUser  = '不通知人员';
$lang->message->markUnread = '标为未读';

$lang->message->typeList['mail']     = '邮件';
$lang->message->typeList['message']  = '系统通知';
$lang->message->typeList['webhook']  = 'Webhook';

$lang->message->browserSetting = new stdclass();
$lang->message->browserSetting->turnon   = '是否打开';
$lang->message->browserSetting->pollTime = '轮询时间';

$lang->message->browserSetting->pollTimeTip         = '轮询时间不能小于30秒。';
$lang->message->browserSetting->pollTimePlaceholder = '通知的时间间隔，以秒为单位。';

$lang->message->browserSetting->turnonList[1] = '打开';
$lang->message->browserSetting->turnonList[0] = '关闭';

$lang->message->browserSetting->more    = '更多设置';
$lang->message->browserSetting->show    = '浏览器通知';
$lang->message->browserSetting->count   = '计数提醒';
$lang->message->browserSetting->maxDays = '保留天数';

$lang->message->unread = '未读消息(%s)';
$lang->message->all    = '全部消息';

$lang->message->timeLabel['minute'] = '%s分钟前';
$lang->message->timeLabel['hour']   = '1小时前';

$lang->message->mention = '%s在%s《%s》中@了你，请及时查看。';

$lang->message->noticeText = new stdclass();
$lang->message->noticeText->opened         = '{actor} 创建了新的{objectType}《{objectTitle}》。';
$lang->message->noticeText->created        = $lang->message->noticeText->opened;
$lang->message->noticeText->added          = $lang->message->noticeText->opened;
$lang->message->noticeText->edited         = '{actor} 编辑了{objectType}《{objectTitle}》的内容。';
$lang->message->noticeText->commented      = '{actor} 在{objectType}《{objectTitle}》下添加了新的备注。';
$lang->message->noticeText->changed        = '{actor} 变更了{objectType}《{objectTitle}》的内容。';
$lang->message->noticeText->submitreview   = '{actor} 提交了{objectType}《{objectTitle}》的评审，请您及时审批。';
$lang->message->noticeText->reviewed       = '您提交的{objectType}《{objectTitle}》已由 {actor} 处理，结果为：{reviewResult}。{reviewComment}';
$lang->message->noticeText->otherreviewed  = '您提交的{objectType}《{objectTitle}》已由 {actor} 处理，结果为：{reviewResult}。';
$lang->message->noticeText->reverted       = '{actor} 将{objectType}《{objectTitle}》回退，请您修改后重新提交。';
$lang->message->noticeText->forwarded      = '{actor} 将{objectType}《{objectTitle}》转交给了您，请及时处理。';
$lang->message->noticeText->assigned       = '{actor} 将{objectType}《{objectTitle}》指派给了您，请及时处理。';
$lang->message->noticeText->mentioned      = '{actor} 在{objectType}《{objectTitle}》中提到了您，请及时查看。';
$lang->message->noticeText->converttotask  = '{actor} 将{objectType}《{objectTitle}》转为任务。';
$lang->message->noticeText->totask         = $lang->message->noticeText->converttotask;
$lang->message->noticeText->tobug          = '{actor} 将{objectType}《{objectTitle}》转为Bug。';
$lang->message->noticeText->frombug        = "{actor} 将Bug转为{$lang->SRCommon}《{objectTitle}》。";
$lang->message->noticeText->tostory        = "{actor} 将{objectType}《{objectTitle}》转为{$lang->SRCommon}。";
$lang->message->noticeText->touserstory    = "{actor} 将{objectType}《{objectTitle}》转为{$lang->URCommon}。";
$lang->message->noticeText->toepic         = "{actor} 将{objectType}《{objectTitle}》转为{$lang->ERCommon}。";
$lang->message->noticeText->totodo         = '{actor} 将{objectType}《{objectTitle}》转为待办。';
$lang->message->noticeText->toticket       = '{actor} 将{objectType}《{objectTitle}》转为工单。';
$lang->message->noticeText->todemand       = '{actor} 将{objectType}《{objectTitle}》转为需求池需求。';
$lang->message->noticeText->confirmed      = "{actor}确认了{objectType}《{objectTitle}》的{$lang->SRCommon}变更。";
$lang->message->noticeText->aitaskfinished = '您的数字员工任务《{objectTitle}》已完成。';
$lang->message->noticeText->aitaskfailed   = '您的数字员工任务《{objectTitle}》执行失败。';
$lang->message->noticeText->minuted        = '{actor}记录了会议纪要《{objectTitle}》。';
$lang->message->noticeText->generic        = '{actor} {action}{objectType}《{objectTitle}》。';
$lang->message->noticeText->moved          = '{actor} 移动了看板卡片《{objectTitle}》，';
$lang->message->noticeText->skipColSuffix  = '从 {fromColTitle} 列到 {toColTitle} 列。';
$lang->message->noticeText->skipLaneSuffix = '从 {fromLaneTitle}泳道的 {fromColTitle} 列到 {toLaneTitle}泳道的 {toColTitle} 列。';
$lang->message->noticeText->ccSuffix       = '您已被抄送。';

$lang->message->noticeText->extend['submitreview']['cc']     = '{actor} 提交了{objectType}《{objectTitle}》的评审，您已被抄送。';
$lang->message->noticeText->extend['submitreview']['other']  = '{actor} 提交了{objectType}《{objectTitle}》的评审。';
$lang->message->noticeText->extend['reviewed']['cc']         = '{submitter} 提交的{objectType}《{objectTitle}》已由 {actor} 处理，结果为：{reviewResult}。{reviewComment}您已被抄送。';
$lang->message->noticeText->extend['reviewed']['other']      = '{submitter} 提交的{objectType}《{objectTitle}》已由 {actor} 处理，结果为：{reviewResult}。{reviewComment}';
$lang->message->noticeText->extend['otherreviewed']['cc']    = '{submitter} 提交的{objectType}《{objectTitle}》已由 {actor} 处理，结果为：{reviewResult}。您已被抄送。';
$lang->message->noticeText->extend['otherreviewed']['other'] = '{submitter} 提交的{objectType}《{objectTitle}》已由 {actor} 处理，结果为：{reviewResult}。';
$lang->message->noticeText->extend['reverted']['cc']         = '{actor} 将{objectType}《{objectTitle}》回退，您已被抄送。';
$lang->message->noticeText->extend['forwarded']['cc']        = '{actor} 将{objectType}《{objectTitle}》转交给 {assignedTo} 处理，您已被抄送。';
$lang->message->noticeText->extend['assigned']['cc']         = '{actor} 将{objectType}《{objectTitle}》指派给了{assignedTo}，您已被抄送。';
$lang->message->noticeText->extend['assigned']['other']      = '{actor} 将{objectType}《{objectTitle}》指派给了{assignedTo}。';

$lang->message->noticeTitle = new stdclass();
$lang->message->noticeTitle->opened         = '新的{objectType}已创建';
$lang->message->noticeTitle->created        = $lang->message->noticeTitle->opened;
$lang->message->noticeTitle->added          = $lang->message->noticeTitle->opened;
$lang->message->noticeTitle->edited         = '{objectType}信息已更新';
$lang->message->noticeTitle->commented      = '{objectType}有新备注';
$lang->message->noticeTitle->changed        = '{objectType}发生变更';
$lang->message->noticeTitle->submitreview   = '{objectType}提交评审';
$lang->message->noticeTitle->reviewed       = '{objectType}评审结果通知';
$lang->message->noticeTitle->reverted       = '项目评审已回退';
$lang->message->noticeTitle->forwarded      = '项目评审已转交';
$lang->message->noticeTitle->assigned       = '您被指派处理{objectType}';
$lang->message->noticeTitle->mentioned      = '有人在{objectType}中提到了您';
$lang->message->noticeTitle->converttotask  = '{objectType}已转为任务';
$lang->message->noticeTitle->totask         = $lang->message->noticeTitle->converttotask;
$lang->message->noticeTitle->tobug          = '{objectType}已转为Bug';
$lang->message->noticeTitle->frombug        = "Bug已转为{$lang->SRCommon}";
$lang->message->noticeTitle->tostory        = "{objectType}已转为{$lang->SRCommon}";
$lang->message->noticeTitle->touserstory    = "{objectType}已转为{$lang->URCommon}";
$lang->message->noticeTitle->toepic         = "{objectType}已转为{$lang->ERCommon}";
$lang->message->noticeTitle->totodo         = '{objectType}已转为待办';
$lang->message->noticeTitle->toticket       = '{objectType}已转为工单';
$lang->message->noticeTitle->todemand       = '{objectType}已转为需求池需求';
$lang->message->noticeTitle->confirmed      = "{$lang->SRCommon}变更已确认";
$lang->message->noticeTitle->aitaskfinished = '数字员工任务完成';
$lang->message->noticeTitle->aitaskfailed   = '数字员工任务执行失败';
$lang->message->noticeTitle->generic        = '{objectType}已{action}';
$lang->message->noticeTitle->minuted        = $lang->message->noticeTitle->generic;

$lang->message->noticeTitle->extend['assigned']['cc']    = '{assignedTo}被指派处理{objectType}';
$lang->message->noticeTitle->extend['assigned']['other'] = $lang->message->noticeTitle->extend['assigned']['cc'];

$lang->message->reviewResultList['pass']    = '通过';
$lang->message->reviewResultList['reject']  = '不通过';
$lang->message->reviewResultList['revert']  = '撤销';
$lang->message->reviewResultList['clarify'] = '有待明确';

$lang->message->storyReviewResultList['pass']    = '确认通过';
$lang->message->storyReviewResultList['revert']  = '撤销变更';
$lang->message->storyReviewResultList['clarify'] = '有待明确';
$lang->message->storyReviewResultList['reject']  = '拒绝';

$lang->message->reviewCommentTemplate = '审批意见：%s。';

$lang->message->notice = new stdclass();
$lang->message->notice->allMarkRead = '一键已读';
$lang->message->notice->clearRead   = '清空已读';

$lang->message->error = new stdclass();
$lang->message->error->maxDaysFormat  = '保留天数只能填写正整数';
$lang->message->error->maxDaysValue   = '保留天数不能小于0。';

$lang->message->label = new stdclass();
$lang->message->label->created      = '创建';
$lang->message->label->opened       = '创建';
$lang->message->label->changed      = '变更';
$lang->message->label->releaseddoc  = '发布';
$lang->message->label->edited       = '编辑';
$lang->message->label->assigned     = '指派';
$lang->message->label->closed       = '关闭';
$lang->message->label->deleted      = '删除';
$lang->message->label->undeleted    = '还原';
$lang->message->label->commented    = '备注';
$lang->message->label->activated    = '激活';
$lang->message->label->resolved     = '解决';
$lang->message->label->submitreview = '提交评审';
$lang->message->label->reviewed     = '评审';
$lang->message->label->confirmed    = "确认{$lang->SRCommon}";
$lang->message->label->frombug      = "转{$lang->SRCommon}";
$lang->message->label->started      = '开始';
$lang->message->label->delayed      = '延期';
$lang->message->label->suspended    = '挂起';
$lang->message->label->finished     = '完成';
$lang->message->label->paused       = '暂停';
$lang->message->label->canceled     = '取消';
$lang->message->label->restarted    = '继续';
$lang->message->label->blocked      = '阻塞';
$lang->message->label->bugconfirmed = '确认';
$lang->message->label->compilepass  = '构建通过';
$lang->message->label->compilefail  = '构建失败';
$lang->message->label->archived     = '归档';
$lang->message->label->restore      = '还原';
$lang->message->label->moved        = '移动';
$lang->message->label->nearing      = '到期提醒';
$lang->message->label->published    = '发布';
$lang->message->label->changestatus = '修改发布状态';
$lang->message->label->mentioned    = '@通知';
