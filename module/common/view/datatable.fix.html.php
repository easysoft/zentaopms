<?php $datatableId = $this->moduleName . ucfirst($this->methodName);?>
<script>
$(function()
{
    var $btnToolbar = $('#main #mainContent form.main-table .table-header .btn-toolbar:first');
    if($btnToolbar.length > 0)
    {
        <?php $mode = isset($this->config->datatable->$datatableId->mode) ? $this->config->datatable->$datatableId->mode : 'table';?>
        $btnToolbar.append("<a href=\"javascript:saveDatatableConfig('mode', 'table', true);\" class='btn btn-link <?php echo $mode == 'table' ? 'btn-active-line' : '';?>'><?php echo $lang->datatable->table?></a>");
        $btnToolbar.append("<a href=\"javascript:saveDatatableConfig('mode', 'datatable', true);\" class='btn btn-link <?php echo $mode == 'datatable' ? 'btn-active-line' : '';?>'><?php echo $lang->datatable->datatable?></a>");
        $btnToolbar.append("<a id='tableCustomBtn' class='btn btn-link' href='<?php echo $this->createLink('datatable', 'ajaxCustom', 'id=' . $this->moduleName . '&method=' . $this->methodName)?>' data-toggle='modal' data-type='ajax'><i class='icon icon-cog'></i></a>");
        <?php if(!empty($setShowModule)):?>
        $('#sidebar .cell .text-center').append("<a href='javascript:;' data-toggle='showModuleModal' class='text-secondary small'><?php echo $lang->datatable->showModule?></a><hr class='space-sm' />");
        <?php endif;?>
        $('#tableCustomBtn').modalTrigger();
        $("a[data-toggle='showModuleModal']").click(function(){$('#showModuleModal').modal('show')});
    }

    $('#setShowModule').click(function()
    {
        saveDatatableConfig('showModule', $('#showModuleModal input[name="showModule"]:checked').val(), true)
    });

    function saveDatatableConfig(name, value, reload, global)
    {
        if('<?php echo $this->app->user->account?>' == 'guest') return;
        datatableId = '<?php echo $datatableId?>';
        if(typeof value === 'object') value = JSON.stringify(value);
        if(typeof global === 'undefined') global = 0;
        $.ajax(
        {
            type: "POST",
            dataType: 'json',
            data: {target: datatableId, name: name, value: value, global: global},
            success:function(){if(reload) window.location.reload();},
            url: '<?php echo $this->createLink('datatable', 'ajaxSave')?>'
        });
        $.get(createLink('score', 'ajax', "method=switchToDataTable"));
    };
    window.saveDatatableConfig = saveDatatableConfig;
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
