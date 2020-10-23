<?php $selected = key($productlines);?>
<div class='alert alert-info'>
  <?php
  printf($lang->upgrade->mergeSummary, $noMergedProductCount, $noMergedProjectCount);
  echo '<br />' . $lang->upgrade->mergeByProductLine;
  ?>
</div>
<div class='main-row' style='margin-top: 20px; padding: 20px; background: #f1f1f1'>
  <div class='side-col'>
    <div class='cell'>
      <div class='detial'>
        <div class="detail-title"><?php echo $lang->upgrade->line;?></div>
        <div class="detail-content article-content">
          <ul class='nav scrollbar-hover' id='lineList'>
          <?php foreach($productlines as $line):?>
          <li <?php if($line->id == $selected) echo "class='active' id='activeLine'";?> lineID='<?php echo $line->id;?>'>
            <?php echo html::a("###", $line->name, '', "data-target=#line{$line->id}");?>
          </li>
          <?php endforeach;?>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class='table-col' id='source'>
    <div class='cell'>
      <div class='lineGroup-title'>
        <div class='item'><strong><?php echo $lang->upgrade->product;?></strong></div>
        <div class='item'><strong><?php echo $lang->upgrade->project;?></strong></div>
      </div>
      <?php $i = 0;?>
      <?php foreach($productlines as $line):?>
      <?php
      if(!isset($lineGroups[$line->id]))
      {
          unset($productlines[$line->id]);
          continue;
      }
      ?>
      <div id='line<?php echo $line->id;?>' class='<?php if($line->id != $selected) echo 'hidden';?> lineBox'>
        <?php $projectHtml = '';?>
        <?php foreach($lineGroups[$line->id] as $productID => $product):?>
        <div class='lineGroup'>
          <div class='productList'>
          <?php echo html::checkBox("products[$line->id]", array($productID => "{$lang->productCommon} #{$product->id} {$product->name}"), $i == 0 ? $product->id : 0, "data-productid='{$product->id}' data-line='{$line->id}' data-begin='{$product->createdDate}'");?>
          <?php echo html::hidden("productIdList[$line->id][$productID]", $productID);?>
          </div>
          <div class='projectList'>
            <div class='scroll-handle scrollbar-hover'>
            <?php if(isset($productGroups[$productID])):?>
            <?php foreach($productGroups[$productID] as $project):?>
            <?php echo html::checkBox("projects[$line->id][$productID]", array($project->id => "{$lang->upgrade->project} #{$project->id} {$project->name}"), $i == 0 ? $project->id : 0, "data-product='{$product->id}' data-line='{$line->id}' data-begin='{$project->begin}'");?>
            <?php echo html::hidden("projectIdList[$line->id][$productID][$project->id]", $project->id);?>
            <?php endforeach;?>
            <?php endif;?>
            </div>
          </div>
        </div>
        <?php endforeach;?>
      </div>
      <?php $i ++;?>
      <?php endforeach;?>
    </div>
  </div>
  <div class='table-col divider strong'></div>
  <div class='table-col pgmWidth' id='programBox'>
    <div class='cell'> 
    <?php
    $line = reset($productlines);
    $programName = $line->name;
    include "./createprogram.html.php";
    ?>
    </div>
  </div>
</div>
