<form id='childrenForm' class='form-ajax' action="<?php echo inlink('exec', "jobID={$job->id}");?>" method='post'>
  <table class='table table-form table-auto'>
    <tr>
      <td><?php echo $pipelineTips;?></td>
    </tr>
    <tr>
      <td class='required'><?php echo html::select("ref", $refList, '', "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <td><?php echo $lang->job->pipelineVariables; ?></td>
    </tr>
    <tr id='insertItemBox' class='row-module'>
      <td><?php echo html::input("keys[]", '', "class='form-control' placeholder='{$lang->job->pipelineVariablesKeyPlaceHolder}'");?></td>
      <td><?php echo html::input("values[]", '', "class='form-control' placeholder='{$lang->job->pipelineVariablesValuePlaceHolder}'");?></td>
      <td><button type="button" class="btn btn-link btn-icon btn-add" onclick="addVariable(this)"><i class="icon icon-plus"></i></button></td>
      <td><button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteVariable(this)"><i class="icon icon-close"></i></button></td>
    </tr>
    <tr>
      <td><?php echo $lang->job->pipelineVariablesTips; ?></td>
    </tr>
    <tr>
      <td>
        <?php
        echo html::submitButton($lang->job->runPipeline, '', empty($refList) ? 'disabled btn btn-primary' : 'btn btn-primary');
       ?>
      </td>
      <td></td>
    </tr>
  </table>
</form>
<script>
$('#childrenForm.form-ajax').ajaxForm();
</script>
