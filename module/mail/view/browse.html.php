<?php
/**
 * The browse view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . 'message/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <div class='heading'>
      <strong><?php echo $lang->mail->browse?></strong>
    </div>
  </div>
  <form class='main-table' method='post' action='<?php echo inlink('batchDelete')?>' target='hiddenwin' id='mailForm' data-ride='table'>
    <table class='table has-sort-head table-fixed'>
      <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <thead>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class='w-80px'>  <?php common::printOrderLink('toList',      $orderBy, $vars, $lang->mail->toList);?></th>
          <th class='w-150px'> <?php common::printOrderLink('subject',     $orderBy, $vars, $lang->mail->subject);?></th>
          <th class='w-80px'>  <?php common::printOrderLink('createdBy',   $orderBy, $vars, $lang->mail->createdBy);?></th>
          <th class='w-150px'> <?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->mail->createdDate);?></th>
          <th class='w-150px'> <?php common::printOrderLink('sendTime',    $orderBy, $vars, $lang->mail->sendTime);?></th>
          <th class='w-80px'>  <?php common::printOrderLink('status',      $orderBy, $vars, $lang->mail->status);?></th>
          <th>                 <?php echo $lang->mail->failReason;?></th>
          <th class='c-actions-2'>  <?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($queueList as $queue):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='mailIDList[]'  value='<?php echo $queue->id;?>'/> 
              <label></label>
              <?php echo $queue->id?>
            </div>
          </td>
          <td><?php echo zget($users, $queue->toList, $queue->toList)?></td>
          <td class='text-left'><?php echo $queue->subject?></td>
          <td><?php echo zget($users, $queue->createdBy)?></td>
          <td><?php echo $queue->createdDate?></td>
          <td><?php echo $queue->sendTime?></td>
          <td><?php echo zget($lang->mail->statusList, $queue->status)?></td>
          <td class='text-left'><?php echo $queue->failReason?></td>
          <td class='c-actions'>
            <?php
            if(common::hasPriv('mail', 'delete')) echo html::a(inlink('delete', "id=$queue->id"), $lang->delete, 'hiddenwin', "class='btn btn-link'");
            if(common::hasPriv('mail', 'resend') and $queue->status == 'fail') echo html::a(inlink('resend', "id=$queue->id"), $lang->mail->resend, 'hiddenwin', "class='btn btn-link'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($queueList):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php if(common::hasPriv('mail', 'batchDelete')) echo html::submitButton($lang->delete);?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>

