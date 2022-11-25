<?php if($config->visions == ',lite,'):?>
<?php $feedbackDatasources = $this->dao->select('id')->from(TABLE_WORKFLOWDATASOURCE)->where('code')->like('litefeedback%')->andWhere('vision')->eq('lite')->fetchPairs('id', 'id');?>
<?php if($feedbackDatasources):?>
<script>
<?php foreach($feedbackDatasources as $id):?>
$('#fieldOptionType option[value=<?php echo $id;?>]').remove();
<?php endforeach;?>
$('#fieldOptionType').trigger("chosen:updated");
</script>
<?php endif;?>
<?php endif;?>
