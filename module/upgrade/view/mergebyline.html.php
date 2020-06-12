<table class='table table-form'>
  <thead>
    <tr>
      <th><?php echo $lang->upgrade->program;?></th>
      <th><?php echo $lang->upgrade->programAdmin;?></th>
      <th><?php echo $lang->upgrade->line;?></th>
      <th><?php echo $lang->upgrade->product;?></th>
      <th><?php echo $lang->upgrade->project;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($productlines as $line):?>
    <?php if(!isset($lineGroups[$line->id])) continue;?>
    <tr>
      <td class='text-top'><?php echo html::input("newPrograms[$line->id]", $line->name, "class='form-control'");?></td>
      <td class='text-top'><?php echo html::select("account[$line->id]", $users, '', "class='form-control chosen'");?></td>
      <td class='text-top'><?php echo $line->name;?></td>
      <td class='text-top'>
        <?php $projectHtml = '';?>
        <?php foreach($lineGroups[$line->id] as $productID => $product):?>
        <?php echo html::checkBox("products[$line->id]", array($productID => "{$lang->productCommon} #{$product->id} {$product->name}"), $product->id, "data-productid='{$product->id}'");?>
        <?php echo html::hidden("productIdList[$line->id][$productID]", $productID);?>
        <?php if(isset($productGroups[$productID])):?>
        <?php foreach($productGroups[$productID] as $project):?>
        <?php $projectHtml .= html::checkBox("projects[$line->id]", array($project->id => "{$lang->projectCommon} #{$project->id} {$project->name}"), $project->id, "data-product='{$product->id}'");?>
        <?php $projectHtml .= html::hidden("projectIdList[$line->id][$productID][$project->id]", $project->id);?>
        <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>
      </td>
      <td><?php echo $projectHtml;?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
