<?php
/**
 * The main view file of block module of ZentaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
$viewDir      = dirname(__FILE__);
$file2Include = file_exists(dirname($viewDir) . "/ext/view/{$code}block.html.php") ? dirname($viewDir) . "/ext/view/{$code}block.html.php" : "{$viewDir}/{$code}block.html.php";
include $file2Include;
?>
