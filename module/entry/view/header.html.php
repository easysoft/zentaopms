<?php
/**
 * The header view file of entry module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     entry
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'><?php common::printAdminSubMenu('dev');?></div>
  <div class='btn-toolbar pull-right'>
    <?php echo html::a($this->createLink('entry', 'create'), "<i class='icon icon-plus'></i> {$lang->entry->create}", '', "class='btn btn-primary'"); ?>
  </div>
</div>
