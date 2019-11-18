<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-800px'>
    <div class='main-header'>
      <h2><?php echo $lang->webhook->bind?></h2>
    </div>
    <form class='main-form' id='bindForm' target='hiddenwin' method='post' data-ride='table'>
      <table id='bindList' class='table table-fixed table-bordered active-disabled'>
        <thead>
        <tr class='text-center'>
          <th class='text-left'><?php echo $lang->user->account?></th>
          <th class='w-200px text-left'><?php echo $lang->user->realname?></th>
          <th class='w-200px'><?php echo $lang->webhook->dingUserid?></th>
          <th class='w-100px'><?php echo $lang->webhook->dingBindStatus?></th>
        </tr>
        </thead>
        <tbody>
        <?php $inputVars = 0;?>
        <?php foreach($users as $user):?>
        <tr>
          <td><?php echo $user->account;?></td>
          <td><?php echo $user->realname;?></td>
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
          <td><?php echo html::select("userid[{$user->account}]", $useridPairs, $userid, 'class="form-control"')?></td>
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
        </div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>
<script>
<?php if(common::judgeSuhosinSetting($inputVars)):?>
$(function()
{
    $('.table-footer').before("<div class='alert alert-info'><?php echo  extension_loaded('suhosin') ? trim(sprintf($lang->suhosinInfo, $inputVars)) : trim(sprintf($lang->maxVarsInfo, $inputVars));?></div>")
})
<?php endif;?>
</script>
<?php include '../../common/view/footer.html.php';?>
