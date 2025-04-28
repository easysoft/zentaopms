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
