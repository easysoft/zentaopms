<?php
$config->project->form = new stdclass();
$config->project->form->start   = array();
$config->project->form->suspend = array();

$config->project->form->start['realBegan'] = array('type' => 'date', 'required' => true, 'filter' => 'trim');
