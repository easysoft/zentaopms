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
          <th class='w-200px'><?php echo $webhook->type == 'dingapi' ? $lang->webhook->dingUserid : $lang->webhook->wechatUserid;?></th>
          <th class='w-100px'><?php echo $lang->actions;?></th>
          <th class='w-100px'><?php echo $webhook->type == 'dingapi' ? $lang->webhook->dingBindStatus : $lang->webhook->wechatBindStatus;?></th>
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
<!--          <td>--><?php //echo html::select("userid[{$user->account}]", $useridPairs, 0, 'class="form-control"')?><!--</td>-->
          <td ><?php echo '<span class="label label-badge label-primary label-outline">' . $useridPairs[$userid] . '</span>'; echo html::input("userid[{$user->account}]", $userid, 'class="form-control hidden"');?></td>
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
        </div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
    <div class="content" id="user-list">
      <?php echo html::select("userid", $useridPairs, 0, 'class="form-control" id="user-select"')?>
      <div class='table-footer'>
          <?php echo html::submitButton($lang->save, '', 'btn btn-primary btn-select');?>
          <?php echo html::submitButton($lang->cancel, '', 'btn btn-close');?>
      </div>
      <script>
        $(".btn-close").click(function(){myModalTrigger.close()});
        $(".btn-select").click(function()
          {
              var inputValue1 = $("#user-select option:selected").val();
              var spanValue = $("#user-select option:selected").text();
              var inputName = $("#save-input").children('input').eq(0).attr("name");
              setInput(inputName, inputValue1, spanValue);
              myModalTrigger.close()
          });
      </script>
    </div>
    <div id="save-input"></div>
  </div>
</div>
<script>
var myModalTrigger = myModalTrigger = new $.zui.ModalTrigger({title:"<?php echo $lang->webhook->bind;?>", custom:$('#user-list').html(), height:"auto"});
$(function () {$("#user-list").html("");});
$(".bind").on("click",function()
{
  var inputName = this.getAttribute("data-value");
  $("#save-input").html();
  $("#save-input").html("<input type='hidden' name='" + inputName + "' value=''>");
  myModalTrigger.show();
});
function setInput(setName, setValue1, spanValue){$("input[name='" + setName + "']").attr("value", setValue1); $("input[name='" + setName + "']").prev().html(spanValue);}
<?php if(common::judgeSuhosinSetting($inputVars)):?>
$(function()
{
    $('.table-footer').before("<div class='alert alert-info'><?php echo  extension_loaded('suhosin') ? trim(sprintf($lang->suhosinInfo, $inputVars)) : trim(sprintf($lang->maxVarsInfo, $inputVars));?></div>")
});
<?php endif;?>
</script>
<?php include '../../common/view/footer.html.php';?>
