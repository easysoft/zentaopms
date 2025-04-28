<?php
include dirname(__FILE__) . '/lib/ui.php';
include_once('result.php');
ini_set('memory_limit', -1);
if(empty($link) || empty($link->project))
{
    echo '没有要检查的链接';
    exit(1);
}

$urlList = array();
foreach($link as $module => $LinkList)
{
    if(in_array($module, array('tutorial', 'misc'))) continue;
    foreach($LinkList as $url)
    {
        $params = parse_url($url);
        if(isset($params['query']) && in_array($module, array('company', 'project', 'product', 'execution', 'bug', 'feedback', 'ticket')))
        {
            parse_str($params['query'], $params);
            if($params['f'] == 'browse' && isset($params['dept']) && $params['dept'] != 1) continue;
            if($params['f'] == 'browse' && isset($params['programID']) && $params['programID'] != 1) continue;
            if($params['f'] == 'index' && isset($params['id']) && $params['id'] != 1001) continue;
            if($params['f'] == 'browse' && isset($params['productID']) && $params['productID'] != 1) continue;
            if($params['f'] == 'task' && isset($params['executionID']) && $params['executionID'] != 12000) continue;
            if($params['f'] == 'admin' && isset($params['browseType']) && $params['browseType'] == 'byProduct' && isset($params['param']) && $params['param'] != 1) continue;
            if($params['f'] == 'manageProduct' && isset($params['product']) && $params['product'] != 1) continue;
        }
        $urlList[] = $url;
    }
}
