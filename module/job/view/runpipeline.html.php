<?php include '../../common/view/header.html.php';?>
    <form id='childrenForm' method='post' target='hiddenwin'>
      <table class='table table-form table-auto'>
        <tr>
          <td><?php echo $pipelineTips;?></td>
        </tr>
        <tr>
          <td class='required'><?php echo html::select("references", $refList, '', "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <td><?php echo $lang->job->pipeline->variables; ?></td>
        </tr>
        <tr id='insertItemBox' class='row-module'>
          <td><?php echo html::input("keys[]", '', "class='form-control' placeholder='{$lang->job->pipeline->variablesKeyPlaceHolder}'");?></td>
          <td><?php echo html::input("values[]", '', "class='form-control' placeholder='{$lang->job->pipeline->variablesValuePlaceHolder}'");?></td>
          <td><button type="button" class="btn btn-link btn-icon btn-add" onclick="addVariable(this)"><i class="icon icon-plus"></i></button></td>
          <td><button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteVariable(this)"><i class="icon icon-close"></i></button></td>
        </tr>
        <tr>
          <td><?php echo $lang->job->pipeline->variablesTips; ?></td>
        </tr>
        <tr>
          <td>
            <?php
            echo html::submitButton($lang->job->runPipeline);
           ?>
          </td>
          <td></td>
        </tr>
      </table>
    </form>
<?php include '../../common/view/footer.html.php';?>
