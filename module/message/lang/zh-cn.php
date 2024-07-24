<?php
$lang->message->common    = '消息';
$lang->message->index     = '首页';
$lang->message->setting   = '设置';
$lang->message->browser   = '系统通知';
$lang->message->blockUser = '不通知人员';

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

$lang->message->notice = new stdclass();
$lang->message->notice->confirmDelete = "您确定要删除消息吗？";
$lang->message->notice->allMarkRead   = '一键已读';
$lang->message->notice->deleteAllRead = '清空已读';

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
