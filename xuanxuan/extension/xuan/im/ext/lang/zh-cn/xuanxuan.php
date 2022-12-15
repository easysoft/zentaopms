<?php
$lang->im->common           = '聊天';
$lang->im->turnon           = '是否打开';
$lang->im->help             = '使用说明';
$lang->im->settings         = '服务器设置';
$lang->im->xxdServer        = '禅道服务器';
$lang->im->downloadXXD      = '下载聊天服务端';
$lang->im->zentaoIntegrate  = '禅道集成';
$lang->im->zentaoClient     = '新增禅道客户端！';
$lang->im->getChatUsers     = '获取用户';
$lang->im->getChatGroups    = '获取讨论组';
$lang->im->notifyMSG        = '消息通知';
$lang->im->sendNotification = '向通知中心推送通知消息';
$lang->im->sendChatMessage  = '向指定的讨论组推送通知消息';

$lang->im->createBug   = '创建 Bug';
$lang->im->createDoc   = '创建文档';
$lang->im->createStory = '创建需求';
$lang->im->createTask  = '创建任务';
$lang->im->createTodo  = '创建待办';

$lang->im->xxdIsHttps = '启用HTTPS';

$lang->im->turnonList = array();
$lang->im->turnonList[1] = '是';
$lang->im->turnonList[0] = '否';

$lang->im->xxClientConfirm = '与禅道深度集成，支持成员沟通，小组讨论，文件传输，任务指派，更加方便的项目管理，更加流畅的团队协作！点击界面右上角用户下拉菜单中下载禅道客户端。';
$lang->im->xxServerConfirm = '与禅道深度集成，支持成员沟通，小组讨论，文件传输，任务指派，更加方便的项目管理，更加流畅的团队协作！进入后台-客户端进行下载配置。';

$lang->im->xxdServerTip    = '禅道服务器地址为完整的协议+地址+端口，示例：http://192.168.1.35 或 http://pms.zentao.com ，不能使用127.0.0.1。';
$lang->im->xxdServerEmpty  = '禅道服务器地址为空。';
$lang->im->xxdServerError  = '禅道服务器地址不能为 127.0.0.1。';

$lang->im->xxd->aes  = '服务端通信 AES';
$lang->im->xxdAESTip = '该设置仅针对 XXB 和 XXD 之间的通讯加密，不影响客户端通讯加密。';
$lang->im->aesOptions['on']  = '开启';
$lang->im->aesOptions['off'] = '关闭';

$lang->im->bot->commonName = '阿道';
$lang->im->bot->welcome->title = '哈喽~我是你的助手阿道';
$lang->im->bot->upgradeWelcome->title = '哈喽~我是你的助手阿道';

$lang->im->bot->zentaoBot = new stdclass();
$lang->im->bot->zentaoBot->name = '禅道';
$lang->im->bot->zentaoBot->pageSearchRegex = '/(pageID|recPerPage|页码|每页数量|頁碼|每頁數量)=(\d+)/';

$lang->im->bot->zentaoBot->commands = new stdclass();
$lang->im->bot->zentaoBot->commands->view = new stdclass();
$lang->im->bot->zentaoBot->commands->view->description = '查看任务';
$lang->im->bot->zentaoBot->commands->view->alias       = array('查看', '搜索', '查询', '筛选');
$lang->im->bot->zentaoBot->commands->start = new stdclass();
$lang->im->bot->zentaoBot->commands->start->description = '开始任务';
$lang->im->bot->zentaoBot->commands->start->alias       = array('开始', '开始任务');
$lang->im->bot->zentaoBot->commands->close = new stdclass();
$lang->im->bot->zentaoBot->commands->close->description = '关闭任务';
$lang->im->bot->zentaoBot->commands->close->alias       = array('关闭', '关闭任务');
$lang->im->bot->zentaoBot->commands->finish = new stdclass();
$lang->im->bot->zentaoBot->commands->finish->description = '完成任务';
$lang->im->bot->zentaoBot->commands->finish->alias       = array('完成', '完成任务');

$lang->im->bot->zentaoBot->condKeywords = array();
$lang->im->bot->zentaoBot->condKeywords['task']            = array('任务', 'task');
$lang->im->bot->zentaoBot->condKeywords['pri']             = array('优先级', 'pri');
$lang->im->bot->zentaoBot->condKeywords['status']          = array('状态', 'status');
$lang->im->bot->zentaoBot->condKeywords['assignTo']        = array('指派人', '指派给', 'assignto', 'user');
$lang->im->bot->zentaoBot->condKeywords['id']              = array('编号', 'id');
$lang->im->bot->zentaoBot->condKeywords['taskName']        = array('任务名', '任务名称', 'taskname');
$lang->im->bot->zentaoBot->condKeywords['comment']         = array('备注', 'comment');
$lang->im->bot->zentaoBot->condKeywords['left']            = array('预计剩余', 'left');
$lang->im->bot->zentaoBot->condKeywords['consumed']        = array('总计消耗', 'consumed');
$lang->im->bot->zentaoBot->condKeywords['realStarted']     = array('实际开始', 'realStarted');
$lang->im->bot->zentaoBot->condKeywords['pageID']          = array('pageID', '页码', '頁碼');
$lang->im->bot->zentaoBot->condKeywords['recPerPage']      = array('recPerPage', '每页数量', '每頁數量');
$lang->im->bot->zentaoBot->condKeywords['finishedDate']    = array('实际完成', 'finishedDate');
$lang->im->bot->zentaoBot->condKeywords['currentConsumed'] = array('本次消耗', 'currentConsumed');

$lang->im->bot->zentaoBot->success        = '指令执行完成';
$lang->im->bot->zentaoBot->tasksFound     = '为您匹配到 %d 项任务';
$lang->im->bot->zentaoBot->prevPage       = '上一页';
$lang->im->bot->zentaoBot->nextPage       = '下一页';
$lang->im->bot->zentaoBot->effortRecorded = '任务 #%d 已完成工时信息填写';

$lang->im->bot->zentaoBot->finishCommand = '完成';
$lang->im->bot->zentaoBot->closeCommand  = '关闭';
$lang->im->bot->zentaoBot->startCommand  = '开始';
$lang->im->bot->zentaoBot->viewCommand   = '查看';

$lang->im->bot->zentaoBot->errors = new stdclass();
$lang->im->bot->zentaoBot->errors->emptyResult     = '未查询到相关匹配信息';
$lang->im->bot->zentaoBot->errors->invalidCommand  = '无法识别该指令';
$lang->im->bot->zentaoBot->errors->invalidStatus   = '检测到该任务为%s状态，无法实现指令操作';
$lang->im->bot->zentaoBot->errors->unauthorized    = '您无权操作此任务';
$lang->im->bot->zentaoBot->errors->taskIDRequired  = '请输入任务编号';
$lang->im->bot->zentaoBot->errors->taskNotFound    = '任务不存在';

$lang->im->bot->zentaoBot->finish = new stdclass();
$lang->im->bot->zentaoBot->finish->tip             = '完成任务指令需要填入工时与记录起始时间，请点击下方入口';
$lang->im->bot->zentaoBot->finish->tipLinkTitle    = '工时记录';
$lang->im->bot->zentaoBot->finish->done            = '任务 #%d 已完成，完成时间：%s，消耗：%.1f 小时';
$lang->im->bot->zentaoBot->finish->bugTip          = '检测到任务 #%d 关联相关 Bug，您可以点击以下链接进行处理';
$lang->im->bot->zentaoBot->finish->bugTipLinkTitle = '关联 Bug 处理';

$lang->im->bot->zentaoBot->start = new stdclass();
$lang->im->bot->zentaoBot->start->tip                = '点击链接开始任务 #%d';
$lang->im->bot->zentaoBot->start->tipLinkTitle       = '开始任务';
$lang->im->bot->zentaoBot->start->finishWithZeroLeft = '剩余工时为 0，任务将被标记为"已完成"';

$lang->im->bot->zentaoBot->help = <<<EOT
### 1. 任务查询指令

指令：`查看 任务 条件a 值a1，值a2 条件b 值b1，值b2···条件n 值n1，值n2`
示例：`查看 任务 阿道 P1 进行中` 显示指派给阿道、优先级为P1且状态为进行中的任务

| 命令 | 描述 |
| ---- | ---- |
| 查看 任务 			| 显示当前用户名下所有未关闭的任务 |
| 查看 任务 名称关键字	| 显示匹配到名称关键字的任务 |
| 查看 任务 指派人	| 显示指派人为输入值的任务 |
| 查看 任务 优先级	| 显示优先级为输入值的任务 |
| 查看 任务 状态 		| 显示状态为输入值的任务 |
| 查看 任务 ID		| 显示ID为输入值的任务 |

### 2. 任务编辑指令

任务编辑指令支持对任务进行状态变更。

| 命令 | 描述 |
| ---- | ---- |
| 开始 任务 #ID 	| 开始任务并记录其消耗/剩余工时 |
| 完成 任务 #ID	| 完成任务并记录其消耗/剩余工时 |
| 关闭 任务 #ID	| 关闭任务并记录其消耗/剩余工时 |
EOT;
