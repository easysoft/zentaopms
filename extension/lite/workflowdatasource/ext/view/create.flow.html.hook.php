<script>
$('#submit').after('<input type="hidden" id="vision" name="vision" value="<?php echo $this->config->vision;?>">');
$("#name").parent().addClass('required');
$("#datasource").addClass('required');
</script>
