<?php
/**
 * The header view file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     webhook
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'><?php common::printAdminSubMenu('message');?></div>
  <div class='btn-toolbar pull-right'>
    <div class='btn-group'>
      <div class='btn-group' id='createActionMenu'>
        <?php if(common::hasPriv('webhook', 'create')) echo html::a($this->createLink('webhook', 'create'), "<i class='icon-plus'></i> {$lang->webhook->create}", '', "class='btn btn-primary'");?>
      </div>
    </div>
  </div>
</div>
