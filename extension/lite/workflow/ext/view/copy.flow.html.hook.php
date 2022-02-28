<script>
$('#submit').after('<input type="hidden" name="vision" id="vision" value="<?php echo $this->config->vision;?>">');
</script>
<?php include $this->app->getExtensionRoot() . 'biz/workflow/ext/view/' . basename(__FILE__);?>
