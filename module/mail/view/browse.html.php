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
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'><?php echo $lang->mail->browse?></div>
</div>
<form method='post' action='<?php echo inlink('batchDelete')?>' target='hiddenwin' id='mailForm'>
<div class='panel'>
  <table class='table table-condensed table-bordered active-disabled table-fixed tablesorter table-selectable'>
    <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
    <thead>
      <tr>
        <th class='w-id'>    <?php common::printOrderLink('id',        $orderBy, $vars, $lang->idAB);?></th>
        <th class='w-80px'>  <?php common::printOrderLink('toList',    $orderBy, $vars, $lang->mail->toList);?></th>
        <th class='w-150px'> <?php common::printOrderLink('subject',   $orderBy, $vars, $lang->mail->subjectName);?></th>
        <th class='w-80px'>  <?php common::printOrderLink('addedBy',   $orderBy, $vars, $lang->mail->addedBy);?></th>
        <th class='w-150px'> <?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->mail->addedDate);?></th>
        <th class='w-150px'> <?php common::printOrderLink('sendTime',  $orderBy, $vars, $lang->mail->sendTime);?></th>
        <th class='w-60px'>  <?php common::printOrderLink('status',    $orderBy, $vars, $lang->mail->status);?></th>
        <th>                 <?php echo $lang->mail->failReason;?></th>
        <th class='w-80px'>  <?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody class='text-center'>
      <?php foreach($queueList as $queue):?>
      <tr>
        <td class='cell-id'>
          <input type='checkbox' name='mailIDList[]'  value='<?php echo $queue->id;?>'/> 
          <?php echo $queue->id?>
        </td>
        <td><?php echo zget($users, $queue->toList, $queue->toList)?></td>
        <td class='text-left'><?php echo $queue->subject?></td>
        <td><?php echo zget($users, $queue->addedBy, $queue->addedBy)?></td>
        <td><?php echo $queue->addedDate?></td>
        <td><?php echo $queue->sendTime?></td>
        <td><?php echo zget($lang->mail->statusList, $queue->status, '')?></td>
        <td class='text-left'><?php echo $queue->failReason?></td>
        <td class='text-left'>
          <?php
          if(common::hasPriv('mail', 'delete')) echo html::a(inlink('delete', "id=$queue->id"), $lang->delete, 'hiddenwin');
          if(common::hasPriv('mail', 'resend') and $queue->status == 'fail') echo html::a(inlink('resend', "id=$queue->id"), $lang->mail->resend, 'hiddenwin');
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan='9'>
        <?php
        if(count($queueList))
        {
            echo "<div class='table-actions'>";
            echo html::selectButton();
            if(common::hasPriv('mail', 'batchDelete')) echo html::submitButton($lang->delete);
            echo '</div>';
        }
        $pager->show();
        ?>
        </td>
      </tr>
    </tfoot>
  </table>
</div>
</form>
<script>$(function(){fixedTfootAction('#mailForm')})</script>
<?php include '../../common/view/footer.html.php';?>

