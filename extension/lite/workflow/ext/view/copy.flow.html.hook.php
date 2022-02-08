<?php if($source->type == 'flow'):?>
<?php
$select    = html::select('navigator', $lang->workflow->navigators, '', "class='form-control'");
$navigator = <<<EOT
    <tr>
      <th class='w-80px'>{$lang->workflow->navigator}</th>
      <td>{$select}</td>
      <td class='w-40px'></td>
    </tr>
EOT;
?>
<script>
$('#app').parents('tr').before(<?php echo json_encode($navigator);?>).hide();
$('div.required.required-wrapper').remove();
setRequiredFields();

$('#navigator').change(function()
{
    if($(this).val() == 'primary')
    {
        $('#app').parents('tr').hide();
        $('select#positionModule').load(createLink('workflow', 'ajaxGetApps'));
    }
    if($(this).val() == 'secondary')
    {
        $('#app').parents('tr').show();
        $('select#positionModule').load(createLink('workflow', 'ajaxGetAppMenus', 'app=' + $('#app').val() + '&exclude=<?php echo $source->module;?>'));
    }
});
</script>
<?php endif;?>
