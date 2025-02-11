<?php
if(!isset($lang->excel)) $lang->excel = new stdclass();
if(!isset($lang->excel->help)) $lang->excel->help = new stdclass();
$lang->excel->help->task = "When adding a task, the task name and task type are required fields. If not filled in, the data will be ignored when importing;\nIf you need to add a multi-person task, please add it in the \"Initial Expected\" column in the format of \"Username: {$lang->hourCommon}\", and separate multiple users with newlines. The user name is viewed in column G of the \"System Data\" worksheet. \nPlease fill in \"Mode\" for multiplayer tasks. Fill in \"Mode\" for non-multiplayer tasks. When importing, the system will automatically leave \"Mode\" blank.";
