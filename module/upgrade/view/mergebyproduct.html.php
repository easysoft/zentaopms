<table class='table table-form'>
  <thead>
    <tr>
      <th><?php echo $lang->upgrade->program;?></th>
      <th><?php echo $lang->upgrade->programAdmin;?></th>
      <th><?php echo $lang->upgrade->product;?></th>
      <th><?php echo $lang->upgrade->project;?></th>
    </tr>
  </thead>
  <tbody>
  <?php $i = 0;?>
  <?php foreach($noMergedProducts as $productID => $product):?>
  <tr>
    <?php if($i == 0):?>
    <td class='text-top' rowspan='<?php echo count($noMergedProducts);?>'>
      <div class='input-group'>
        <?php echo html::select("programs", $programs, '', "class='form-control chosen'");?>
        <?php echo html::input("programName", '', "class='form-control'");?>
        <span class='input-group-addon'>
          <div class="checkbox-primary">
            <input type="checkbox" name="newProgram" value="0" checked onchange="toggleProgram(this)" id="newProgram0" />
            <label for="newProgram0"><?php echo $lang->upgrade->newProgram;?></label>
          </div>
        </span>
      </div>
    </td>
    <td class='text-top' rowspan='<?php echo count($noMergedProducts);?>'><?php echo html::select("account", $users, '', "class='form-control chosen'");?></td>
    <?php endif;?>
    <td class='text-top'>
      <?php
      echo html::checkBox("products", array($product->id => "{$lang->productCommon} #{$product->id} {$product->name}"), $product->id, "data-productid='{$product->id}'");
      echo html::hidden("productIdList[$product->id]", $product->id);
      ?>
    </td>
    <td>
      <?php if(isset($productGroups[$productID])):?>
      <?php foreach($productGroups[$productID] as $project):?>
      <?php echo html::checkBox("projects", array($project->id => "{$lang->projectCommon} #{$project->id} {$project->name}"), $project->id, "data-product='{$productID}'");?>
      <?php echo html::hidden("projectIdList[$productID][$project->id]", $project->id);?>
      <?php endforeach;?>
      <?php endif;?>
    </td>
  </tr>
  <?php $i++;?>
  <?php endforeach;?>
  </tbody>
</table>
