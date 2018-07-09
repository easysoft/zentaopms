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
<div id="mainMenu" class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php common::printLink('entry', 'browse', '', "<span class='text'>{$lang->entry->common}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('admin', 'log', '', "<i class='icon icon-cog'></i> " . $lang->entry->setting, '', "class='btn btn-primary'");?>
    <?php echo html::backButton();?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <div class='heading'>
      <h2 title='<?php echo $entry->name;?>'>
        <?php echo $entry->name;?>
        <span class='label label-info'><?php echo $lang->entry->log;?></span>
      </h2>
    </div>
  </div>
  <table id='logList' class='main-table table has-sort-head table-fixed'>
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
  </table>
  <?php if($logs):?>
  <div class='table-footer'>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
