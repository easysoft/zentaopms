<style> #source .lineGroup .productList{width: 170px;} </style>
<?php reset($productlines);?>
<?php $selected = key($productlines);?>
<div class='alert alert-info'>
  <?php
  printf($lang->upgrade->mergeSummary, $noMergedProductCount, $noMergedSprintCount);
  echo '<br />' . $lang->upgrade->mergeByProductLine;
  ?>
</div>
<div class='main-row'>
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
  <div class='table-col divider strong'><i class='icon icon-angle-double-right'></i></div>
  <div class='table-col' id='source'>
    <div class='cell'>
      <div class='lineGroup-title'>
        <div class='item'><strong><?php echo $lang->upgrade->product;?></strong></div>
        <div class='item'><strong><?php echo $lang->upgrade->project;?></strong></div>
      </div>
      <div class='line-groups'>
        <?php $i = 0;?>
        <?php foreach($productlines as $line):?>
        <div id='line<?php echo $line->id;?>' class='<?php if($line->id != $selected) echo 'hidden';?> lineBox'>
          <?php foreach($lineGroups[$line->id] as $productID => $product):?>
          <div class='lineGroup'>
            <div class='productList'>
            <?php echo html::checkBox("products[$line->id]", array($productID => $product->name), $i == 0 ? $product->id : 0, "title='{$product->name}' data-productid='{$product->id}' data-line='{$line->id}' data-begin='{$product->createdDate}' data-programid='{$product->program}'");?>
            <?php echo html::hidden("productIdList[$line->id][$productID]", $productID);?>
            </div>
            <div class='projectList'>
              <div class='scroll-handle'>
              <?php if(isset($productGroups[$productID])):?>
              <?php foreach($productGroups[$productID] as $sprint):?>
              <?php echo html::checkBox("sprints[$line->id][$productID]", array($sprint->id => $sprint->name), $i == 0 ? $sprint->id : 0, "title='{$sprint->name}' data-product='{$product->id}' data-line='{$line->id}' data-begin='{$sprint->begin}' data-end='{$sprint->end}' data-status='{$sprint->status}' data-pm='{$sprint->PM}'");?>
              <?php echo html::hidden("sprintIdList[$line->id][$productID][$sprint->id]", $sprint->id);?>
              <?php endforeach;?>
              <?php endif;?>
              </div>
            </div>
          </div>
          <?php $i ++;?>
          <?php endforeach;?>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </div>
  <div class='table-col divider strong'><i class='icon icon-angle-double-right'></i></div>
  <div class='table-col pgmWidth' id='programBox'>
    <div class='cell'>
    <?php
    $line = reset($productlines);
    $programName = $line->name;
    $product     = isset($lineGroups[$line->id]) ? reset($lineGroups[$line->id]) : '';
    $sprintName  = $product ? $product->name : '';
    include "./createprogram.html.php";
    ?>
    </div>
  </div>
</div>
