<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-800px'>
    <div class='main-header'>
      <h2><?php echo $lang->webhook->bind?></h2>
    </div>
    <form class='main-form' id='bindForm' target='hiddenwin' method='post' data-ride='table'>
      <table id='bindList' class='table table-fixed table-bordered active-disabled table-hover'>
        <thead>
        <tr class='text-center'>
          <th class='text-left' colspan="2"><?php echo $lang->webhook->zentaoUser?></th>
          <th class='text-left' colspan="2"><?php echo $webhook->type == 'dinguser' ? $lang->webhook->dingUserid : $lang->webhook->wechatUserid;?></th>
          <th class='w-100px'><?php echo $lang->actions;?></th>
          <th class='w-100px'><?php echo $webhook->type == 'dinguser' ? $lang->webhook->dingBindStatus : $lang->webhook->wechatBindStatus;?></th>
        </tr>
        </thead>
        <tbody>
        <?php $inputVars = 0;?>
        <?php foreach($users as $user):?>
        <tr>
          <td colspan="2"><?php echo $user->account;?> <span class="label label-badge label-info label-outline"><?php echo $user->realname;?></span></td>
          <?php
          $userid     = '';
          $bindStatus = 0;
          if(isset($bindedUsers[$user->account]))
          {
              $userid     = $bindedUsers[$user->account];
              $bindStatus = 1;
          }
          elseif(isset($dingUsers[$user->realname]))
          {
              $userid = $dingUsers[$user->realname];
          }
          ?>
          <td colspan="2">
            <?php echo '<span class="label label-badge label-primary label-outline">' . zget($useridPairs, $userid) . '</span>';?>
            <?php echo html::input("userid[{$user->account}]", $userid, 'class="form-control hidden"');?>
          </td>
          <td class='text-center c-actions'><?php echo '<button class="btn bind" type="button" data-value="userid[' . $user->account . ']"><i class="icon-common-edit icon-edit"></i></button>';?></td>
          <td class='text-center'><?php echo zget($lang->webhook->dingBindStatusList, $bindStatus, '');?></td>
        </tr>
        <?php $inputVars += 1;?>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php if($users):?>
      <div class='table-footer'>
        <div class='text'>
          <?php echo html::submitButton($lang->save, '', 'btn btn-primary');?>
          <?php echo html::a($this->createLink('webhook', 'browse'), $lang->goback, '', "class='btn'");?>
          <?php if($selectedDepts) echo html::a($this->createLink('webhook', 'chooseDept', "id={$webhook->id}"), $lang->webhook->chooseDeptAgain, '', "class='btn'");?>
        </div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
    <div class="text-hide" id="triggerTitle"><?php echo $lang->webhook->bind;?></div>
    <div class="content" id="userList">
      <?php echo html::select("userid", $useridPairs, 0, 'class="form-control" id="userSelect"');?>
      <div class='table-footer'><?php echo html::commonButton($lang->save, 'onclick = "confirmChanges();"', 'btn btn-primary');?></div>
      <script>
          $("#userSelect").chosen();
          $('.chosen-container').eq(1).remove();
      </script>
    </div>
    <div id="saveInput"></div>
</div>
<script>
<?php if(common::judgeSuhosinSetting($inputVars)):?>
$(function()
{
    $('.table-footer').before("<div class='alert alert-info'><?php echo  extension_loaded('suhosin') ? trim(sprintf($lang->suhosinInfo, $inputVars)) : trim(sprintf($lang->maxVarsInfo, $inputVars));?></div>")
});
<?php endif;?>
</script>
<?php include '../../common/view/footer.html.php';?>
