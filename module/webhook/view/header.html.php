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
<div id='featurebar'>
  <ul class='nav'>
    <?php 
    //echo "<li id='entry'>"; common::printLink('entry', 'browse', '', $lang->webhook->entry); echo '</li>';
    echo "<li id='webhook' class='active'>"; common::printLink('webhook', 'browse', '', $lang->webhook->common); echo '</li>';
    ?>
  </ul>
  <div class='actions'>
    <div class='btn-group'>
      <div class='btn-group' id='createActionMenu'>
        <?php common::printIcon('webhook', 'create', '', '', 'button', '', '', 'btn-primary');?>
      </div>
    </div>
  </div>
</div>
