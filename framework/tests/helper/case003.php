#!/usr/bin/env php
<?php
/**
 * 测试createLink方法
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      chunsheng.wang <chunsheng@cnezsoft.com>
 * @package     Testing
 * @version     $Id: case003.php 133 2010-09-11 07:22:48Z wwccss $
 * @link        http://www.zentao.net
 * @license     http://opensource.org/licenses/lgpl-3.0.html LGPL
 */
include '../../helper.class.php';

/* 实例化app的mock对象。*/
$app = new mockapp();
$app->setViewType('html');

/* 设置cfg配置，并将其转换为$config对象。*/
$cfg['webRoot'] = '/';
$cfg['requestType'] = 'PATH_INFO';
$cfg['requestFix'] = '/';
$cfg['pathType']   = 'full';
$cfg['moduleVar']  = 'm';
$cfg['methodVar']  = 'f';
$cfg['viewVar']    = 't';
eval(helper::array2Object($cfg, 'config'));

/* PATH_INFO + FULL*/
$vars = array('k1' => 'v1', 'k2' => 'v2');
echo helper::createLink('index') . "\n";               // 只有模块名。
echo helper::createLink('user', 'login') . "\n";       // 增加方法名。
echo helper::createLink('user', 'view', $vars) . "\n"; // 增加参数。
$vars = 'k1=v1&k2=v2';
echo helper::createLink('user', 'view', $vars) . "\n\n"; // 参数改成str形式。

/* PATH_INFO + CLEAN */
$config->pathType = 'clean';
$vars = array('k1' => 'v1', 'k2' => 'v2');
echo helper::createLink('index') . "\n";               // 只有模块名。
echo helper::createLink('user', 'login') . "\n";       // 增加方法名。
echo helper::createLink('user', 'view', $vars) . "\n"; // 增加参数。
$vars = 'k1=v1&k2=v2';
echo helper::createLink('user', 'view', $vars) . "\n\n"; // 参数改成str形式。

/* PATH_INFO + CLEAN + REQUESTFIX */
$config->requestFix = '-';
$vars = array('k1' => 'v1', 'k2' => 'v2');
echo helper::createLink('index') . "\n";               // 只有模块名。
echo helper::createLink('user', 'login') . "\n";       // 增加方法名。
echo helper::createLink('user', 'view', $vars) . "\n"; // 增加参数。
$vars = 'k1=v1&k2=v2';
echo helper::createLink('user', 'view', $vars) . "\n\n"; // 参数改成str形式。

/* GET + CLEAN */
$config->requestType = 'GET';
$vars = array('k1' => 'v1', 'k2' => 'v2');
echo helper::createLink('index') . "\n";               // 只有模块名。
echo helper::createLink('user', 'login') . "\n";       // 增加方法名。
echo helper::createLink('user', 'view', $vars) . "\n"; // 增加参数。
$vars = 'k1=v1&k2=v2';
echo helper::createLink('user', 'view', $vars) . "\n"; // 参数改成str形式。

/**
 * app的mock对象。
 * 
 * @package Testing
 */
class mockapp
{
    private $viewType;
    public function setViewType($viewType)
    {
        $this->viewType = $viewType;
    }
    public function getViewType()
    {
        return $this->viewType;
    }
}
?>
