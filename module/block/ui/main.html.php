<?php
declare(strict_types=1);
/**
* The main view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

$viewDir      = dirname(__FILE__);
$extFire      = $app->getExtensionRoot() . $config->edition . "/block/ext/view/{$code}block.html.php";
$file2Include = file_exists($extFire) ? $extFire : "{$viewDir}/{$code}block.html.php";
include $file2Include;
