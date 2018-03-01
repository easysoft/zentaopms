<?php
/**
 * The log view file of entry module of ZenTaoPMS.
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
<div id="titlebar">
  <div class="heading">
    <strong><?php echo html::a(inlink('browse'), $lang->entry->common);?></strong>
    <small class="text-muted"> <?php echo $entry->name;?> </small>
    <small class="text-muted"> <?php echo $lang->entry->log;?></small>
  </div>
  <div class='actions'>
    <div class='btn-group'>
      <div class='btn-group' id='createActionMenu'>
        <?php common::printLink('admin', 'log', '', "<i class='icon icon-cog'> </i>" . $lang->entry->setting, '', "class='btn btn-primary'");?>
      </div>
    </div>
  </div>
</div>
<table id='logList' class='table table-condensed table-hover table-striped tablesorter table-fixed'>
  <thead>
    <tr>
      <?php $vars = "id={$entry->id}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->entry->id);?></th>
      <th class='w-160px'><?php common::printOrderLink('date', $orderBy, $vars, $lang->entry->date);?></th>
      <th><?php common::printOrderLink('url', $orderBy, $vars, $lang->entry->url);?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($logs as $id => $log):?>
    <tr>
      <td class='text-center'><?php echo $id;?></td>
      <td><?php echo $log->date;?></td>
      <td class='text' title='<?php echo $log->url;?>'><?php echo $log->url;?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='3'><?php $pager->show();?></td>
    </tr>
  </tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>
