<div id='setParams' class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title"><i class="icon-file-text"></i> <?php echo $lang->measurement->setParams?></h4>
      </div>
      <div class="modal-body">
        <form id='paramsForm' method='post' action='<?php echo inlink('ajaxSetParams', "measurementID=$measurementID")?>' class='form-ajax'>
          <table class='table active-disabled'>
            <thead>
              <tr class='text-center'>
                <th class='w-110px'><?php echo $lang->measurement->param->varName?></th>
                <th class='w-120px'><?php echo $lang->measurement->param->showName?></th>
                <th class='w-p30'><?php echo $lang->measurement->param->varType?></th>
                <th class='w-200px'><?php echo $lang->measurement->param->defaultValue?></th>
                <th class='w-200px'><?php echo $lang->measurement->param->queryValue?></th>
              </tr>
            </thead>
            <tbody class='text-center'>
              <?php if($hasParams):?>
              <?php $index = 0;?>
              <?php foreach($params as $param):?>
              <tr id='<?php echo $param['varName']?>'>
                <td><span></span><?php echo $param['varName'] . html::hidden("varName[]", $param['varName'])?></td>
                <td><?php echo html::input("showName[]", $param['showName'], "class='form-control'")?></td>
                <td>
                  <div class='input-group'>
                    <span class='input-group-addon' style='text-align:left'>
                    <?php echo html::radio("varType[$index]", $lang->measurement->param->typeList, $param['varType'], "onchange='toggleSelectList(this)'")?>
                    </span>
                    <?php
                    $hidden = ($param['varType'] == 'select') ? '' : 'hidden';
                    echo html::select("options[]", $lang->measurement->param->options, $param['options'], "class='form-control $hidden'");
                    ?>
                  </div>
                </td>
                <td><?php echo $this->measurement->buildParamControl('defaultValue', $param['varType'], $param['defaultValue'], $param['options']);?></td>
                <td><?php echo $this->measurement->buildParamControl('queryValue', $param['varType'], $param['queryValue'], $param['options']);?></td>
              </tr>
              <?php $index ++;?>
              <?php endforeach;?>
              <?php endif;?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan='5' class='text-center'><?php echo html::submitButton();?></td>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<div id='templateBox' class='hidden'>
  <table>
    <tbody>
      <tr class='template'>
        <td><span></span><?php echo html::hidden('varName[]', '')?></td>
        <td><?php echo html::input('showName[]', '', "class='form-control'")?></td>
        <td>
          <div class='input-group'>
            <span class='input-group-addon' style='text-align:left'>
            <?php echo html::radio('varType[]', $lang->measurement->param->typeList, 'select', "onchange='toggleSelectList(this)'")?>
            </span>
            <?php echo html::select('options[]', $lang->measurement->param->options, '', "class='form-control'");?>
          </div>
        </td>
        <td><?php echo html::select('defaultValue[]', $programPairs, '', "class='form-control'")?></td>
        <td><?php echo html::select('queryValue[]', $programPairs, '', "class='form-control'")?></td>
      </tr>
    </tbody>
  <table>
</div>
<div id='tmpDataBox' class='hidden'>
</div>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
