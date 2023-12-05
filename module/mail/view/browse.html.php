<?php
/**
 * The browse view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
          <th class='c-user'>     <?php common::printOrderLink('toList',      $orderBy, $vars, $lang->mail->toList);?></th>
          <th class='c-subject'>  <?php common::printOrderLink('subject',     $orderBy, $vars, $lang->mail->subject);?></th>
          <th class='c-user'>     <?php common::printOrderLink('createdBy',   $orderBy, $vars, $lang->mail->createdBy);?></th>
          <th class='c-full-date'><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->mail->createdDate);?></th>
          <th class='c-full-date'><?php common::printOrderLink('sendTime',    $orderBy, $vars, $lang->mail->sendTime);?></th>
          <th class='c-status'>   <?php common::printOrderLink('status',      $orderBy, $vars, $lang->mail->status);?></th>
          <th>                    <?php echo $lang->mail->failReason;?></th>
          <th class='c-actions'>  <?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($queueList as $queue):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='mailIDList[]'  value='<?php echo $queue->id;?>'/>
              <label></label>
            </div>
            <?php echo $queue->id?>
          </td>
          <td><?php echo zget($users, $queue->toList, $queue->toList)?></td>
          <td class='text-left' title='<?php echo $queue->subject;?>'><?php echo $queue->subject?></td>
          <td><?php echo zget($users, $queue->createdBy)?></td>
          <td><?php echo $queue->createdDate?></td>
          <td><?php echo $queue->sendTime?></td>
          <td><?php echo zget($lang->mail->statusList, $queue->status)?></td>
          <td class='text-left' title="<?php echo strip_tags($queue->failReason);?>"><?php echo $queue->failReason?></td>
          <td class='c-actions'>
            <?php
            if(common::hasPriv('mail', 'delete')) echo html::a(inlink('delete', "id=$queue->id"), $lang->delete, 'hiddenwin');
            if(common::hasPriv('mail', 'resend') and $queue->status == 'fail') echo html::a(inlink('resend', "id=$queue->id"), $lang->mail->resend, 'hiddenwin');
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
        <?php if(common::hasPriv('mail', 'batchDelete')) echo html::submitButton($lang->delete, '', 'btn btn-primary');?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>

