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

$config->block->gridOptions[6]  = '1/2';
$config->block->gridOptions[4]  = '1/3';
$config->block->gridOptions[8]  = '2/3';
$config->block->gridOptions[3]  = '1/4';
$config->block->gridOptions[9]  = '3/4';
$config->block->gridOptions[12] = '100%';

$config->filterParam->get['block']['common']['hash']['reg']     = '/^[a-z0-9]{32}$/';
$config->filterParam->get['block']['main']['entry']['reg']      = '/^[a-zA-Z0-9_]+$/';
$config->filterParam->get['block']['main']['lang']['reg']       = '/^[a-zA-Z_\-]+$/';
$config->filterParam->get['block']['main']['mode']['reg']       = '/^[a-zA-Z0-9_]+$/';
$config->filterParam->get['block']['main']['blockid']['reg']    = '/^[a-zA-Z0-9]+$/';
$config->filterParam->get['block']['main']['blockTitle']['reg'] = '/./';
$config->filterParam->get['block']['main']['param']['reg']      = '/^[a-zA-Z0-9\+\/\=]+$/';
$config->filterParam->get['block']['main']['sso']['reg']        = '/^[a-zA-Z0-9\+\/\=]+$/';
