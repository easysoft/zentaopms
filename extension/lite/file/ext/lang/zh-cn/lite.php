<?php
if(!isset($lang->excel)) $lang->excel = new stdclass();
if(!isset($lang->excel->help)) $lang->excel->help = new stdclass();
$lang->excel->help->task = "添加任务时，任务名称,任务类型,是必填字段，如果不填导入时会忽略该条数据；\n如需添加多人任务，请在“最初预计”列里面，按照“用户名:{$lang->hourCommon}”格式添加，多个用户之间用换行分隔。用户名在“系统数>据”工作表的G列查看。\n多人任务请填写“任务模式”，非多人任务填写“任务模式”导入时，系统会自动将“任务模式”置空。";
