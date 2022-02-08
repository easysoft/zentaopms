<?php if($flow->type == 'flow'):?>
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
<script>
$('#app').parents('tr').before(<?php echo json_encode($navigator);?>);
$('div.required.required-wrapper').remove();
setRequiredFields();

$('#navigator').change(function()
{
    var app      = '<?php echo $flow->app;?>';
    var position = '<?php echo $flow->positionModule;?>';
    var link     = createLink('workflow', 'ajaxGetApps', 'exclude=<?php echo $flow->module;?>');

    if($(this).val() == 'primary') $('#app').parents('tr').hide();
    if($(this).val() == 'secondary')
    {
        $('#app').parents('tr').show();
        $('#app').load(link, function()
        {
            $(this).val(app).trigger('chosen:updated');
        });
        link = createLink('workflow', 'ajaxGetAppMenus', 'app=' + $('#app').val() + '&exclude=' + currentModule);
    }
    $('select#positionModule').load(link, function()
    {
        $(this).val(position);
    });
});
$('#navigator').change();
</script>
<?php endif;?>
