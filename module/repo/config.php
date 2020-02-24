<?php
$config->program = new stdclass();
$config->program->suffix['c']    = "cpp";
$config->program->suffix['cpp']  = "cpp";
$config->program->suffix['asp']  = "asp";
$config->program->suffix['php']  = "php";
$config->program->suffix['cs']   =  "cs";
$config->program->suffix['sh']   = "bash";
$config->program->suffix['jsp']  = "java";
$config->program->suffix['lua']  = "lua";
$config->program->suffix['sql']  = "sql";
$config->program->suffix['js']   = "javascript";
$config->program->suffix['ini']  = "ini";
$config->program->suffix['conf'] = "apache";
$config->program->suffix['bat']  = "dos";
$config->program->suffix['py']   = "python";
$config->program->suffix['rb']   = "ruby";
$config->program->suffix['as']   = "actionscript";
$config->program->suffix['html'] = "xml";
$config->program->suffix['xml']  = "xml";
$config->program->suffix['htm']  = "xml";
$config->program->suffix['pl']   = "perl";

$config->repo->cacheTime = 10;
$config->repo->syncTime  = 10;
$config->repo->batchNum  = 100;
$config->repo->images    = '|png|gif|jpg|ico|jpeg|bmp|';
$config->repo->binary    = '|pdf|';

$config->repo->editor = new stdclass();
$config->repo->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->repo->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->repo->editor->view   = array('id' => 'commentText', 'tools' => 'simpleTools');
$config->repo->editor->diff   = array('id' => 'commentText', 'tools' => 'simpleTools');

$config->repo->create = new stdclass();
$config->repo->create->requiredFields = 'SCM,name,path,encoding,client';
$config->repo->edit = new stdclass();
$config->repo->edit->requiredFields = 'SCM,name,path,encoding,client';
$config->repo->svn = new stdclass();
$config->repo->svn->requiredFields = 'account,password';

$config->repo->matchComment['module']['story']       = 'Story';
$config->repo->matchComment['module']['task']        = 'Task';
$config->repo->matchComment['module']['bug']         = 'Bug';
$config->repo->matchComment['module']['testtask']    = 'Build';
$config->repo->matchComment['task']['start']         = 'Start';
$config->repo->matchComment['task']['finish']        = 'Finish';
$config->repo->matchComment['task']['cancel']        = 'Cancel';
$config->repo->matchComment['task']['consumed']      = 'Cost';
$config->repo->matchComment['task']['left']          = 'Left';
$config->repo->matchComment['bug']['resolve']        = 'Resolve';
$config->repo->matchComment['bug']['resolvedBuild']  = 'Build';
$config->repo->matchComment['testtask']['start']     = 'Start';
$config->repo->matchComment['id']['mark']            = '#';
$config->repo->matchComment['id']['split']           = ',';
$config->repo->matchComment['mark']['consumed']      = ':';
$config->repo->matchComment['mark']['left']          = ':';
$config->repo->matchComment['mark']['resolvedBuild'] = '#';
