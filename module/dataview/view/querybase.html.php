<?php js::set('lang', $lang->dataview);?>
<?php js::set('queryResult', $lang->dataview->queryResult);?>
<?php js::set('clientLang', $this->app->getClientLang());?>
<?php js::set('currentTheme', $this->app->cookie->theme);?>
<?php js::set('recTotalTip', $lang->dataview->recTotalTip);?>
<?php js::set('recPerPageTip', $lang->dataview->recPerPageTip);?>
<div class='panel'>
  <div class='panel-heading'>
    <div class='btn-active-text'><span class='text ptitle'><?php echo $lang->dataview->sqlQuery;?></span></div>
  </div>
  <form method='post' id='dataform' class="form-ajax">
    <table class='table table-form'>
      <tr>
        <th style="width: 0; padding: 0; margin: 0;"></th>
        <td>
          <?php echo html::textarea('sql', isset($data) ? $data->sql : '', "placeholder='" . $lang->dataview->sqlPlaceholder . "' class='form-control' rows=6");?>
        </td>
      </tr>
      <tr class="error hidden"><th></th><td></td></tr>
      <tr>
        <td></td>
        <td>
          <button type="button" class="btn query btn-primary"><?php echo $lang->dataview->query;?></button>
          <span id='querying' class='hidden query-padding'><?php echo $lang->dataview->querying;?></span>
        </td>
      </tr>
    </table>
  </form>
</div>
<div class="panel table-panel" id='queryTable'>
  <div class="panel-heading titleResult">
    <div class='btn-active-text'>
      <span class='text'><?php echo $lang->dataview->result;?></span>
      <button type="button" class="btn btn-link fieldSettings pull-right"><i class='icon icon-cog-outline'></i> <?php echo $lang->dataview->fieldSettings;?></button>
      <?php if($this->app->rawModule == 'dataview' and common::hasPriv('dataview', 'export')):?>
      <button type="button" id='exportDataview' class="btn btn-link hidden dataview-export pull-right"><i class='icon icon-export'></i> <?php echo $lang->dataview->export;?></button>
      <?php endif;?>
    </div>
  </div>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->dataview->noQueryData;?></span></p>
  </div>
  <div style="overflow-x: auto; padding: 0px 20px;">
    <table class="result table table-condensed table-striped table-bordered table-fixed datatable">
    </table>
  </div>
  <div class='table-footer hide'>
    <ul class="pager">
      <li><div class="pager-label recTotal"></div></li>
      <li>
        <div class="btn-group pager-size-menu dropup">
          <button type="button" class="btn dropdown-toggle recPerPage" data-toggle="dropdown" style="border-radius: 4px;"></button>
          <ul class="dropdown-menu">
            <li><a href="javascript:;" data-size="5">5</a></li>
            <li><a href="javascript:;" data-size="10">10</a></li>
            <li><a href="javascript:;" data-size="15">15</a></li>
            <li><a href="javascript:;" data-size="20">20</a></li>
            <li><a href="javascript:;" data-size="25">25</a></li>
            <li><a href="javascript:;" data-size="30">30</a></li>
            <li><a href="javascript:;" data-size="35">35</a></li>
            <li><a href="javascript:;" data-size="40">40</a></li>
            <li><a href="javascript:;" data-size="45">45</a></li>
            <li><a href="javascript:;" data-size="50">50</a></li>
            <li><a href="javascript:;" data-size="100">100</a></li>
            <li><a href="javascript:;" data-size="200">200</a></li>
            <li><a href="javascript:;" data-size="500">500</a></li>
            <li><a href="javascript:;" data-size="1000">1000</a></li>
            <li><a href="javascript:;" data-size="2000">2000</a></li>
          </ul>
        </div>
      </li>
      <li class='pager-item-left first-page'>
        <a class='pager-item' data-page='1' href='javascript:;'><i class='icon icon-first-page'></i></a>
      </li>
      <li class='pager-item-left left-page'>
        <a class='pager-item' data-page='1' href='javascript:;'><i class='icon icon-angle-left'></i></a>
      </li>
      <li><div class='pager-label page-number'></div></li>
      <li class='pager-item-right right-page'>
        <a class='pager-item' data-page='1' href='javascript:;'><i class='icon icon-angle-right'></i></a>
      </li>
      <li class='pager-item-right last-page'>
        <a class='pager-item' data-page='1' href='javascript:;'><i class='icon icon-last-page'></i></a>
      </li>
    </ul>
  </div>
</div>

<div class="modal fade" id="addModal">
  <div class="modal-dialog" style="width: 1200px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang->common->close;?></span></button>
        <h4 class="modal-title"><?php echo $lang->dataview->fieldSettings;?></h4>
      </div>
      <div class="modal-body">

      </div>
    </div>
  </div>
</div>

<template id="fieldSettingTpl">
  <form id="edit" onsubmit="javascript:saveSettings();return false;">
    <table class="table table-form">
      <thead>
        <tr>
          <th class='text-center' style='width: 128px;'><?php echo $lang->dataview->field;?></th>
          <?php if($this->app->getModuleName() !== 'dataview'):?>
          <th style='width: 128px; padding-right: 0px;'><?php echo $lang->dataview->relatedTable;?></th>
          <th style='width: 128px; padding-left: 0px;'><?php echo $lang->dataview->relatedField;?></th>
          <?php endif;?>
          <th><?php echo $lang->dataview->multilingual;?></th>
        </tr>
      </thead>
      <tbody></tbody>
      <tfoot>
        <tr>
          <td <?php echo $this->app->getModuleName() !== 'dataview' ? "colspan='4'" : "colspan='2'"?> class="text-center">
            <button type="submit" id="submit" class="btn btn-wide btn-primary"><?php echo $lang->dataview->save;?></button>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</template>

<template id="tbodyTpl">
  <tr>
    <td><?php echo html::input("{field}", "{field}", "class='form-control text-center' readonly='readonly'");?></td>
    <?php if($this->app->getModuleName() !== 'dataview'):?>
    <td style='padding-right: 0px;' data-field='{field}'><?php echo html::select('relatedTable[{field}]', array(), '', "class='form-control chosen' onchange='setRelatedField(this)'")?></td>
    <td style='padding-left: 0px;' data-field='{field}'><?php echo html::select('relatedField[{field}]', array(), '', "class='form-control chosen'")?></td>
    <?php endif;?>
    <td>
      <div class='input-group'>
        <?php foreach($config->langs as $key => $name):?>
        <span class="input-group-addon"><?php echo $name?></span>
        <?php echo html::input("langs[{field}][$key]", '', "class='form-control' data-field='{field}' data-lang='$key' onclick='activeField(this)' onblur='removeActive(this)'");?>
        <?php endforeach;?>
      </div>
    </td>
  </tr>
  <script>
  $(document).ready(function()
  {
      $("select[name^=relatedTable]+div.picker, select[name^=relatedField]+div.picker").on('click', function()
      {
          var fieldID = $(this).closest('td').data('field');
          if(currentTheme == 'default')
          {
              $(this).closest('tr').find('#' + fieldID).css('color', '#2e7fff');
          }
          else
          {
              $(this).closest('tr').find('#' + fieldID).addClass('text-primary');
          }
      });

      $("select[name^=relatedTable]+div.picker input, select[name^=relatedField]+div.picker input").on('blur', function()
      {
          var fieldID = $(this).closest('td').data('field');
          if(currentTheme == 'default')
          {
              $(this).closest('tr').find('#' + fieldID).css('color', '#0b0f18');
          }
          else
          {
              $(this).closest('tr').find('#' + fieldID).removeClass('text-primary');
          }
      });
  })
  </script>
</template>
