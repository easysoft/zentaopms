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
<div id='featurebar'>
  <ul class='nav'>
    <li id='entry' class='active'><?php common::printLink('entry', 'browse', '', $lang->entry->common);?></li>
    <li id='webhook'><?php common::printLink('webhook', 'browse', '', $lang->entry->webhook);?></li>
  </ul>
  <div class='actions'>
    <div class='btn-group'>
      <div class='btn-group' id='createActionMenu'>
        <?php common::printIcon('entry', 'create', '', '', 'button', '', '', 'btn-primary');?>
      </div>
    </div>
  </div>
</div>
