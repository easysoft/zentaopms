<?php
global $app, $config;
$app->loadLang('story');
$lang->epic = clone $lang->story;

foreach($lang->epic as $key => $value)
{
    if(!is_string($value)) continue;
    if(strpos($value, $lang->SRCommon) !== false) $lang->epic->$key = str_replace($lang->SRCommon, $lang->ERCommon, $value);
}

$lang->epic->common = $lang->ERCommon;

$lang->epic->sourceList = array();
$lang->epic->sourceList['']           = '';
$lang->epic->sourceList['customer']   = '客户';
$lang->epic->sourceList['user']       = '用户';
$lang->epic->sourceList['po']         = $lang->productCommon . '经理';
$lang->epic->sourceList['market']     = '市场';
$lang->epic->sourceList['service']    = '客服';
$lang->epic->sourceList['operation']  = '运营';
$lang->epic->sourceList['support']    = '技术支持';
$lang->epic->sourceList['competitor'] = '竞争对手';
$lang->epic->sourceList['partner']    = '合作伙伴';
$lang->epic->sourceList['dev']        = '开发人员';
$lang->epic->sourceList['tester']     = '测试人员';
$lang->epic->sourceList['bug']        = 'Bug';
$lang->epic->sourceList['forum']      = '论坛';
$lang->epic->sourceList['other']      = '其他';

$lang->epic->priList = array();
$lang->epic->priList[0] = '';
$lang->epic->priList[1] = '1';
$lang->epic->priList[2] = '2';
$lang->epic->priList[3] = '3';
$lang->epic->priList[4] = '4';

$lang->epic->categoryList = array();
$lang->epic->categoryList['feature']     = '功能';
$lang->epic->categoryList['interface']   = '接口';
$lang->epic->categoryList['performance'] = '性能';
$lang->epic->categoryList['safe']        = '安全';
$lang->epic->categoryList['experience']  = '体验';
$lang->epic->categoryList['improve']     = '改进';
$lang->epic->categoryList['other']       = '其他';

$lang->epic->stageList = array();
$lang->epic->stageList[''] = '';
$lang->epic->stageList['wait'] = '未开始';
if($config->edition == 'ipd')
{
    $lang->epic->stageList['inroadmap'] = '已设路标';
    $lang->epic->stageList['incharter'] = 'Charter立项';
}
$lang->epic->stageList['planned']    = '已计划';
$lang->epic->stageList['projected']  = '研发立项';
$lang->epic->stageList['developing'] = '研发中';
$lang->epic->stageList['delivering'] = '交付中';
$lang->epic->stageList['delivered']  = '已交付';
$lang->epic->stageList['closed']     = '已关闭';

$lang->epic->reasonList = array();
$lang->epic->reasonList['']           = '';
$lang->epic->reasonList['done']       = '已完成';
$lang->epic->reasonList['subdivided'] = '已拆分';
$lang->epic->reasonList['duplicate']  = '重复';
$lang->epic->reasonList['postponed']  = '延期';
$lang->epic->reasonList['willnotdo']  = '不做';
$lang->epic->reasonList['cancel']     = '已取消';
$lang->epic->reasonList['bydesign']   = '设计如此';
