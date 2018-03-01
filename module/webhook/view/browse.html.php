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
  <table id='webhookList' class='table table-condensed table-hover table-striped table-fixed'>
    <thead>
      <tr>
        <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->webhook->id);?></th>
        <th class='w-60px'><?php common::printOrderLink('type', $orderBy, $vars, $lang->webhook->type);?></th>
        <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->webhook->name);?></th>
        <th><?php common::printOrderLink('url', $orderBy, $vars, $lang->webhook->url);?></th>
        <th class='w-70px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($webhooks as $id => $webhook):?>
      <tr>
        <td class='text-center'><?php echo $id;?></td>
        <td class='text-center'><?php echo zget($lang->webhook->typeList, $webhook->type);?></td>
        <td class='text' title='<?php echo $webhook->name;?>'><?php echo $webhook->name;?></td>
        <td class='text' title='<?php echo $webhook->url;?>'><?php echo $webhook->url;?></td>
        <td class='text-right'>
          <?php 
          common::printIcon('webhook', 'log', "webhookID=$id", '', 'list', 'file-text-o');
          common::printIcon('webhook', 'edit', "webhookID=$id", '', 'list');
          if(common::hasPriv('webhook', 'delete'))
          {
              $deleteURL = $this->createLink('webhook', 'delete', "webhookID=$id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"webhookList\",confirmDelete)", '<i class="icon-remove"></i>', '', "title='{$lang->webhook->delete}' class='btn-icon'");
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan='5'><?php $pager->show();?></td>
      </tr>
    </tfoot>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
