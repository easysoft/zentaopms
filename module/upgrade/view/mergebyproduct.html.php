<table class='table table-form'>
  <thead>
    <tr>
      <th><?php echo $lang->upgrade->product;?></th>
      <th><?php echo $lang->upgrade->project;?></th>
      <th><?php echo $lang->upgrade->program;?></th>
      <th class='w-150px'><?php echo $lang->upgrade->pgmAdmin;?></th>
    </tr>
  </thead>
  <tbody>
  <?php $i = 0;?>
  <?php foreach($noMergedProducts as $productID => $product):?>
  <tr>
    <td class='text-top'><?php echo html::checkBox("products", array($product->id => "{$lang->productCommon} #{$product->id} {$product->name}"), $product->id, "data-productid='{$product->id}'");?></td>
    <td>
      <?php if(isset($productGroups[$productID])):?>
      <?php foreach($productGroups[$productID] as $project):?>
      <?php echo html::checkBox("projects[$productID]", array($project->id => "{$lang->projectCommon} #{$project->id} {$project->name}"), $project->id, "data-product='{$productID}'");?>
      <?php echo html::hidden("projectIdList[$productID][$project->id]", $project->id);?>
      <?php endforeach;?>
      <?php endif;?>
    </td>
    <?php if($i == 0):?>
    <td class='text-top' rowspan='<?php echo count($noMergedProducts);?>'>
      <div class='input-group'>
        <?php if($programs) echo html::select("programs", $programs, '', "class='form-control chosen'");?>
        <?php echo html::input("programName", '', "class='form-control'");?>
        <?php if($programs):?>
        <span class='input-group-addon'>
          <div class="checkbox-primary">
            <input type="checkbox" name="newProgram" value="0" checked onchange="toggleProgram(this)" id="newProgram0" />
            <label for="newProgram0"><?php echo $lang->upgrade->newProgram;?></label>
          </div>
        </span>
        <?php endif;?>
      </div>
    </td>
    <td class='text-top' rowspan='<?php echo count($noMergedProducts);?>'><?php echo html::select("pgmAdmin", $users, '', "class='form-control chosen'");?></td>
    <?php endif;?>
  </tr>
  <?php $i++;?>
  <?php endforeach;?>
  </tbody>
</table>
