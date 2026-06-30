<?php
global $app, $config;
$app->loadLang('story');
$lang->requirement = clone $lang->story;

foreach($lang->requirement as $key => $value)
{
    if(!is_string($value)) continue;
    if(strpos($value, $lang->SRCommon) !== false) $lang->requirement->$key = str_replace($lang->SRCommon, $lang->URCommon, $value);
}

$lang->requirement->common = $lang->URCommon;

$lang->requirement->sourceList = array();
$lang->requirement->sourceList['']           = '';
$lang->requirement->sourceList['customer']   = '客户';
$lang->requirement->sourceList['user']       = '用户';
$lang->requirement->sourceList['po']         = $lang->productCommon . '经理';
$lang->requirement->sourceList['market']     = '市场';
$lang->requirement->sourceList['service']    = '客服';
$lang->requirement->sourceList['operation']  = '运营';
$lang->requirement->sourceList['support']    = '技术支持';
$lang->requirement->sourceList['competitor'] = '竞争对手';
$lang->requirement->sourceList['partner']    = '合作伙伴';
$lang->requirement->sourceList['dev']        = '开发人员';
$lang->requirement->sourceList['tester']     = '测试人员';
$lang->requirement->sourceList['bug']        = 'Bug';
$lang->requirement->sourceList['forum']      = '论坛';
$lang->requirement->sourceList['other']      = '其他';

$lang->requirement->priList = array();
$lang->requirement->priList[0] = '';
$lang->requirement->priList[1] = '1';
$lang->requirement->priList[2] = '2';
$lang->requirement->priList[3] = '3';
$lang->requirement->priList[4] = '4';

$lang->requirement->categoryList = array();
$lang->requirement->categoryList['feature']     = '功能';
$lang->requirement->categoryList['interface']   = '接口';
$lang->requirement->categoryList['performance'] = '性能';
$lang->requirement->categoryList['safe']        = '安全';
$lang->requirement->categoryList['experience']  = '体验';
$lang->requirement->categoryList['improve']     = '改进';
$lang->requirement->categoryList['other']       = '其他';

$lang->requirement->stageList = array();
$lang->requirement->stageList[''] = '';
$lang->requirement->stageList['wait'] = '未开始';
if($config->edition == 'ipd')
{
    $lang->requirement->stageList['inroadmap'] = '已设路标';
    $lang->requirement->stageList['incharter'] = 'Charter立项';
}
$lang->requirement->stageList['planned']    = '已计划';
$lang->requirement->stageList['projected']  = '研发立项';
$lang->requirement->stageList['developing'] = '研发中';
$lang->requirement->stageList['delivering'] = '交付中';
$lang->requirement->stageList['delivered']  = '已交付';
$lang->requirement->stageList['closed']     = '已关闭';

$lang->requirement->reasonList = array();
$lang->requirement->reasonList['']           = '';
$lang->requirement->reasonList['done']       = '已完成';
$lang->requirement->reasonList['subdivided'] = '已拆分';
$lang->requirement->reasonList['duplicate']  = '重复';
$lang->requirement->reasonList['postponed']  = '延期';
$lang->requirement->reasonList['willnotdo']  = '不做';
$lang->requirement->reasonList['cancel']     = '已取消';
$lang->requirement->reasonList['bydesign']   = '设计如此';

$lang->requirement->linkStory = '关联需求';
