<script>
$('#submit').after('<input type="hidden" id="vision" name="vision" value="<?php echo $this->config->vision;?>">');
</script>
<?php include $this->app->getExtensionRoot() . 'biz/workflowdatasource/ext/view/' . basename(__FILE__);?>
