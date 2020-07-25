<?php $datatableId = $this->moduleName . ucfirst($this->methodName);?>
<style>
#setShowModule {margin-left: 30px;}
</style>
<script>
$(function()
{
    <?php if(!empty($setModule)):?>
    $('#sidebar .cell .text-center:last').append("<a href='#showModuleModal' data-toggle='modal' class='btn btn-info btn-wide'><?php echo $lang->datatable->moduleSetting?></a><hr class='space-sm' />");
    <?php endif;?>

    var addSettingButton = function()
    {
        var $btnToolbar = $('#main .table-header .btn-toolbar:first');
        if($btnToolbar.length > 0)
        {
            <?php $mode = isset($config->datatable->$datatableId->mode) ? $config->datatable->$datatableId->mode : 'table';?>
            var $dropdown = $('<div class="dropdown"><button id="tableCustomBtn" type="button" class="btn btn-link" data-toggle="dropdown"><i class="icon-cog"></i></button></div>');
            var $dropmenu = $('<ul class="dropdown-menu pull-right"></ul>');
            $dropmenu.append("<li><a href='<?php echo $this->createLink('datatable', 'ajaxCustom', 'id=' . $this->moduleName . '&method=' . $this->methodName)?>' data-toggle='modal' data-type='ajax'><?php echo $lang->datatable->custom?></a></li>");
            $dropmenu.append("<li><a href='javascript:saveDatatableConfig(\"mode\", \"<?php echo $mode == 'table' ? 'datatable' : 'table';?>\", true);' id='switchToDatatable'><?php echo $mode == 'table' ? $lang->datatable->switchToDatatable : $lang->datatable->switchToTable;?></a></li>");
            $dropdown.append($dropmenu).appendTo($btnToolbar);
        }
    };
    $('#main .main-table').on('tableReload', addSettingButton);
    addSettingButton();

    $('#setShowModule').click(function()
    {
        if('<?php echo $this->app->user->account?>' == 'guest') return;
        datatableId   = '<?php echo $datatableId?>';
        var value     = $('#showModuleModal input[name="showModule"]:checked').val();
        var allModule = $('#showModuleModal input[name="showAllModule"]:checked').val();
        if(typeof allModule === 'undefined') allModule = false;
        $.ajax(
        {
            type: "POST",
            dataType: 'json',
            data:
            {
                target: datatableId,
                name: 'showModule',
                value: value,
                allModule: allModule,
            },
            success:function(){window.location.reload();},
            url: '<?php echo $this->createLink('datatable', 'ajaxSave')?>'
        });
    });

    window.saveDatatableConfig = function(name, value, reload, global)
    {
        if('<?php echo $this->app->user->account?>' == 'guest') return;
        var datatableId = '<?php echo $datatableId;?>';
        if(typeof value === 'object') value = JSON.stringify(value);
        if(typeof global === 'undefined') global = 0;
        $.ajax(
        {
            type: "POST",
            dataType: 'json',
            data: {target: datatableId, name: name, value: value, global: global},
            success:function(e){if(reload) window.location.reload();},
            url: '<?php echo $this->createLink('datatable', 'ajaxSave')?>'
        });
        $.get(createLink('score', 'ajax', "method=switchToDataTable"));
    };
});
</script>

<div class="modal fade" id="showModuleModal" tabindex="-1" role="dialog">
  <div class="modal-dialog w-600px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><i class="icon-cog"></i> <?php echo $lang->datatable->moduleSetting;?></h4>
      </div>
      <div class="modal-body">
        <form class='form-condensed' method='post' target='hiddenwin' action='<?php echo $this->createLink('datatable', 'ajaxSave')?>'>
          <table class='table table-form'>
            <tr>
              <td class='w-150px'><?php echo $lang->datatable->showModule;?></td>
              <td><?php echo html::radio('showModule', $lang->datatable->showModuleList, isset($config->datatable->$datatableId->showModule) ? $config->datatable->$datatableId->showModule : '');?></td>
            </tr>
            <?php if($app->moduleName == 'project' && $app->methodName == 'task'):?>
            <tr>
              <td><?php echo $lang->datatable->showAllModule;?></td>
              <td><?php echo html::radio('showAllModule', $lang->datatable->showAllModuleList, isset($config->project->task->allModule) ? $config->project->task->allModule : 0);?></td>
            </tr>
            <?php endif;?>
            <tr>
              <td colspan='2' class='text-center'><button type='button' id='setShowModule' class='btn btn-primary'><?php echo $lang->save?></button></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
