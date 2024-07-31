<?php
$config->projectrelease->editor = new stdclass();
$config->projectrelease->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->projectrelease->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');

$config->projectrelease->actions = new stdclass();
$config->projectrelease->actions->view = array();
$config->projectrelease->actions->view['mainActions']   = array('publish', 'play', 'pause');
$config->projectrelease->actions->view['suffixActions'] = array('edit', 'delete');
