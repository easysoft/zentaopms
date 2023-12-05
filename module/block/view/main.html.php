<?php
/**
 * The main view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.pms
 */
$viewDir      = dirname(__FILE__);
$extFire      = $app->getExtensionRoot() . $config->edition . "/block/ext/view/{$code}block.html.php";
$file2Include = file_exists($extFire) ? $extFire : "{$viewDir}/{$code}block.html.php";
include $file2Include;
?>
