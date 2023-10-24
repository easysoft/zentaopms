<?php
$config->dimension->changeDimensionLink = array();
$config->dimension->changeDimensionLink['screen-browse'] = 'screen|browse|dimensionID=%s';
$config->dimension->changeDimensionLink['pivot-preview'] = 'pivot|preview|dimensionID=%s';
$config->dimension->changeDimensionLink['chart-preview'] = 'chart|preview|dimensionID=%s';

$config->dimension->defaultDimension     = array('macro', 'efficiency', 'quality');

/* Pivot second module list. */
$config->dimension->pivotModuleList = array();
$config->dimension->pivotModuleList['macro'] = array();
$config->dimension->pivotModuleList['macro']['staff']   = 'program';
$config->dimension->pivotModuleList['macro']['product'] = 'product';

$config->dimension->pivotModuleList['efficiency'] = array();
$config->dimension->pivotModuleList['efficiency']['project'] = 'project,progress,cost,timelimit';

$config->dimension->pivotModuleList['quality'] = array();
$config->dimension->pivotModuleList['quality']['test'] = 'bug';

/* Chart second module list. */
$config->dimension->chartModuleList = array();
$config->dimension->chartModuleList['macro'] = array();
$config->dimension->chartModuleList['macro']['staff']   = 'program,project,product,plan,execution,release,story,task,bug,doc,cost,personnel,effort,behavior';
$config->dimension->chartModuleList['macro']['product'] = 'story,release';
$config->dimension->chartModuleList['macro']['test']    = 'bug';
$config->dimension->chartModuleList['macro']['project'] = 'project,task,execution,cost,timelimit,story';

$config->dimension->chartModuleList['efficiency'] = array();
$config->dimension->chartModuleList['efficiency']['staff']   = 'project,execution,release,story,task,bug,cost';
$config->dimension->chartModuleList['efficiency']['project'] = 'progress,cost,timelimit';

$config->dimension->chartModuleList['quality'] = array();
$config->dimension->chartModuleList['quality']['staff'] = 'story,bug,testcase';
$config->dimension->chartModuleList['quality']['test']  = 'bug,testcase';

/* Pivot upgrade group. */
$config->dimension->pivotUpgrade = array();
$config->dimension->pivotUpgrade['macro'] = array();
$config->dimension->pivotUpgrade['macro']['staff'] = array();
$config->dimension->pivotUpgrade['macro']['staff']['program'] = array('chart' => '1041,1073,1074');
$config->dimension->pivotUpgrade['macro']['product'] = array();
$config->dimension->pivotUpgrade['macro']['product']['product'] = array('chart' => '1045,1075,1076');

$config->dimension->pivotUpgrade['efficiency'] = array();
$config->dimension->pivotUpgrade['efficiency']['project'] = array();
$config->dimension->pivotUpgrade['efficiency']['project']['project']   = array('chart' => '10118,10119');
$config->dimension->pivotUpgrade['efficiency']['project']['progress']  = array('chart' => '10116,10117');
$config->dimension->pivotUpgrade['efficiency']['project']['cost']      = array('pivot' => '1001');
$config->dimension->pivotUpgrade['efficiency']['project']['timelimit'] = array('pivot' => '1000');

$config->dimension->pivotUpgrade['quality'] = array();
$config->dimension->pivotUpgrade['quality']['test'] = array();
$config->dimension->pivotUpgrade['quality']['test']['bug'] = array('pivot' => '1002');

/* Chart upgrade group. */
$config->dimension->chartUpgrade['macro'] = array();
$config->dimension->chartUpgrade['macro']['staff'] = array();
$config->dimension->chartUpgrade['macro']['staff']['program']     = array('chart' => '1018,1033,1042,1043,1055,1083');
$config->dimension->chartUpgrade['macro']['staff']['project']     = array('chart' => '1019,1036,1060,1066');
$config->dimension->chartUpgrade['macro']['staff']['product']     = array('chart' => '1020,1035,1056');
$config->dimension->chartUpgrade['macro']['staff']['plan']        = array('chart' => '1021,1037,1059');
$config->dimension->chartUpgrade['macro']['staff']['execution']   = array('chart' => '1022,1038,1061,1067');
$config->dimension->chartUpgrade['macro']['staff']['release']     = array('chart' => '1023,1064,1068');
$config->dimension->chartUpgrade['macro']['staff']['story']       = array('chart' => '1024,1034,1057,1069');
$config->dimension->chartUpgrade['macro']['staff']['task']        = array('chart' => '1025,1040,1062,1071');
$config->dimension->chartUpgrade['macro']['staff']['bug']         = array('chart' => '1026,1039,1058,1070');
$config->dimension->chartUpgrade['macro']['staff']['doc']         = array('chart' => '1027,1063');
$config->dimension->chartUpgrade['macro']['staff']['cost']        = array('chart' => '1028,1029');
$config->dimension->chartUpgrade['macro']['staff']['personnel']   = array('chart' => '1049,1050,1051,1065,1105,1106,1107,1108,1109,1110');
$config->dimension->chartUpgrade['macro']['staff']['effort']      = array('chart' => '1072');
$config->dimension->chartUpgrade['macro']['staff']['behavior']    = array('chart' => '1030');

$config->dimension->chartUpgrade['macro']['product'] = array();
$config->dimension->chartUpgrade['macro']['product']['story']     = array('chart' => '1031,1046,1077,1088,1089,1091,1092,1099,1101,1102');
$config->dimension->chartUpgrade['macro']['product']['release']   = array('chart' => '1082');

$config->dimension->chartUpgrade['macro']['test'] = array();
$config->dimension->chartUpgrade['macro']['test']['bug']          = array('chart' => '1032,1047,1078,1090,1093,1103,1104');

$config->dimension->chartUpgrade['macro']['project'] = array();
$config->dimension->chartUpgrade['macro']['project']['project']   = array('chart' => '1044,1080');
$config->dimension->chartUpgrade['macro']['project']['task']      = array('chart' => '1079');
$config->dimension->chartUpgrade['macro']['project']['execution'] = array('chart' => '1081');
$config->dimension->chartUpgrade['macro']['project']['cost']      = array('chart' => '1085,1086,1097,1087,1098');
$config->dimension->chartUpgrade['macro']['project']['timelimit'] = array('chart' => '1094,1096');
$config->dimension->chartUpgrade['macro']['project']['story']     = array('chart' => '1091,1100');

$config->dimension->chartUpgrade['efficiency'] = array();
$config->dimension->chartUpgrade['efficiency']['staff'] = array();
$config->dimension->chartUpgrade['efficiency']['staff']['project']   = array('chart' => '10000,10001,10002,10101,10103,10105,10107');
$config->dimension->chartUpgrade['efficiency']['staff']['execution'] = array('chart' => '10007,10008,10009,10102,10104,10106,10108');
$config->dimension->chartUpgrade['efficiency']['staff']['release']   = array('chart' => '10005');
$config->dimension->chartUpgrade['efficiency']['staff']['story']     = array('chart' => '10003,10004,10109,10111');
$config->dimension->chartUpgrade['efficiency']['staff']['task']      = array('chart' => '10010,10110');
$config->dimension->chartUpgrade['efficiency']['staff']['bug']       = array('chart' => '10006');
$config->dimension->chartUpgrade['efficiency']['staff']['cost']      = array('chart' => '10011,10012,10013,10112,10113');

$config->dimension->chartUpgrade['efficiency']['project'] = array();
$config->dimension->chartUpgrade['efficiency']['project']['progress']  = array('chart' => '10114,10115');
$config->dimension->chartUpgrade['efficiency']['project']['cost']      = array('chart' => '10022');
$config->dimension->chartUpgrade['efficiency']['project']['timelimit'] = array('chart' => '10014,10015,10016,10017,10020,10021,10018,10019');

$config->dimension->chartUpgrade['quality'] = array();
$config->dimension->chartUpgrade['quality']['staff'] = array();
$config->dimension->chartUpgrade['quality']['staff']['story']    = array('chart' => '10201,10202');
$config->dimension->chartUpgrade['quality']['staff']['bug']      = array('chart' => '10204,10205,10206');
$config->dimension->chartUpgrade['quality']['staff']['testcase'] = array('chart' => '10203');

$config->dimension->chartUpgrade['quality']['test'] = array();
$config->dimension->chartUpgrade['quality']['test']['bug']      = array('chart' => '10209,10210,10217,10211,10219,10212,10218,10214,10220,10213,10216');
$config->dimension->chartUpgrade['quality']['test']['testcase'] = array('chart' => '10207,10208,10215');

$config->dimension->secondModuleList = array();
$config->dimension->secondModuleList['macro']      = array('pivot' => $config->dimension->pivotModuleList['macro'],      'pivotUpgrade' => $config->dimension->pivotUpgrade['macro'],      'chart' => $config->dimension->chartModuleList['macro'],      'chartUpgrade' => $config->dimension->chartUpgrade['macro']);
$config->dimension->secondModuleList['efficiency'] = array('pivot' => $config->dimension->pivotModuleList['efficiency'], 'pivotUpgrade' => $config->dimension->pivotUpgrade['efficiency'], 'chart' => $config->dimension->chartModuleList['efficiency'], 'chartUpgrade' => $config->dimension->chartUpgrade['efficiency']);
$config->dimension->secondModuleList['quality']    = array('pivot' => $config->dimension->pivotModuleList['quality'],    'pivotUpgrade' => $config->dimension->pivotUpgrade['quality'],    'chart' => $config->dimension->chartModuleList['quality'],    'chartUpgrade' => $config->dimension->chartUpgrade['quality']);
