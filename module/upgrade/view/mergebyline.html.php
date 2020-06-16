<div class='table-row'>
  <div class='table-col' id='source'>
    <div class='alert alert-info'>
      <?php
      printf($lang->upgrade->mergeSummary, $noMergedProductCount, $noMergedProjectCount);
      echo '<br />' . $lang->upgrade->mergeByProductLine;
      ?>
    </div>
    <table class='table table-form'>
      <thead>
        <tr>
          <th class='w-150px'><?php echo $lang->upgrade->line;?></th>
          <th><?php echo $lang->upgrade->product;?></th>
          <th><?php echo $lang->upgrade->project;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 0;?>
        <?php foreach($productlines as $line):?>
        <?php
        if(!isset($lineGroups[$line->id]))
        {
            unset($productlines[$line->id]);
            continue;
        }
        ?>
        <tr>
          <td class='text-top'>
            <?php echo html::checkBox("lines", array($line->id => "{$line->name}"), $i == 0 ? $line->id : 0, "data-lineid='{$line->id}'");?>
          </td>
          <td class='text-top'>
            <?php $projectHtml = '';?>
            <?php foreach($lineGroups[$line->id] as $productID => $product):?>
            <?php echo html::checkBox("products[$line->id]", array($productID => "{$lang->productCommon} #{$product->id} {$product->name}"), $i == 0 ? $product->id : 0, "data-productid='{$product->id}' data-line='{$line->id}' data-begin='{$product->createdDate}'");?>
            <?php echo html::hidden("productIdList[$line->id][$productID]", $productID);?>
            <?php if(isset($productGroups[$productID])):?>
            <?php foreach($productGroups[$productID] as $project):?>
            <?php $projectHtml .= html::checkBox("projects[$line->id][$productID]", array($project->id => "{$lang->projectCommon} #{$project->id} {$project->name}"), $i == 0 ? $project->id : 0, "data-product='{$product->id}' data-line='{$line->id}' data-begin='{$project->begin}'");?>
            <?php $projectHtml .= html::hidden("projectIdList[$line->id][$productID][$project->id]", $project->id);?>
            <?php endforeach;?>
            <?php endif;?>
            <?php endforeach;?>
          </td>
          <td class='text-top'><?php echo $projectHtml;?></td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <div class='table-col divider strong'></div>
  <div class='table-col pgmWidth' id='programBox'>
    <?php
    $line = reset($productlines);
    $programName = $line->name;
    include "./createprogram.html.php";
    ?>
  </div>
</div>
