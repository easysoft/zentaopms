<?php if($config->edition == 'biz' && $config->vision == 'lite' && trim($config->visions, ',') != 'lite'):?>
<script>
$(function()
{
    $('#globalBarLogo a').eq(1).attr('title', '<?php echo str_replace('biz', $lang->bizName . ' ', $config->version);?>');
    $('#globalBarLogo a .version').html('<?php echo ' ' . str_replace('biz', $lang->bizName . ' ', $config->version);?>');
    $('#globalBarLogo a .upgrade').html('<?php echo $lang->maxName;?>');
})
</script>
<?php elseif($config->edition == 'max' && $config->vision == 'lite' && trim($config->visions, ',') != 'lite'):?>
<script>
$(function()
{
    $('#globalBarLogo a').eq(1).attr('title', '<?php echo str_replace('max', $lang->maxName . ' ', $config->version);?>');
    $('#globalBarLogo a .version').html('<?php echo ' ' . str_replace('max', $lang->maxName . ' ', $config->version);?>');
    $('#globalBarLogo a .upgrade').html('<?php echo $lang->upgrade->common;?>');
})
</script>
<?php endif;?>