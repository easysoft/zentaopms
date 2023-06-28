<?php

$sqlite = new Sqlite(); // 连接数据库

golbal $measPaths;

$measGroup = array();
foreach($measPaths as $className => $path)
{
    include_once $path;
    $measObj = new $className();

    /* 获取当前度量项的特征值，本质上是一个md5字符串，是对主表和连接表的字符串进行md5计算。*/
    $hash = $measObj->getHash();

    if(!isset($measGroup[$hash])) $measGroup[$hash] = array('sql' => '', 'measList' => array(), 'fields' => array());
    $measGroup[$hash]['measList'][] = $measObj;
    $measGroup[$hash]['fields'][] = $measObj->getFields();
}

/* 解析生成数据查询sql。*/
foreach($measGroup as $hash => $measInfo)
{
    $uniqueFields = array_unique($measInfo['fields']);
    $firstMeasObj = current($measInfo['measList']);
    $measInfo['sql'] = $firstMeasObj->getSql($uniqueFields);
}

/* 筛选出当前场景下需要收集的度量项。*/
$readyMeasGroup = array();
foreach($measGroup as $measInfo)
{
    $measList = array();
    foreach($measInfo['measList'] => $measObj)
    {
        if(!isReady($measObj->collectConf)) continue;
        $measList[] = $measObj;
    }
    if(!empty($measList)) $readyMeasGroup[] = array('sql' => $measInfo['sql'], 'measList' => $measList, 'fields' => $measInfo['fields']);
}

$measRecord = array();

foreach($readyMeasGroup as $hash => $measInfo)
{
    $sql = $measInfo['sql'];

    /* 读数据库获取数据。*/
    $dataList = $sqlite->query($sql);

    foreach($dataList => $item)
    {
        foreach($measInfo['measList'] as $measObj)
        {
            $measObj->calculate($item);
        }
    }

    foreach($measInfo['measList'] as $measObj)
    {
        $result = $measObj->getResult();
        $measRecord = array_merge($measRecord, formatMeasResult($result));
    }

    // foreach($measInfo['measList'] as $measObj)
    // {
    //     $measObj->calculate(&$dataList);
    //     $result = $measObj->getResult();
    //     $measRecord = array_merge($measRecord, formatMeasResult($result));
    // }
}

foreach($measRecord as $record) $this->dao->insert(TABLE_MEASRECORD)->data($record)->exec();




