<style>
.preference .table-form > tbody > tr > th {font-weight: 400; color: #0B0F18;}
.preference .chosen-container-single .chosen-single > span {color: #313C52;}
</style>
<div class='preference'>
<form method='post' target='hiddenwin' action='<?php echo $this->createLink('my', 'preference', "showTip=false")?>'>
    <table align='center' class='table table-form w-320px'>
      <tr>
        <th class='w-120px'><?php echo $lang->my->storyConcept;?></th>
        <td><?php echo html::select('URSR', $URSRList, $URSR, "class='form-control chosen'");?></td>
      </tr>
      <?php if($this->config->systemMode == 'ALM'):?>
      <tr>
        <th><?php echo $lang->my->programLink;?></th>
        <td><?php echo html::select('programLink', $lang->my->programLinkList, $programLink, "class='form-control chosen'");?></td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->my->productLink;?></th>
        <td><?php echo html::select('productLink', $lang->my->productLinkList, $productLink, "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->my->projectLink;?></th>
        <td><?php echo html::select('projectLink', $lang->my->projectLinkList, $projectLink, "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->my->executionLink;?></th>
        <td><?php echo html::select('executionLink', $lang->my->executionLinkList, $executionLink, "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <td colspan='2' class='text-center form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
