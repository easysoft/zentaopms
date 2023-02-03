<?php
/**
 * The snapshot browse view file of zahost module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      xiawenlong <liyuchun@easycorp.ltd>
 * @package     zahost
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('nodeID', $nodeID);?>
<div id='mainContent' class='main-table'>
  <?php $vars = "nodeID=$nodeID&browseType=all&param=0&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
  <?php if(empty($snapshotList)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->zanode->snapshotEmpty;?></span>
    </p>
  </div>
  <?php else:?>
  <table class='table has-sort-head table-fixed' id='snapshotList'>
    <thead>
      <tr>
        <th class='c-name'><?php common::printOrderLink('name', $orderBy, $vars, $lang->zahost->image->name);?></th>
        <th class='c-status'><?php echo $lang->zahost->status;?></th>
        <th class='c-createdBy'><?php echo $lang->zahost->createdBy;?></th>
        <th class='c-datetime'><?php echo $lang->zahost->createdDate;?></th>
        <th class='c-actions-3'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($snapshotList as $snapshot):?>
      <tr>
        <?php
        $snapshot->status = ($snapshot->status == 'restoring' && time() - strtotime($snapshot->restoreDate) > 600) ? 'restore_failed' : $snapshot->status;

        $editAttr = $snapshot->status == 'failed' ? 'class="btn disabled"' : "title={$lang->zanode->editSnapshot} onclick='window.parent.editSnapshot(\"" . $this->createLink('zanode', 'editSnapshot', "snapshotID={$snapshot->id}") . "\")' class='btn'";

        $restoreAttr  = "title='{$lang->zanode->restoreSnapshot}' target='hiddenwin'";
        $restoreAttr .= ($snapshot->status == 'restoring' or $snapshot->status == 'failed') ? ' class="btn disabled"' :  'class="btn"';

        $deleteAttr  = "title='{$lang->zanode->deleteSnapshot}' target='hiddenwin'";
        $deleteAttr .= (($snapshot->status == 'restoring' and (time() - strtotime($snapshot->restoreDate)) <= 600) or $snapshot->status == 'failed') ? ' class="btn disabled"' :  'class="btn"';
        ?>
        <td title="<?php echo $snapshot->name;?>"><?php echo $snapshot->localName ? $snapshot->localName : $snapshot->name;?></td>
        <td class='<?php echo $snapshot->status;?>'><?php echo zget($lang->zanode->snapshot->statusList, $snapshot->status, '');?></td>
        <td class="c-createdBy"><?php echo zget($users, $snapshot->createdBy, '')?></td>
        <td class='c-datetime'><?php echo $snapshot->createdDate;?></td>
        <td class='c-actions'>
          <?php if(common::hasPriv('zanode', 'editSnapshot')) echo html::a('###', '<i class="icon-edit"></i>', 'hiddenwin', $editAttr);?>
          <?php if(common::hasPriv('zanode', 'restoreSnapshot')) echo html::a($this->createLink('zanode', 'restoreSnapshot', "nodeID={$nodeID}&snapshotID={$snapshot->id}"), '<i class="icon-restart"></i>', 'hiddenwin', $restoreAttr);?>
          <?php if(common::hasPriv('zanode', 'deleteSnapshot')) echo html::a($this->createLink('zanode', 'deleteSnapshot', "snapshotID={$snapshot->id}"), '<i class="icon-trash"></i>', 'hiddenwin', $deleteAttr);?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
