<style>
.datatable-menu-wrapper {position: relative; z-index: 999; overflow: visible;}
#switchToDatatable {position: absolute; top: 0; right: 0; border: 1px solid #ddd; outline: none; color: #4d90fe!important; background: #fff}
#switchToDatatable:hover {color: #002563!important}
</style>
<?php $datatableId = $this->moduleName . $this->methodName;?>
<script>
$(function()
{
    var table = $('#wrap > .outer > .main > form > table').first();
    if($(table).length > 0)
    {
        $(table).before("<div class='datatable-menu-wrapper'><span id='switchToDatatable' title='<?php echo $lang->datatable->switchToDatatable?>' class='btn btn-link'><i class='icon-table'></i></span></div>");
    }

    $('#switchToDatatable').click(function()
    {
        saveDatatableConfig('mode', 'datatable', true)
    });

    function saveDatatableConfig(name, value, reload)
    {
        if('<?php echo $this->app->user->account?>' == 'guest') return;
        datatableId = '<?php echo $datatableId?>';
        if(typeof value === 'object') value = JSON.stringify(value);
        $.ajax(
        {
            type: "POST",
            dataType: 'json',
            data: {target: datatableId, name: name, value: value},
            success:function(){if(reload) window.location.reload();},
            url: '<?php echo $this->createLink('datatable', 'ajaxSave')?>'
        });
    };
});
</script>
