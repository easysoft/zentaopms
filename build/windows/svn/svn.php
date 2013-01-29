<?php
$svnClient = substr(__FILE__, 0, strpos(__FILE__, 'zentao')) . 'silksvn\svn.exe';
$config->svn->client = $svnClient;

#$i = 1;
#$config->svn->repos[$i]['path']     = '';
#$config->svn->repos[$i]['username'] = '';
#$config->svn->repos[$i]['password'] = '';
#$i ++;
