<?php

if(count($argv) < 7)
{
    echo "Usage: php preparebidata.php [host] [port] [db] [user] [pwd] [zentaoRoot]\n";
    exit;
}

function getPDO($argv)
{
    global $pdo;

    $host = $argv[1];
    $port = $argv[2];
    $db   = $argv[3];
    $user = $argv[4];
    $pwd  = $argv[5];

    $dsn = "mysql:host={$host}; port={$port}; dbname={$db}";
    $options = [
        PDO::ATTR_EMULATE_PREPARES => true, // 开启模拟预处理
        PDO::ATTR_PERSISTENT => false
    ];
    $pdo = new PDO($dsn, $user, $pwd, $options);
    $pdo->exec("SET NAMES UTF8");
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

function jsonEncode($object)
{
    if(empty($object)) return null;
    if(is_scalar($object)) return $object;
    return json_encode($object);
}

function prepareFieldAndValueStr($data)
{
    $data = (array)$data;
    $keys = array_keys($data);

    $fieldStr = "`" . implode("`, `", $keys) . "`";

    $values = array();
    foreach($data as $field => $value)
    {
        $values[] = ":$field";
    }

    $valueStr = implode(",", $values);

    return array($fieldStr, $valueStr);
}

function truncate($table)
{
    global $pdo;
    $prepareSql = "TRUNCATE $table";
    $statement = $pdo->prepare($prepareSql);
    $statement->execute();
}

function insert($table, $data)
{
    global $pdo;

    list($fieldStr, $valueStr) = prepareFieldAndValueStr($data);
    $prepareSql = "INSERT INTO `$table` ($fieldStr) VALUES ($valueStr)";
    $statement = $pdo->prepare($prepareSql);

    foreach($data as $field => $value)
    {
        $statement->bindValue(":$field", $value);
    }
    $statement->execute();
}

function createView($viewName, $sql)
{
    global $pdo;

    $prepareSql = "CREATE OR REPLACE VIEW $viewName AS $sql";
    $statement  = $pdo->prepare($prepareSql);
    $statement->execute();
}

global $pdo;
$path = $argv[6];
getPDO($argv);

$config = new stdclass();

$path = "$path/module/bi";

include "$path/config.php";
include "$path/config/charts.php";
include "$path/config/metrics.php";
include "$path/config/pivots.php";
include "$path/config/dataviews.php";

truncate('zt_chart');
truncate('zt_pivot');
truncate('zt_metric');
truncate('zt_screen');
truncate('zt_dataview');
foreach($config->bi->builtin->charts as $chart)
{
    $chart['createdBy']   = 'system';
    $chart['createdDate'] = date('Y-m-d H:i:s');

    if(isset($chart['settings'])) $chart['settings'] = jsonEncode($chart['settings']);
    if(isset($chart['filters']))  $chart['filters']  = jsonEncode($chart['filters']);
    if(isset($chart['fields']))   $chart['fields']   = jsonEncode($chart['fields']);
    if(isset($chart['langs']))    $chart['langs']    = jsonEncode($chart['langs']);

    insert('zt_chart', $chart);
}

foreach($config->bi->builtin->pivots as $pivot)
{
    $pivot['createdBy']   = 'system';
    $pivot['createdDate'] = date('Y-m-d H:i:s');

    $pivot['name']     = jsonEncode($pivot['name']);
    if(isset($pivot['desc']))     $pivot['desc']     = jsonEncode($pivot['desc']);
    if(isset($pivot['settings'])) $pivot['settings'] = jsonEncode($pivot['settings']);
    if(isset($pivot['filters']))  $pivot['filters']  = jsonEncode($pivot['filters']);
    if(isset($pivot['fields']))   $pivot['fields']   = jsonEncode($pivot['fields']);
    if(isset($pivot['langs']))    $pivot['langs']    = jsonEncode($pivot['langs']);
    if(isset($pivot['vars']))     $pivot['vars']     = jsonEncode($pivot['vars']);

    if(!isset($pivot['drills']))  $pivot['drills']   = array();

    foreach($pivot['drills'] as $drill)
    {
        $drill = (object)$drill;
        $drill->condition = jsonEncode($drill->condition);
        $drill->status    = 'published';
        $drill->type      = 'manual';
        $drill->pivot     = $pivot['id'];

        insert('zt_pivotdrill', (array)$drill);
    }
    unset($pivot['drills']);

    $pivotSpec = new stdclass();
    $pivotSpec->version     = $pivot['version'];
    $pivotSpec->pivot       = $pivot['id'];
    $pivotSpec->mode        = 'text';
    $pivotSpec->sql         = $pivot['sql'];
    $pivotSpec->name        = $pivot['name'];

    if(isset($pivot['desc']))     $pivotSpec->desc     = $pivot['desc'];
    if(isset($pivot['settings'])) $pivotSpec->settings = $pivot['settings'];
    if(isset($pivot['filters']))  $pivotSpec->filters  = $pivot['filters'];
    if(isset($pivot['fields']))   $pivotSpec->fields   = $pivot['fields'];
    if(isset($pivot['langs']))    $pivotSpec->langs    = $pivot['langs'];
    if(isset($pivot['vars']))     $pivotSpec->vars     = $pivot['vars'];

    if(!isset($pivot['settings'])) $pivotSpec->settings = null;
    if(!isset($pivot['filters']))  $pivotSpec->filters  = null;
    if(!isset($pivot['fields']))   $pivotSpec->fields   = null;
    if(!isset($pivot['langs']))    $pivotSpec->langs    = null;
    if(!isset($pivot['vars']))     $pivotSpec->vars     = null;

    insert('zt_pivot', $pivot);
    insert('zt_pivotspec', $pivotSpec);
}

foreach($config->bi->builtin->metrics as $metric)
{
    $metric['stage']       = 'released';
    $metric['type']        = 'php';
    $metric['builtin']     = '1';
    $metric['createdBy']   = 'system';
    $metric['createdDate'] = date('Y-m-d H:i:s');

    insert('zt_metric', $metric);
}

foreach($config->bi->builtin->screens as $screenID)
{
    $screenJson = file_get_contents($path . '/json/' . "screen{$screenID}.json");
    $screen = json_decode($screenJson);
    if(isset($screen->scheme)) $screen->scheme = json_encode($screen->scheme, JSON_UNESCAPED_UNICODE);
    $screen = (array)$screen;
    $screen['status']      = 'published';
    $screen['createdBy']   = 'system';
    $screen['createdDate'] = date('Y-m-d H:i:s');

    insert('zt_screen', $screen);
}

foreach($config->bi->builtin->modules->dataviews as $module)
{
    insert('zt_module', $module);
}

foreach($config->bi->builtin->dataviews as $dataview)
{
    $dataview['createdBy']   = 'system';
    $dataview['createdDate'] = date('Y-m-d H:i:s');

    if(isset($dataview['fields'])) $dataview['fields'] = jsonEncode($dataview['fields']);

    insert('zt_dataview', $dataview);
    createView($dataview['view'], $dataview['sql']);
}
