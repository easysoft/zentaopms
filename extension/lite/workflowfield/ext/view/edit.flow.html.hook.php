<?php if($config->visions == ',lite,'):?>
<?php $feedbackDatasources = $this->dao->select('id')->from(TABLE_WORKFLOWDATASOURCE)->where('code')->like('litefeedback%')->andWhere('vision')->eq('lite')->fetchPairs('id', 'id');?>
<?php if($feedbackDatasources):?>
<script>
<?php foreach($feedbackDatasources as $id):?>
$('#optionType option[value=<?php echo $id;?>]').remove();
<?php endforeach;?>
$('#optionType').trigger("chosen:updated");
</script>
<?php endif;?>
<?php endif;?>
