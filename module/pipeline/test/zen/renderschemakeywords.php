#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';

error_reporting(E_ERROR);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('1-1');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('pipeline');
helper::import($tester->app->getModulePath('', 'pipeline') . 'control.php');
helper::import($tester->app->getModulePath('', 'pipeline') . 'zen.php');


/**

title=测试 pipelineModel::renderSchemaKeywords()
timeout=0
cid=0

- 步骤1：正常输入 @renderschemakeywords.php?m=pipeline&f=ajaxGetRepos,renderschemakeywords.php?m=artifact&f=ajaxGetArtifactLibs
- 步骤2：边界值输入 @renderschemakeywords.php?m=pipeline&f=ajaxGetRepos,renderschemakeywords.php?m=artifact&f=ajaxGetArtifactLibs
- 步骤3：无效输入 @renderschemakeywords.php?m=pipeline&f=ajaxGetRepos,renderschemakeywords.php?m=artifact&f=ajaxGetArtifactLibs
- 步骤4：大值输入 @renderschemakeywords.php?m=pipeline&f=ajaxGetRepos,renderschemakeywords.php?m=artifact&f=ajaxGetArtifactLibs
- 步骤5：业务规则验证 @renderschemakeywords.php?m=pipeline&f=ajaxGetRepos,renderschemakeywords.php?m=artifact&f=ajaxGetArtifactLibs

*/

try { ob_start(); $result = callZenMethod('pipeline', 'renderSchemaKeywords', array((object)array(), '')); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r(implode(',', $result)) && p() && e('renderschemakeywords.php?m=pipeline&f=ajaxGetRepos,renderschemakeywords.php?m=artifact&f=ajaxGetArtifactLibs'); // 步骤1：正常输入
try { ob_start(); $result = callZenMethod('pipeline', 'renderSchemaKeywords', array((object)array(), '')); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r(implode(',', $result)) && p() && e('renderschemakeywords.php?m=pipeline&f=ajaxGetRepos,renderschemakeywords.php?m=artifact&f=ajaxGetArtifactLibs'); // 步骤2：边界值输入
try { ob_start(); $result = callZenMethod('pipeline', 'renderSchemaKeywords', array((object)array(), '')); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r(implode(',', $result)) && p() && e('renderschemakeywords.php?m=pipeline&f=ajaxGetRepos,renderschemakeywords.php?m=artifact&f=ajaxGetArtifactLibs'); // 步骤3：无效输入
try { ob_start(); $result = callZenMethod('pipeline', 'renderSchemaKeywords', array((object)array('id' => 999999), '')); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r(implode(',', $result)) && p() && e('renderschemakeywords.php?m=pipeline&f=ajaxGetRepos,renderschemakeywords.php?m=artifact&f=ajaxGetArtifactLibs'); // 步骤4：大值输入
try { ob_start(); $result = callZenMethod('pipeline', 'renderSchemaKeywords', array((object)array('id' => 1, 'name' => 'test'), 'test')); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r(implode(',', $result)) && p() && e('renderschemakeywords.php?m=pipeline&f=ajaxGetRepos,renderschemakeywords.php?m=artifact&f=ajaxGetArtifactLibs'); // 步骤5：业务规则验证
