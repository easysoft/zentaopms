<?php
$config->cache->keys = [];
$config->cache->keys['user'] = [];
$config->cache->keys['user']['getpairs'] = ['type' => 'array', 'filter' => 'encode'];

$config->cache->keys['bug']['openedby']   = ['type' => 'array'];
$config->cache->keys['bug']['assignedto'] = ['type' => 'array'];
