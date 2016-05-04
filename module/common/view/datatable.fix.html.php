<style>
.datatable-menu-wrapper {position: relative;}
.datatable-menu {position: absolute; right: 0; top: 0; border: 1px solid #ddd; background: #fff; z-index: 999;}
.datatable-menu > .btn {padding: 5px 6px; outline: none; color: #4d90fe!important}
.datatable-menu > .btn:hover {color: #002563!important}
.datatable + .datatable-menu-wrapper .datatable-menu > .btn {padding: 5px 6px 6px;}
</style>
<?php $datatableId = $this->moduleName . ucfirst($this->methodName);?>
<script>
$(function()
{
    var table = $('#wrap > .outer > .main > form > table').first();
    if($(table).length > 0)
    {
        var $dropdown = $("<div class='datatable-menu-wrapper'><div class='dropdown datatable-menu'><button type='button' class='btn btn-link' data-toggle='dropdown'><i class='icon-cogs'></i> <span class='caret'></span></button></div></div>");
        var $dropmenu = $("<ul class='dropdown-menu pull-right'></ul>");
        $dropmenu.append("<li><a href='javascript:;' id='switchToDatatable'><?php echo $lang->datatable->switchToDatatable?></a></li>");
        $dropdown.children('.dropdown').append($dropmenu);
        $(table).before($dropdown);
        <?php if(!empty($setShowModule)):?>
        $('.side .side-body .panel-body .tree').parent().append("<div class='text-right'><a href='javascript:;' data-toggle='showModuleModal'><?php echo $lang->datatable->showModule?></a></div>");
        <?php endif;?>
        $("a[data-toggle='showModuleModal']").click(function(){$('#showModuleModal').modal('show')});
    }

    $('#switchToDatatable').click(function()
    {
        saveDatatableConfig('mode', 'datatable', true)
    });

    $('#setShowModule').click(function()
    {
        saveDatatableConfig('showModule', $('#showModuleModal input[name="showModule"]:checked').val(), true)
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
<div class="modal fade" id="showModuleModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-600px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title"><i class="icon-cog"></i> <?php echo $lang->datatable->showModule?></h4>
      </div>
      <div class="modal-body">
        <form class='form-condensed' method='post' target='hiddenwin' action='<?php echo $this->createLink('datatable', 'ajaxSave')?>'>
          <p>
            <span><?php echo html::radio('showModule', $lang->datatable->showModuleList, isset($config->datatable->$datatableId->showModule) ? $config->datatable->$datatableId->showModule : '');?></span>
            <button type='button' id='setShowModule' class='btn btn-primary'><?php echo $lang->save?></button>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>
