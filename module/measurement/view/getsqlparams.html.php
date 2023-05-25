<table class='table active-disabled'>
  <thead>
    <tr class='text-center'>
      <th class='w-110px'><?php echo $lang->measurement->param->varName?></th>
      <th class='w-200px'><?php echo $lang->measurement->param->showName?></th>
      <th><?php echo $lang->measurement->param->varType?></th>
      <th class='w-300px'><?php echo $lang->measurement->param->defaultValue?></th>
      <th class='w-300px'><?php echo $lang->measurement->param->queryValue?></th>
    </tr>
  </thead>
  <tbody class='text-center'>
    <?php $index = 0;?>
    <?php foreach($params as $param):?>
    <?php if($param['varType'] == '' and $param['varName'] == '$program'):?>
    <?php $param['varType'] = 'select';?>
    <?php $param['showName'] = $lang->measurement->buildinParams->program;?>
    <?php endif;?>
    <?php if($param['varType'] == '' and stripos($param['varName'], 'date') !== false):?>
    <?php $param['varType'] = 'date';?>
    <?php $param['showName'] = $lang->measurement->buildinParams->day;?>
    <?php $value = $lang->measurement->buildinParams->day;?>
    <?php endif;?>
    <tr id='<?php echo $param['varName']?>'>
      <td><span></span><?php echo $param['varName'] . html::hidden("varName[]", $param['varName'])?></td>
      <td><?php echo html::input("showName[]", $param['showName'], "class='form-control'")?></td>
      <td>
        <div class='col-md-6'>
          <?php echo html::select("varType", $lang->measurement->param->typeList, $param['varType'], "class='form-control chosen' onchange='toggleSelectList(this)'")?>
        </div>
        <div class='col-md-6'>
          <?php
          $hidden = ($param['varType'] == 'select') ? '' : 'hidden';
          echo html::select("options[]", $lang->measurement->param->options, $param['options'], "class='form-control $hidden' onchange='togglParamList(this)'");
          ?>
        </div>
      </td>
      <td><?php echo $this->measurement->buildParamControl('defaultValue', $param['varType'], $param['defaultValue'], $param['options']);?></td>
      <td><?php echo $this->measurement->buildParamControl('queryValue', $param['varType'], $param['queryValue'], $param['options']);?></td>
    </tr>
    <?php $index ++;?>
    <?php endforeach;?>
 </tbody>
</table>
<div id="responseBox" class='alter'></div>
<div class='panel-body'>
  <input type='button' value='<?php echo $lang->measurement->test;?>' id='testBtn' class='btn btn-success' onclick='submitForm("test")'/>
  <input type='button' value='<?php echo $lang->save;?>' id='submit' class='btn' disabled onclick='submitForm("submit")'/>
</div>
<script>
$('[name^=varType]').change();
</script>
