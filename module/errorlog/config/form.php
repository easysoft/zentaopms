<?php
declare(strict_types=1);

$config->errorlog->form = new stdclass();
$config->errorlog->form->setting = array();
$config->errorlog->form->setting['days'] = array('type' => 'int', 'required' => true, 'default' => 30);
