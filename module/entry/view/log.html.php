<?php
/**
 * The log view file of entry module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     entry
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <div class='heading'>
      <h2 title='<?php echo $entry->name;?>'>
        <?php echo $entry->name;?>
        <span class='label label-info'><?php echo $lang->entry->log;?></span>
        <div class="btn-toolbar pull-right">
          <?php common::printLink('admin', 'log', '', "<i class='icon icon-cog'></i> " . $lang->entry->setting, '', "class='btn btn-primary iframe'", '', true);?>
          <?php echo html::backButton('', '' , 'btn');?>
        </div>
      </h2>
    </div>
  </div>
  <table id='logList' class='main-table table has-sort-head table-fixed'>
    <thead>
      <tr>
        <?php $vars = "id={$entry->id}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->entry->id);?></th>
        <th class='c-date'><?php common::printOrderLink('date', $orderBy, $vars, $lang->entry->date);?></th>
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
