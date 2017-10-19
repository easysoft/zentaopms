<?php
/**
 * The browse view file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     webhook 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<?php js::set('confirmDelete', $lang->webhook->confirmDelete);?>
<form id='ajaxForm' method='post'>
  <table id='webhookList' class='table table-condensed table-hover table-striped tablesorter'>
    <thead>
      <tr>
        <?php $vars = "orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";?>
        <th class='w-80px'><?php  common::printOrderLink('id', $orderBy, $vars, $lang->webhook->id);?></th>
        <th class='w-200px'><?php common::printOrderLink('name', $orderBy, $vars, $lang->webhook->name);?></th>
        <th class='w-200px'><?php common::printOrderLink('url', $orderBy, $vars, $lang->webhook->url);?></th>
        <th class='w-100px'><?php common::printOrderLink('requestType', $orderBy, $vars, $lang->webhook->requestType);?></th>
        <th class='w-200px'><?php common::printOrderLink('params', $orderBy, $vars, $lang->webhook->params);?></th>
        <th><?php echo common::printOrderLink('desc', $orderBy, $vars, $lang->webhook->desc);?></th>
        <th class='w-100px'><?php echo html::a('###', $lang->actions);?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($entries as $webhook):?>
      <tr>
        <td class='text-center'><?php echo $webhook->id;?></td>
        <td title='<?php echo $webhook->name;?>'><?php echo $webhook->name;?></td>
        <td><?php echo $webhook->url;?></td>
        <td title='<?php echo $webhook->requestType;?>'><?php echo $webhook->requestType;?></td>
        <td title='<?php echo $webhook->params;?>'><?php echo $webhook->params;?></td>
        <td title='<?php echo $webhook->desc;?>'><?php echo $webhook->desc;?></td>
        <td class='text-right'>
          <?php 
          common::printIcon('webhook', 'log', "webhookID=$webhook->id", '', 'list', 'file-text-o');
          common::printIcon('webhook', 'action', "webhookID=$webhook->id", '', 'list', 'cog');
          common::printIcon('webhook', 'edit', "webhookID=$webhook->id", '', 'list');
          if(common::hasPriv('webhook', 'delete'))
          {
              $deleteURL = $this->createLink('webhook', 'delete', "webhookID=$webhook->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"webhookList\",confirmDelete)", '<i class="icon-remove"></i>', '', "title='{$lang->webhook->delete}' class='btn-icon'");
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan='7'><?php $pager->show();?></td>
      </tr>
    </tfoot>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
