<?php
/**
 * The config file of block module for the GitFox edition.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang LI <liyang@easycorp.ltd>
 * @package     block
 * @link        https://www.zentao.net
 */

/* 待我评审区块的默认展示数量，可在编辑区块中调整。 */
$config->block->params['reviewbyme'] = new stdclass();
$config->block->params['reviewbyme']->count['name']    = $lang->block->count;
$config->block->params['reviewbyme']->count['default'] = 20;
$config->block->params['reviewbyme']->count['control'] = 'input';

/* 待我评审区块默认为长区块，也可以调整为短区块。 */
$config->block->size['ppm']['reviewbyme'] = array(2 => 6, 1 => 6);

/* 我创建的评审区块的默认展示数量，可在编辑区块中调整。 */
$config->block->params['reviewcreatedbyme'] = new stdclass();
$config->block->params['reviewcreatedbyme']->count['name']    = $lang->block->count;
$config->block->params['reviewcreatedbyme']->count['default'] = 20;
$config->block->params['reviewcreatedbyme']->count['control'] = 'input';

/* 我创建的评审区块默认为长区块，也可以调整为短区块。 */
$config->block->size['ppm']['reviewcreatedbyme'] = array(2 => 6, 1 => 6);

$config->block->modules['ppm'] = new stdclass();
$config->block->modules['ppm']->moreLinkList = new stdclass();
$config->block->modules['ppm']->moreLinkList->reviewbyme        = 'my|audit|browseType=ppm';
$config->block->modules['ppm']->moreLinkList->reviewcreatedbyme = 'ppm|browse|repoID=0&mode=status&param=creator';

$config->block->size['teamachievement']['teamachievement'] = array(1 => 4);
