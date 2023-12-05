<?php
/**
 * The sendcloud user view file of mail module of ZenTaoPMS.
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
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->mail->sendcloudUser?></span></div>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='col-md-6'>
    <form class='main-table' method='post' target='hiddenwin' data-ride='table'>
      <div class='panel'>
        <div class='panel-heading'><strong><?php echo $lang->mail->unsyncUser?></strong></div>
        <table class='table table-fixed'>
          <thead>
            <tr>
              <th class='w-80px'>
                <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                  <label></label>
                </div>
                <?php echo $lang->user->account;?>
              </th>
              <th class='w-150px'> <?php echo $lang->user->realname;?></th>
              <th class='w-150px'> <?php echo $lang->user->email;?></th>
            </tr>
          </thead>
          <tbody class='text-left'>
            <?php foreach($users as $key => $user):?>
            <?php if($user->email and isset($members[$user->email])) continue;?>
            <tr>
              <td class='c-id'>
                <div class="checkbox-primary">
                  <input type='checkbox' name='unsyncList[]'  value='<?php echo $user->account;?>'/> 
                  <label></label>
                  <?php echo $user->account?>
                </div>
              </td>
              <td><?php echo $user->realname?></td>
              <td><?php echo $user->email?></td>
            </tr>
            <?php unset($users[$key]);?>
            <?php endforeach;?>
          </tbody>
        </table>
        <div class='table-footer'>
          <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
          <div class="table-actions btn-toolbar">
            <?php
            echo html::submitButton($lang->mail->sync);
            echo html::hidden('action', 'sync');
            ?>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class='col-md-6'>
    <form class='main-table' method='post' target='hiddenwin'>
      <div class='panel'>
        <div class='panel-heading'><strong><?php echo $lang->mail->syncedUser?></strong></div>
        <table class='table table-fixed'>
          <thead>
            <tr>
              <th class='w-80px'>
                <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                  <label></label>
                </div>
                <?php echo $lang->user->account;?>
              </th>
              <th class='w-150px'> <?php echo $lang->user->realname;?></th>
              <th class='w-150px'> <?php echo $lang->user->email;?></th>
            </tr>
          </thead>
          <tbody class='text-left'>
            <?php foreach($users as $key => $user):?>
            <?php if(empty($user->email) or !isset($members[$user->email])) continue;?>
            <tr>
              <td class='c-id'>
                <div class="checkbox-primary">
                  <input type='checkbox' name='syncedList[]'  value='<?php echo $user->account;?>'/> 
                  <label></label>
                  <?php echo $user->account?>
                </div>
              </td>
              <td><?php echo $user->realname?></td>
              <td><?php echo $user->email?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <div class='table-footer'>
          <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
          <div class="table-actions btn-toolbar">
            <?php
            echo html::submitButton($lang->mail->remove);
            echo html::hidden('action', 'delete');
            ?>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
