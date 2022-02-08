<?php if(isset($flow) and $flow->type == 'flow'):?>
<?php
$select    = html::select('navigator', $lang->workflow->navigators, $flow->navigator, "class='form-control'");
$navigator = <<<EOT
    <tr>
      <th class='w-80px'>{$lang->workflow->navigator}</th>
      <td>{$select}</td>
      <td class='w-40px'></td>
    </tr>
EOT;
?>
<?php
js::set('positionModule', $flow->positionModule);
js::set('position', $flow->position);
js::set('app', $flow->app);
?>
<script>
$('#app').parents('tr').before(<?php echo json_encode($navigator);?>).hide();
$('div.required.required-wrapper').remove();

var $inputGroup = $('#app').closest('.input-group');
$inputGroup.find('#name').remove();
$inputGroup.find('#code').remove();
$inputGroup.find('.input-group-addon').remove();

setRequiredFields();

$('#navigator').change(function()
{
    if($(this).val() == 'primary')
    {
        $('#app').parents('tr').hide();
        $('select#positionModule').load(createLink('workflow', 'ajaxGetApps'), function()
        {
            $('#positionModule').val(positionModule);
            $('#position').val(position);
        });
    }
    if($(this).val() == 'secondary')
    {
        $('#app').parents('tr').show();
        $('select#positionModule').load(createLink('workflow', 'ajaxGetAppMenus', 'app=' + $('#app').val() + '&exclude=<?php echo $flow->module;?>'), function()
        {
            $('#app').val(app);
            $('#positionModule').val(positionModule);
            $('#position').val(position);
        });
    }
});
$('#navigator').change();
</script>
<?php endif;?>
