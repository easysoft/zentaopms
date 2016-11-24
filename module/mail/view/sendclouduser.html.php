<?php
/**
 * The sendcloud user view file of mail module of ZenTaoPMS.
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
  <div class='heading'><?php echo $lang->mail->sendcloudUser?></div>
</div>
<div class='row'>
  <div class='col-md-6'>
    <form method='post' target='hiddenwin'>
    <div class='panel'>
      <div class='panel-heading'><strong><?php echo $lang->mail->unsyncUser?></strong></div>
      <table class='table table-condensed table-bordered active-disabled table-fixed tablesorter table-selectable'>
        <thead>
          <tr>
            <th class='w-80px'>  <?php echo $lang->user->account;?></th>
            <th class='w-150px'> <?php echo $lang->user->realname;?></th>
            <th class='w-150px'> <?php echo $lang->user->email;?></th>
          </tr>
        </thead>
        <tbody class='text-left'>
          <?php foreach($users as $key => $user):?>
          <?php if($user->email and isset($members[$user->email])) continue;?>
          <tr>
            <td class='cell-id'>
              <input type='checkbox' name='unsyncList[]'  value='<?php echo $user->account;?>'/> 
              <?php echo $user->account?>
            </td>
            <td><?php echo $user->realname?></td>
            <td><?php echo $user->email?></td>
          </tr>
          <?php unset($users[$key]);?>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='3'>
              <div class='table-actions'>
              <?php
              echo html::selectButton();
              echo html::submitButton($lang->mail->sync);
              echo html::hidden('action', 'sync');
              ?>
              </div>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    </form>
  </div>
  <div class='col-md-6'>
    <form method='post' target='hiddenwin'>
    <div class='panel'>
      <div class='panel-heading'><strong><?php echo $lang->mail->syncedUser?></strong></div>
      <table class='table table-condensed table-bordered active-disabled table-fixed tablesorter table-selectable'>
        <thead>
          <tr>
            <th class='w-80px'>  <?php echo $lang->user->account;?></th>
            <th class='w-150px'> <?php echo $lang->user->realname;?></th>
            <th class='w-150px'> <?php echo $lang->user->email;?></th>
          </tr>
        </thead>
        <tbody class='text-left'>
          <?php foreach($users as $key => $user):?>
          <?php if(empty($user->email) or !isset($members[$user->email])) continue;?>
          <tr>
            <td class='cell-id'>
              <input type='checkbox' name='syncedList[]'  value='<?php echo $user->account;?>'/> 
              <?php echo $user->account?>
            </td>
            <td><?php echo $user->realname?></td>
            <td><?php echo $user->email?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='3'>
              <div class='table-actions'>
              <?php
              echo html::selectButton();
              echo html::submitButton($lang->mail->remove);
              echo html::hidden('action', 'delete');
              ?>
              </div>
             </td>
          </tr>
        </tfoot>
      </table>
    </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>

