<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php js::set('measurementID', $measurement->id);?>
<div class='panel'>
  <div class='panel-heading'>
    <div class='panel-title'>
      <span class='text'><?php echo $measurement->name . " $lang->colon " . $lang->measurement->setSQL?></span>
      <?php if($measurement->deleted):?>
      <span class='label label-danger'><?php echo $lang->measurement->deleted;?></span>
      <?php endif;?>
    </div>
  </div>
  <form method='post' id='sqlForm'>
    <table class='table table-form'>
      <tr>
        <td colspan="3"> <?php echo html::textarea('sql', $sql, "placeholder='{$lang->measurement->placeholder->sql}' class='form-control' rows='10'");?> </td>
      </tr>
    </table>
    <div class='panel-body' id="paramBox" style='padding-top: 10px'>
      <?php if(!empty($measurement->params)):?>
        <table class='table'>
          <thead>
            <tr class='text-center'>
              <th class='w-110px'><?php echo $lang->measurement->param->varName?></th>
              <th class='w-150px'><?php echo $lang->measurement->param->showName?></th>
              <th class='w-200px'><?php echo $lang->measurement->param->varType?></th>
              <th class='w-250px'><?php echo $lang->measurement->param->defaultValue?></th>
              <th class='w-250px'><?php echo $lang->measurement->param->queryValue?></th>
            </tr>
          </thead>
          <tbody class='text-center'>
            <?php $index = 0;?>
            <?php foreach($params as $param):?>
            <?php $varType = zget($param, 'varType', '');?>
            <tr id='<?php echo $param['varName']?>'>
              <td><span></span><?php echo $param['varName'] . html::hidden("varName[]", $param['varName'])?></td>
              <td><?php echo html::input("showName[]", $param['showName'], "class='form-control'")?></td>
              <td>
                <div class='col-md-6'>
                  <?php echo html::select("varType[]", $lang->measurement->param->typeList, zget($param, 'varType'), "class='select form-control chosen' onchange='toggleSelectList(this)'")?>
                </div>
                <div class='col-md-6'>
                  <?php
                  $hidden = ($varType == 'select') ? '' : 'hidden';
                  echo html::select("options[]", $lang->measurement->param->options, $param['options'], "class='form-control $hidden' onchange='togglParamList(this)'");
                  ?>
                </div>
              </td>
              <td><?php echo $this->measurement->buildParamControl('defaultValue', $varType, zget($param, 'defaultValue', ''), $param['options']);?></td>
              <td><?php echo $this->measurement->buildParamControl('queryValue', $varType, zget($param, 'queryValue', ''), $param['options']);?></td>
            </tr>
            <?php $index ++;?>
            <?php endforeach;?>
         </tbody>
        </table>
      <?php endif;?>
      <div id="responseBox" class='alter'></div>
      <?php if(!$measurement->deleted):?>
      <div class='panel-body'>
        <input type='button' value='<?php echo $lang->measurement->test;?>' id='testBtn' class='btn btn-success' onclick='submitForm("test")'/>
        <input type='button' value='<?php echo $lang->save;?>' id='submit' class='btn submit' disabled onclick='submitForm("submit")'/>
      </div>
      <?php endif;?>
    </div>
    <?php echo html::hidden('action', 'test');?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
