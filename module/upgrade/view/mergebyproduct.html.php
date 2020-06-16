<div class='table-row'>
  <div class='table-col' id='source'>
    <div class='alert alert-info'>
      <?php
      printf($lang->upgrade->mergeSummary, $noMergedProductCount, $noMergedProjectCount);
      echo '<br />' . $lang->upgrade->mergeByProduct;
      ?>
    </div>
    <table class='table table-form'>
      <thead>
        <tr>
          <th><?php echo $lang->upgrade->product;?></th>
          <th><?php echo $lang->upgrade->project;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($noMergedProducts as $productID => $product):?>
        <tr>
          <td class='text-top'><?php echo html::checkBox("products", array($product->id => "{$lang->productCommon} #{$product->id} {$product->name}"), $product->id, "data-productid='{$product->id}' data-begin='{$product->createdDate}'");?></td>
          <td class='text-top'>
            <?php if(isset($productGroups[$productID])):?>
            <?php foreach($productGroups[$productID] as $project):?>
            <?php echo html::checkBox("projects[$productID]", array($project->id => "{$lang->projectCommon} #{$project->id} {$project->name}"), $project->id, "data-product='{$productID}' data-begin='{$project->begin}'");?>
            <?php echo html::hidden("projectIdList[$productID][$project->id]", $project->id);?>
            <?php endforeach;?>
            <?php endif;?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <div class='table-col divider strong'></div>
  <div class='table-col pgmWidth' id='programBox'><?php include "./createprogram.html.php";?></div>
</div>
