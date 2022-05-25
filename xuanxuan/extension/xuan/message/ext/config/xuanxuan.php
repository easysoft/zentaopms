<?php
$config->message->objectTypes['mr'] = array('compilepass', 'compilefail');

$config->message->available['xuanxuan']['story']      = $config->message->objectTypes['story'];
$config->message->available['xuanxuan']['task']       = $config->message->objectTypes['task'];
$config->message->available['xuanxuan']['bug']        = $config->message->objectTypes['bug'];
$config->message->available['xuanxuan']['todo']       = $config->message->objectTypes['todo'];
$config->message->available['xuanxuan']['mr']         = $config->message->objectTypes['mr'];
$config->message->available['xuanxuan']['kanbancard'] = $config->message->objectTypes['kanbancard'];

$config->message->setting['xuanxuan']['setting'] = $config->message->available['xuanxuan'];
