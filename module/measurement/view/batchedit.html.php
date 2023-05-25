<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<?php js::set('orderBy', $orderBy);?>
<div class="main-content" id="mainContent">
  <div class="main-header">
    <h2><?php echo $lang->measurement->batchEdit;?></h2>
  </div>
  <form class="load-indicator main-form" method="post" target="hiddenwin" id="batchEdit">
    <div class="table-responsive">
      <table class="table table-form">
        <thead>
          <tr class="text-center">
            <th class="w-50px"><?php echo $lang->sort;?></th>
            <th class="w-30px"><?php echo $lang->idAB;?></th>
            <th class="w-120px"><?php echo $this->lang->measurement->purpose?></th>
            <th class="w-120px"><?php echo $this->lang->measurement->scope?></th>
            <th class="w-120px"><?php echo $this->lang->measurement->object?></th>
            <th class="required"><?php echo $this->lang->measurement->name?></th>
            <th class="required"><?php echo $this->lang->measurement->code?></th>
            <th class="required"><?php echo $this->lang->measurement->unit?></th>
            <th class="required"><?php echo $this->lang->measurement->definition?></th>
          </tr>
        </thead>
        <tbody class="sortable" id="measurementList">
          <?php foreach($measurements as $measurement):?>
            <tr data-id="<?php echo $measurement->id;?>" class="text-center">
              <td class='sort-handler' title='<?php echo $lang->dragAndSort?>'><i class='icon-move'></i></td>
              <td><?php echo $measurement->id;?></td>
              <td><?php echo html::select("purpose[$measurement->id]", $lang->measurement->purposeList, $measurement->purpose, "class='form-control'");?></td>
              <td><?php echo html::select("scope[$measurement->id]", $lang->measurement->scopeList, $measurement->scope, "class='form-control'");?></td>
              <td><?php echo html::select("object[$measurement->id]", $lang->measurement->objectList, $measurement->object, "class='form-control'");?></td>
              <td><?php echo html::input("name[$measurement->id]", $measurement->name, "class='form-control'");?></td>
              <td><?php echo html::input("code[$measurement->id]", $measurement->code, "class='form-control'");?></td>
              <td><?php echo html::input("unit[$measurement->id]", $measurement->unit, "class='form-control'");?></td>
              <td><?php echo html::textarea("definition[$measurement->id]", $measurement->definition, "class='form-control'");?></td>
            </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="9" class="text-center form-actions">
              <?php echo html::submitButton($lang->save)?>
              <?php echo html::backButton()?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
