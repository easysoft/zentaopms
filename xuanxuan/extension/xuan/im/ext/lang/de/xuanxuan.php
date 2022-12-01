<?php
$lang->im->common           = 'Chat';
$lang->im->turnon           = 'On';
$lang->im->help             = 'Help';
$lang->im->settings         = 'Settings';
$lang->im->xxdServer        = 'ZenTao Server';
$lang->im->downloadXXD      = 'Download XXD';
$lang->im->zentaoIntegrate  = 'ZenTao Integrated';
$lang->im->zentaoClient     = 'ZenTao Desktop Client is added!';
$lang->im->getChatUsers     = 'Get Chat Users';
$lang->im->getChatGroups    = 'Get Chat Groups';
$lang->im->notifyMSG        = 'Notification';
$lang->im->sendNotification = 'Push message to notification Center';
$lang->im->sendChatMessage  = 'Push messages to the discussion group';

$lang->im->createBug   = 'Create Bug';
$lang->im->createDoc   = 'Create Doc';
$lang->im->createStory = 'Create Story';
$lang->im->createTask  = 'Create Task';
$lang->im->createTodo  = 'Create Todo';

$lang->im->xxdIsHttps = 'Enable HTTPS';

$lang->im->turnonList = array();
$lang->im->turnonList[1] = 'Enable';
$lang->im->turnonList[0] = 'Disable';

$lang->im->xxClientConfirm = 'Click Download ZenTao Desktop at the right bottom to download it!';
$lang->im->xxServerConfirm = 'Go to User dropmenu  to download the ZenTao Desktop Server!';

$lang->im->xxdServerTip   = 'XXD server address contains protocol, host and port，such as http://192.168.1.35 or http://domain. It should not be 127.0.0.1.';
$lang->im->xxdServerEmpty = 'XXD server address is empty.';
$lang->im->xxdServerError = 'XXD server address should not be 127.0.0.1.';

$lang->im->xxd->aes  = 'Server-side AES';
$lang->im->xxdAESTip = 'This only affects server-side AES encryption between XXB and XXD.';
$lang->im->aesOptions['on']  = 'Enabled';
$lang->im->aesOptions['off'] = 'Disabled';

$lang->im->bot->zentaoBot = new stdclass();
$lang->im->bot->zentaoBot->name = 'Z-bot';
$lang->im->bot->zentaoBot->pageSearchRegex = '/(pageID|recPerPage)=(\d+)/';

$lang->im->bot->zentaoBot->commands = new stdclass();
$lang->im->bot->zentaoBot->commands->view = new stdclass();
$lang->im->bot->zentaoBot->commands->view->description = 'View task';
$lang->im->bot->zentaoBot->commands->start = new stdclass();
$lang->im->bot->zentaoBot->commands->start->description = 'Start task';
$lang->im->bot->zentaoBot->commands->close = new stdclass();
$lang->im->bot->zentaoBot->commands->close->description = 'Close task';
$lang->im->bot->zentaoBot->commands->finish = new stdclass();
$lang->im->bot->zentaoBot->commands->finish->description = 'Finish task';

$lang->im->bot->zentaoBot->condKeywords = array();
$lang->im->bot->zentaoBot->condKeywords['task']            = array('task');
$lang->im->bot->zentaoBot->condKeywords['pri']             = array('pri');
$lang->im->bot->zentaoBot->condKeywords['status']          = array('status');
$lang->im->bot->zentaoBot->condKeywords['assignTo']        = array('assignto', 'user');
$lang->im->bot->zentaoBot->condKeywords['id']              = array('id');
$lang->im->bot->zentaoBot->condKeywords['taskName']        = array('taskname');
$lang->im->bot->zentaoBot->condKeywords['comment']         = array('comment');
$lang->im->bot->zentaoBot->condKeywords['left']            = array('left');
$lang->im->bot->zentaoBot->condKeywords['consumed']        = array('consumed');
$lang->im->bot->zentaoBot->condKeywords['realStarted']     = array('realStarted');
$lang->im->bot->zentaoBot->condKeywords['pageID']          = array('pageID');
$lang->im->bot->zentaoBot->condKeywords['recPerPage']      = array('recPerPage');
$lang->im->bot->zentaoBot->condKeywords['finishedDate']    = array('finishedDate');
$lang->im->bot->zentaoBot->condKeywords['currentConsumed'] = array('currentConsumed');

$lang->im->bot->zentaoBot->success        = 'Command executed successfully.';
$lang->im->bot->zentaoBot->tasksFound     = 'Found %d tasks.';
$lang->im->bot->zentaoBot->prevPage       = 'Prev Page';
$lang->im->bot->zentaoBot->nextPage       = 'Next Page';
$lang->im->bot->zentaoBot->effortRecorded = 'Effort recorded for task #%d.';

$lang->im->bot->zentaoBot->finishTask = 'finish';
$lang->im->bot->zentaoBot->closeTask  = 'close';
$lang->im->bot->zentaoBot->startTask  = 'start';
$lang->im->bot->zentaoBot->viewTask   = 'view';

$lang->im->bot->zentaoBot->errors = new stdclass();
$lang->im->bot->zentaoBot->errors->emptyResult     = 'No task found.';
$lang->im->bot->zentaoBot->errors->invalidCommand  = 'Invalid command.';
$lang->im->bot->zentaoBot->errors->invalidStatus   = 'Cannot perform such action on task with %s status.';
$lang->im->bot->zentaoBot->errors->unauthorized    = 'You are not authorized to perform this action.';
$lang->im->bot->zentaoBot->errors->taskIDRequired  = 'Task ID is required.';
$lang->im->bot->zentaoBot->errors->taskNotFound    = 'Task not found.';

$lang->im->bot->zentaoBot->finish->tip             = 'Click the link below to finish the task. Time consumed and starting time of this task are required.';
$lang->im->bot->zentaoBot->finish->tipLinkTitle    = 'Finish Task';
$lang->im->bot->zentaoBot->finish->done            = 'Task #%d is finished, ended at:%s, time consumed: %f hours.';
$lang->im->bot->zentaoBot->finish->bugTip          = 'Task #%d is associated with a bug, you may mark the bug as resolved by clicking the link below.';
$lang->im->bot->zentaoBot->finish->bugTipLinkTitle = 'Resolve Bug';

$lang->im->bot->zentaoBot->start->tip                = 'Click the link below to start task #%d.';
$lang->im->bot->zentaoBot->start->tipLinkTitle       = 'Start Task';
$lang->im->bot->zentaoBot->start->finishWithZeroLeft = 'Hours left is 0 thus the task is finished.';
