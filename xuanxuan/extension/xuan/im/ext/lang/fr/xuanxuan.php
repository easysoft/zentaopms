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
$lang->im->bot->zentaoBot->name = 'ZenTao';
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

$lang->im->bot->zentaoBot->finishCommand = 'finish';
$lang->im->bot->zentaoBot->closeCommand  = 'close';
$lang->im->bot->zentaoBot->startCommand  = 'start';
$lang->im->bot->zentaoBot->viewCommand   = 'view';

$lang->im->bot->zentaoBot->errors = new stdclass();
$lang->im->bot->zentaoBot->errors->emptyResult     = 'No task found.';
$lang->im->bot->zentaoBot->errors->invalidCommand  = 'Invalid command.';
$lang->im->bot->zentaoBot->errors->invalidStatus   = 'Cannot perform such action on task with %s status.';
$lang->im->bot->zentaoBot->errors->unauthorized    = 'You are not authorized to perform this action.';
$lang->im->bot->zentaoBot->errors->taskIDRequired  = 'Task ID is required.';
$lang->im->bot->zentaoBot->errors->taskNotFound    = 'Task not found.';

$lang->im->bot->zentaoBot->finish = new stdclass();
$lang->im->bot->zentaoBot->finish->tip             = 'Click the link below to finish the task. Time consumed and starting time of this task are required.';
$lang->im->bot->zentaoBot->finish->tipLinkTitle    = 'Finish Task';
$lang->im->bot->zentaoBot->finish->done            = 'Task #%d is finished, ended at:%s, time consumed: %.1f hours.';
$lang->im->bot->zentaoBot->finish->bugTip          = 'Task #%d is associated with a bug, you may mark the bug as resolved by clicking the link below.';
$lang->im->bot->zentaoBot->finish->bugTipLinkTitle = 'Resolve Bug';

$lang->im->bot->zentaoBot->start = new stdclass();
$lang->im->bot->zentaoBot->start->tip                = 'Click the link below to start task #%d.';
$lang->im->bot->zentaoBot->start->tipLinkTitle       = 'Start Task';
$lang->im->bot->zentaoBot->start->finishWithZeroLeft = 'Hours left is 0 thus the task is finished.';

$lang->im->bot->zentaoBot->help = <<<EOT
### 1. Task command

Command：`view task condition...`
Example：`view task dev1 P1 doing` Displays tasks assigned to dev1, with priority P1 and status in progress

| Command | Description |
| ---- | ---- |
| view task | Show all open tasks under the current username |
| view task Name Keyword | Show tasks that match the name keyword |
| view task Assignor | Show tasks whose assignor is the entered value |
| view task Priority | Show tasks with priority as entered |
| view task Status | Show tasks with status as input |
| view task ID | Show tasks with ID as input |

### 2. Task Edit command
The Task Edit command supports making status changes to tasks.

| Command | Description |
| ---- | ---- |
| start task #ID | Start the task and record its consumption/remaining hours |
| complete task #ID | Complete the task and record its consumption/remaining hours |
| close task #ID | Close the task and record its consumption/remaining work hours |
EOT;
