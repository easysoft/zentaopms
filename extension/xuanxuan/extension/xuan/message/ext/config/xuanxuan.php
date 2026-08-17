<?php
$config->message->available['xuanxuan']['story']      = $config->message->objectTypes['story'];
$config->message->available['xuanxuan']['task']       = $config->message->objectTypes['task'];
$config->message->available['xuanxuan']['bug']        = $config->message->objectTypes['bug'];
$config->message->available['xuanxuan']['todo']       = $config->message->objectTypes['todo'];
$config->message->available['xuanxuan']['kanbancard'] = $config->message->objectTypes['kanbancard'];

if(helper::hasFeature('devops'))
{
    $config->message->objectTypes['mr']           = array('compilepass', 'compilefail');
    $config->message->available['xuanxuan']['mr'] = $config->message->objectTypes['mr'];
}

/* 未单独配置喧喧通知的对象，补充 @通知。 */
foreach($config->message->objectTypes as $objectType => $actions)
{
    if(isset($config->message->available['xuanxuan'][$objectType])) continue;
    if(in_array('mentioned', $actions)) $config->message->available['xuanxuan'][$objectType] = array('mentioned');
}

$config->message->setting['xuanxuan']['setting'] = $config->message->available['xuanxuan'];
