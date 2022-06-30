<script>
$('input[name=approval]').parents('tr').hide();
$('#submit').after('<input type="hidden" name="vision" id="vision" value="<?php echo $this->config->vision;?>">');
</script>
