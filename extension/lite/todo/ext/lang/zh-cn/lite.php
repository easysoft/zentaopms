<?php
$lang->todo->typeList['task'] = '任务';

if($this->config->visions == ',lite,') unset($lang->todo->typeList['feedback']);
unset($lang->todo->typeList['bug']);
unset($lang->todo->typeList['testtask']);
unset($lang->todo->typeList['review']);
unset($lang->todo->typeList['issue']);
unset($lang->todo->typeList['risk']);
unset($lang->todo->typeList['opportunity']);
unset($lang->todo->typeList['meeting']);
