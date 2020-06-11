<?php
$config->program = new stdclass();
$config->program->editor = new stdclass();
$config->program->editor->create   = array('id' => 'desc',    'tools' => 'simpleTools');
$config->program->editor->edit     = array('id' => 'desc',    'tools' => 'simpleTools');
$config->program->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->start    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->finish   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->suspend  = array('id' => 'comment', 'tools' => 'simpleTools');

$config->program->list = new stdclass();
$config->program->list->exportFields = 'id,name,code,type,category,status,begin,end,budget,PM,end,desc';

$config->program->priv = new stdclass();
$config->program->priv->scrum = array('program', 'product', 'story', 'productplan', 'release', 'project', 'task', 'build', 'qa', 'bug', 'testcase', 'testsuite', 'testreport', 'caselib', 'doc', 'report', 'repo', 'svn', 'git', 'search', 'tree', 'file', 'jenkins', 'job', 'ci', 'branch');
$config->program->priv->cmmi  = $config->program->priv->scrum + array('workestimation', 'durationestimation', 'budget', 'programplan', 'review', 'reviewissue', 'weekly', 'milestone', 'design', 'issue', 'risk', 'auditplan', 'nc', 'cm', 'pssp');
