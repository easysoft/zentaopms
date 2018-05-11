<?php
/**
 * The config file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$config->block = new stdclass();
$config->block->editor = new stdclass();
$config->block->editor->set = array('id' => 'html', 'tools' => 'simple');

$config->statistic = new stdclass();
$config->statistic->storyStages = array('wait', 'planned', 'developing', 'testing', 'released');
