<?php
/**
 * The log view file of log module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     log 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<div id="titlebar">
  <div class="heading">
    <strong><?php echo html::a(inlink('browse'), $lang->webhook->common);?></strong>
    <small class="text-muted"> <?php echo $webhook->name;?></small>
    <small class="text-muted"> <?php echo $lang->webhook->log;?></small>
  </div>
  <div class='actions'>
    <div class='btn-group'>
      <div class='btn-group' id='createActionMenu'>
        <?php common::printLink('admin', 'log', '', "<i class='icon icon-cog'> </i>" . $lang->webhook->setting, '', "class='btn btn-primary'");?>
      </div>
    </div>
  </div>
</div>
<table id='logList' class='table table-condensed table-hover table-striped tablesorter table-fixed'>
  <thead>
    <tr>
      <?php $vars = "id={$webhook->id}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->webhook->id);?></th>
      <th class='w-160px'><?php common::printOrderLink('date', $orderBy, $vars, $lang->webhook->date);?></th>
      <th><?php common::printOrderLink('url', $orderBy, $vars, $lang->webhook->url);?></th>
      <th class='w-300px'><?php common::printOrderLink('action', $orderBy, $vars, $lang->webhook->action);?></th>
      <th class='w-200px'><?php common::printOrderLink('contentType', $orderBy, $vars, $lang->webhook->contentType);?></th>
      <th class='w-200px'><?php  common::printOrderLink('result', $orderBy, $vars, $lang->webhook->result);?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($logs as $id => $log):?>
    <tr>
      <td class='text-center'><?php echo $id;?></td>
      <td><?php echo $log->date;?></td>
      <td class='text' title='<?php echo $log->url;?>'><?php echo $log->url;?></td>
      <td class='text' title='<?php echo $log->action;?>'><?php echo html::a($log->actionURL, $log->action);?></td>
      <td class='text-center'><?php echo $log->contentType;?></td>
      <td title='<?php echo $log->result;?>'><?php echo $log->result;?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='6'><?php $pager->show();?></td>
    </tr>
  </tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>
