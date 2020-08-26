<?php 
include '../../common/view/header.html.php';
include '../../common/view/kindeditor.html.php';
include '../../common/view/datepicker.html.php';
js::set('holders', $lang->bug->placeholder);
js::set('page', 'create');
js::set('refresh', $lang->refresh);
js::set('flow', $config->global->flow);
?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $issue->id;?></span>
        <?php echo "<span title='$issue->title'>" . $issue->title . '</span>';?>
      </div>
    </div>
    <div class="modal-body" style="min-height: 282px; overflow: auto;">
    <form id='ajaxForm' class="form-ajax" method='post' action="<?php echo inlink('resolve', "issue=$issue->id");?>">
      <table class="table table-form">
        <tr>
          <th><?php echo $lang->issue->resolution;?></th>
          <td>
            <?php echo html::select('issue[resolution]', $lang->issue->resolveMethods, 'resolved', "class='form-control chosen'");?>
          </td>
        </tr>
        <?php include 'createtask.html.php';?>
        <?php include 'createbug.html.php';?>
        <?php include 'createstory.html.php';?>
        <?php include 'createrisk.html.php';?>
        <tr class='resolvedTR'>
          <th><?php echo $lang->issue->resolutionComment;?></th>
          <td colspan='3'><textarea name='issue[resolutionComment]' class='form-control' rows='5' id='resolutionComment'><?php echo $issue->resolutionComment;?></textarea></td>
        </tr>
        <tr>
          <th><?php echo $lang->issue->resolvedBy;?></th>
          <td>
            <?php echo html::select('issue[resolvedBy]', $users, $this->app->user->account, "class='form-control chosen'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->issue->resolvedDate;?></th>
          <td>
             <div class='input-group has-icon-right'>
               <?php echo html::input('issue[resolvedDate]', date('Y-m-d'), "class='form-control form-date'");?>
               <label for="date" class="input-control-icon-right"><i class="icon icon-delay"></i></label>
             </div>
          </td>
        </tr>
        <tr>
          <td></td>
          <td>
            <div class='form-action'><?php echo html::submitButton();?></div>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script>
$().ready(function()
{
    $('.taskTR,.bugTR,.storyTR,.riskTR').hide();
    $('#issueresolution').change(function()
    {
        $('.resolvedTR,.taskTR,.bugTR,.storyTR,.riskTR').hide().find('input[type=text],input[type=radio],input[type=checkbox],select,textarea').prop('disabled', true);
        if($(this).val() == 'resolved') $('.resolvedTR').show().find('input[type=text],input[type=radio],input[type=checkbox],select,textarea').prop('disabled', false);
        if($(this).val() == 'tobug')  $('.bugTR').show().find('input[type=text],input[type=radio],input[type=checkbox],select,textarea').prop('disabled', false);
        if($(this).val() == 'totask') $('.taskTR').show().find('input[type=text],input[type=radio],input[type=checkbox],select,textarea').prop('disabled', false);
        if($(this).val() == 'tostory') $('.storyTR').show().find('input[type=text],input[type=radio],input[type=checkbox],select,textarea').prop('disabled', false);
        if($(this).val() == 'torisk') $('.riskTR').show().find('input[type=text],input[type=radio],input[type=checkbox],select,textarea').prop('disabled', false);
    })
});
</script>
<?php include '../../common/view/footer.html.php';?>
