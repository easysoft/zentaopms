<?php
$config->upgrade->form = new stdclass();
$config->upgrade->form->mergetRepo = array();
$config->upgrade->form->mergetRepo['products'] = array('type' => 'array', 'required' => true, 'default' => array(), 'filter' => 'join');
$config->upgrade->form->mergetRepo['repoes']   = array('type' => 'array', 'required' => true, 'default' => array());
