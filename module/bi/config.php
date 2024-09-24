<?php
$config->bi = new stdclass();
$config->bi->pickerHeight = 150;

$config->bi->conditionList = array();
$config->bi->conditionList['=']           = '=';
$config->bi->conditionList['!=']          = '!=';
$config->bi->conditionList['<']           = '<';
$config->bi->conditionList['>']           = '>';
$config->bi->conditionList['>=']          = '≥ ';
$config->bi->conditionList['<=']          = '≤ ';
$config->bi->conditionList['IN']          = 'IN';
$config->bi->conditionList['NOT IN']      = 'NOT IN';
$config->bi->conditionList['IS NOT NULL'] = 'IS NOT NULL';
$config->bi->conditionList['IS NULL']     = 'IS NULL';

$config->bi->builtin = new stdclass();
$config->bi->builtin->modules = new stdclass();
$config->bi->builtin->screens = array(1, 2, 3, 4, 5, 6, 7, 8,1001);

$config->bi->default = new stdclass();
$config->bi->default->styles  = json_decode('{"filterShow":false,"hueRotate":0,"saturate":1,"contrast":1,"brightness":1,"opacity":1,"rotateZ":0,"rotateX":0,"rotateY":0,"skewX":0,"skewY":0,"blendMode":"normal","animations":[]}');
$config->bi->default->status  = json_decode('{"lock":false,"hide":false}');
$config->bi->default->request = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestInterval":null,"requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
$config->bi->default->events  = json_decode('{"baseEvent":{"click":null,"dblclick":null,"mouseenter":null,"mouseleave":null},"advancedEvents":{"vnodeMounted":null,"vnodeBeforeMount":null}}');

// 是否开启 base/dao exec 的记录 zt_duckdbqueue 功能
$config->bi->cdnUrl       = 'https://dl.zentao.net/duckdb/';
$config->bi->duckdbHelp   = 'https://www.zentao.net/book/zentaopms/1313.html';

$config->bi->duckdbBin = array();
$config->bi->duckdbBin['win']   = array('path' => '/bin/duckdb/',   'file' => 'duckdb.exe', 'fileUrl' => $config->bi->cdnUrl . 'win/duckdb.zip');
$config->bi->duckdbBin['linux'] = array('path' => '/opt/zbox/bin/', 'file' => 'duckdb',     'fileUrl' => $config->bi->cdnUrl . 'linux/amd64/duckdb.zip');

$config->bi->duckdbExt = array();
$config->bi->duckdbExt['win']   = array('dm' => 'sync2parquet.exe', 'mysql' => 'mysql_scanner.duckdb_extension');
$config->bi->duckdbExt['linux'] = array('dm' => 'sync2parquet',     'mysql' => 'mysql_scanner.duckdb_extension');

$config->bi->duckdbExtUrl = array();
$config->bi->duckdbExtUrl['win']   = array('dm' => $config->bi->cdnUrl . 'win/sync2parquet.exe',     'mysql' => $config->bi->cdnUrl . 'win/mysql_scanner.duckdb_extension.zip');
$config->bi->duckdbExtUrl['linux'] = array('dm' => $config->bi->cdnUrl . 'linux/amd64/sync2parquet', 'mysql' => $config->bi->cdnUrl . 'linux/amd64/mysql_scanner.duckdb_extension.zip');

$charts = array();
$charts['32'] = array("root" => 1, "name" => "产品", "grade" => 1);
$charts['33'] = array("root" => 1, "name" => "项目", "grade" => 1);
$charts['34'] = array("root" => 1, "name" => "测试", "grade" => 1);
$charts['35'] = array("root" => 1, "name" => "组织", "grade" => 1);
$charts['36'] = array("root" => 1, "name" => "需求", "grade" => 2);
$charts['37'] = array("root" => 1, "name" => "发布", "grade" => 2);
$charts['38'] = array("root" => 1, "name" => "项目", "grade" => 2);
$charts['39'] = array("root" => 1, "name" => "任务", "grade" => 2);
$charts['40'] = array("root" => 1, "name" => "迭代", "grade" => 2);
$charts['41'] = array("root" => 1, "name" => "成本", "grade" => 2);
$charts['42'] = array("root" => 1, "name" => "工期", "grade" => 2);
$charts['43'] = array("root" => 1, "name" => "需求", "grade" => 2);
$charts['44'] = array("root" => 1, "name" => "Bug", "grade" => 2);
$charts['45'] = array("root" => 1, "name" => "项目集", "grade" => 2);
$charts['46'] = array("root" => 1, "name" => "项目", "grade" => 2);
$charts['47'] = array("root" => 1, "name" => "产品", "grade" => 2);
$charts['48'] = array("root" => 1, "name" => "计划", "grade" => 2);
$charts['49'] = array("root" => 1, "name" => "迭代", "grade" => 2);
$charts['50'] = array("root" => 1, "name" => "发布", "grade" => 2);
$charts['51'] = array("root" => 1, "name" => "需求", "grade" => 2);
$charts['52'] = array("root" => 1, "name" => "任务", "grade" => 2);
$charts['53'] = array("root" => 1, "name" => "Bug", "grade" => 2);
$charts['54'] = array("root" => 1, "name" => "文档", "grade" => 2);
$charts['55'] = array("root" => 1, "name" => "成本", "grade" => 2);
$charts['56'] = array("root" => 1, "name" => "人员", "grade" => 2);
$charts['57'] = array("root" => 1, "name" => "工时", "grade" => 2);
$charts['64'] = array("root" => 1, "name" => "行为", "grade" => 2);
$charts['65'] = array("root" => 2, "name" => "产品", "grade" => 1);
$charts['66'] = array("root" => 2, "name" => "项目", "grade" => 1);
$charts['67'] = array("root" => 2, "name" => "测试", "grade" => 1);
$charts['68'] = array("root" => 2, "name" => "组织", "grade" => 1);
$charts['69'] = array("root" => 2, "name" => "进度", "grade" => 2);
$charts['70'] = array("root" => 2, "name" => "成本", "grade" => 2);
$charts['71'] = array("root" => 2, "name" => "工期", "grade" => 2);
$charts['72'] = array("root" => 2, "name" => "项目", "grade" => 2);
$charts['73'] = array("root" => 2, "name" => "迭代", "grade" => 2);
$charts['74'] = array("root" => 2, "name" => "发布", "grade" => 2);
$charts['75'] = array("root" => 2, "name" => "需求", "grade" => 2);
$charts['76'] = array("root" => 2, "name" => "任务", "grade" => 2);
$charts['77'] = array("root" => 2, "name" => "Bug", "grade" => 2);
$charts['86'] = array("root" => 2, "name" => "成本", "grade" => 2);
$charts['87'] = array("root" => 3, "name" => "产品", "grade" => 1);
$charts['88'] = array("root" => 3, "name" => "项目", "grade" => 1);
$charts['89'] = array("root" => 3, "name" => "测试", "grade" => 1);
$charts['90'] = array("root" => 3, "name" => "组织", "grade" => 1);
$charts['91'] = array("root" => 3, "name" => "Bug", "grade" => 2);
$charts['92'] = array("root" => 3, "name" => "用例", "grade" => 2);
$charts['93'] = array("root" => 3, "name" => "需求", "grade" => 2);
$charts['94'] = array("root" => 3, "name" => "Bug", "grade" => 2);
$charts['95'] = array("root" => 3, "name" => "用例", "grade" => 2);

$pivots = array();
$pivots['59']  = array("root" => 1, "name" => "产品", "grade" => 1);
$pivots['60']  = array("root" => 1, "name" => "项目", "grade" => 1);
$pivots['61']  = array("root" => 1, "name" => "测试", "grade" => 1);
$pivots['62']  = array("root" => 1, "name" => "组织", "grade" => 1);
$pivots['63']  = array("root" => 1, "name" => "产品", "grade" => 2);
$pivots['64']  = array("root" => 1, "name" => "项目集", "grade" => 2);
$pivots['79']  = array("root" => 2, "name" => "产品", "grade" => 1);
$pivots['80']  = array("root" => 2, "name" => "项目", "grade" => 1);
$pivots['81']  = array("root" => 2, "name" => "测试", "grade" => 1);
$pivots['82']  = array("root" => 2, "name" => "组织", "grade" => 1);
$pivots['83']  = array("root" => 2, "name" => "项目", "grade" => 2);
$pivots['84']  = array("root" => 2, "name" => "进度", "grade" => 2);
$pivots['85']  = array("root" => 2, "name" => "成本", "grade" => 2);
$pivots['86']  = array("root" => 2, "name" => "工期", "grade" => 2);
$pivots['96']  = array("root" => 3, "name" => "产品", "grade" => 1);
$pivots['97']  = array("root" => 3, "name" => "项目", "grade" => 1);
$pivots['98']  = array("root" => 3, "name" => "测试", "grade" => 1);
$pivots['99']  = array("root" => 3, "name" => "组织", "grade" => 1);
$pivots['100'] = array("root" => 3, "name" => "Bug", "grade" => 2);

$config->bi->builtin->modules->charts = $charts;
$config->bi->builtin->modules->pivots = $pivots;

$config->bi->duckSQLTemp = array();
$config->bi->duckSQLTemp['mysql'] = "LOAD '{EXTENSIONPATH}';ATTACH 'host={HOST} user={USER} password={PASSWORD} port={PORT} database={DATABASE}' as mysqldb(TYPE MYSQL);USE mysqldb;{COPYSQL}";
$config->bi->duckSQLTemp['dm']    = '{EXTENSIONPATH} --driver="{DRIVER}" --db="host={HOST} user={USER} password={PASSWORD} port={PORT} database={DATABASE}" --copy="{COPYSQL}"';

$config->bi->drivers       = array('mysql', 'duckdb');
$config->bi->defaultDriver = 'mysql';

$config->bi->columnTypes = new stdclass();
$config->bi->columnTypes->mysql['TINY']       = 'number';
$config->bi->columnTypes->mysql['SHORT']      = 'number';
$config->bi->columnTypes->mysql['LONG']       = 'number';
$config->bi->columnTypes->mysql['FLOAT']      = 'number';
$config->bi->columnTypes->mysql['DOUBLE']     = 'number';
$config->bi->columnTypes->mysql['TIMESTAMP']  = 'string';
$config->bi->columnTypes->mysql['LONGLONG']   = 'string';
$config->bi->columnTypes->mysql['INT24']      = 'number';
$config->bi->columnTypes->mysql['DATE']       = 'date';
$config->bi->columnTypes->mysql['TIME']       = 'string';
$config->bi->columnTypes->mysql['DATETIME']   = 'date';
$config->bi->columnTypes->mysql['YEAR']       = 'date';
$config->bi->columnTypes->mysql['ENUM']       = 'string';
$config->bi->columnTypes->mysql['SET']        = 'string';
$config->bi->columnTypes->mysql['TINYBLOB']   = 'string';
$config->bi->columnTypes->mysql['MEDIUMBLOB'] = 'string';
$config->bi->columnTypes->mysql['LONG_BLOB']  = 'string';
$config->bi->columnTypes->mysql['BLOB']       = 'string';
$config->bi->columnTypes->mysql['VAR_STRING'] = 'string';
$config->bi->columnTypes->mysql['STRING']     = 'string';
$config->bi->columnTypes->mysql['NULL']       = 'null';
$config->bi->columnTypes->mysql['NEWDATE']    = 'date';
$config->bi->columnTypes->mysql['INTERVAL']   = 'string';
$config->bi->columnTypes->mysql['GEOMETRY']   = 'string';
$config->bi->columnTypes->mysql['NEWDECIMAL'] = 'number';

/* Dameng native_type. */
$config->bi->columnTypes->mysql['int']       = 'number';
$config->bi->columnTypes->mysql['varchar']   = 'string';
$config->bi->columnTypes->mysql['text']      = 'string';
$config->bi->columnTypes->mysql['timestamp'] = 'string';
$config->bi->columnTypes->mysql['date']      = 'date';
$config->bi->columnTypes->mysql['time']      = 'string';
$config->bi->columnTypes->mysql['double']    = 'number';
$config->bi->columnTypes->mysql['number']    = 'number';
$config->bi->columnTypes->mysql['bigint']    = 'number';

/* DuckDB native_type. */
$config->bi->columnTypes->duckdb['BIGINT']    = 'number';
$config->bi->columnTypes->duckdb['INT8']      = 'number';
$config->bi->columnTypes->duckdb['LONG']      = 'number';
$config->bi->columnTypes->duckdb['UBIGINT']   = 'number';
$config->bi->columnTypes->duckdb['HUGEINT']   = 'number';
$config->bi->columnTypes->duckdb['UHUGEINT']  = 'number';
$config->bi->columnTypes->duckdb['INTEGER']   = 'number';
$config->bi->columnTypes->duckdb['UINTEGER']  = 'number';
$config->bi->columnTypes->duckdb['SMALLINT']  = 'number';
$config->bi->columnTypes->duckdb['INT2']      = 'number';
$config->bi->columnTypes->duckdb['SHORT']     = 'number';
$config->bi->columnTypes->duckdb['INT4']      = 'number';
$config->bi->columnTypes->duckdb['INT']       = 'number';
$config->bi->columnTypes->duckdb['SIGNED']    = 'number';
$config->bi->columnTypes->duckdb['USMALLINT'] = 'number';
$config->bi->columnTypes->duckdb['TINYINT']   = 'number';
$config->bi->columnTypes->duckdb['INT1']      = 'number';
$config->bi->columnTypes->duckdb['UTINYINT']  = 'number';
$config->bi->columnTypes->duckdb['REAL']      = 'number';
$config->bi->columnTypes->duckdb['FLOAT4']    = 'number';
$config->bi->columnTypes->duckdb['FLOAT']     = 'number';
$config->bi->columnTypes->duckdb['DOUBLE']    = 'number';
$config->bi->columnTypes->duckdb['FLOAT8']    = 'number';
$config->bi->columnTypes->duckdb['BOOLEAN']   = 'number';
$config->bi->columnTypes->duckdb['BOOL']      = 'number';
$config->bi->columnTypes->duckdb['LOGICAL']   = 'number';
$config->bi->columnTypes->duckdb['BLOB']      = 'string';
$config->bi->columnTypes->duckdb['VARCHAR']   = 'string';
$config->bi->columnTypes->duckdb['CHAR']      = 'string';
$config->bi->columnTypes->duckdb['BPCHAR']    = 'string';
$config->bi->columnTypes->duckdb['TEXT']      = 'string';
$config->bi->columnTypes->duckdb['STRING']    = 'string';
$config->bi->columnTypes->duckdb['DATE']      = 'date';
$config->bi->columnTypes->duckdb['TIMESTAMP'] = 'date';
$config->bi->columnTypes->duckdb['DATETIME']  = 'date';
$config->bi->columnTypes->duckdb['TIME']      = 'date';
$config->bi->columnTypes->duckdb['DECIMAL']   = 'number';
$config->bi->columnTypes->duckdb['NUMERIC']   = 'number';

$config->bi->columnTypes->INTEGER   = 'number';
$config->bi->columnTypes->UINTEGER  = 'number';
$config->bi->columnTypes->UTINYINT  = 'number';
$config->bi->columnTypes->SMALLINT  = 'number';
$config->bi->columnTypes->FLOAT     = 'number';
$config->bi->columnTypes->BOOLEAN   = 'number';
$config->bi->columnTypes->VARCHAR   = 'string';
$config->bi->columnTypes->TIMESTAMP = 'date';
$config->bi->columnTypes->DATE      = 'date';

$config->bi->duckdb = new stdclass();
$config->bi->duckdb->ztvtables = array();
$config->bi->duckdb->ztvtables['dayactions'] = <<<EOT
SELECT * FROM ztv_dayactions
EOT;
$config->bi->duckdb->ztvtables['dayuserlogin'] = <<<EOT
SELECT * FROM ztv_dayuserlogin
EOT;
$config->bi->duckdb->ztvtables['dayeffort'] = <<<EOT
SELECT * FROM ztv_dayeffort
EOT;
$config->bi->duckdb->ztvtables['daystoryopen'] = <<<EOT
SELECT * FROM ztv_daystoryopen
EOT;
$config->bi->duckdb->ztvtables['daystoryclose'] = <<<EOT
SELECT * FROM ztv_daystoryclose
EOT;
$config->bi->duckdb->ztvtables['daytaskopen'] = <<<EOT
SELECT * FROM ztv_daytaskopen
EOT;
$config->bi->duckdb->ztvtables['daytaskfinish'] = <<<EOT
SELECT * FROM ztv_daytaskfinish
EOT;
$config->bi->duckdb->ztvtables['daybugopen'] = <<<EOT
SELECT * FROM ztv_daybugopen
EOT;
$config->bi->duckdb->ztvtables['daybugresolve'] = <<<EOT
SELECT * FROM ztv_daybugresolve
EOT;
$config->bi->duckdb->ztvtables['productstories'] = <<<EOT
SELECT * FROM ztv_productstories
EOT;
$config->bi->duckdb->ztvtables['productbugs'] = <<<EOT
SELECT * FROM ztv_productbugs
EOT;
$config->bi->duckdb->ztvtables['projectsummary'] = <<<EOT
SELECT * FROM ztv_projectsummary
EOT;
$config->bi->duckdb->ztvtables['executionsummary'] = <<<EOT
SELECT * FROM ztv_executionsummary
EOT;
$config->bi->duckdb->ztvtables['projectstories'] = <<<EOT
SELECT * FROM ztv_projectstories
EOT;
$config->bi->duckdb->ztvtables['projectbugs'] = <<<EOT
SELECT * FROM ztv_projectbugs
EOT;
$config->bi->duckdb->ztvtables['projectteams'] = <<<EOT
SELECT * FROM ztv_projectteams
EOT;
