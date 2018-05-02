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
  <div class='btn-toolbar pull-left'>
    <?php common::printLink('entry', 'browse', '', "<span class='text'>{$lang->entry->common}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printIcon('entry', 'create', '', '', 'button', '', '', 'btn-primary');?>
  </div>
</div>
