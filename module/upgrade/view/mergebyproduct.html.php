<style> #source .lineGroup .productList{width: 250px;} </style>
<div class='alert alert-info'>
  <?php
  printf($lang->upgrade->mergeSummary, $noMergedProductCount, $noMergedSprintCount);
  echo '<br />' . $lang->upgrade->mergeByProduct;
  ?>
</div>
<div class='main-row'>
  <div class='table-col' id='source'>
    <div class='cell'>
      <div class='lineGroup-title'>
        <div class='item'><strong><?php echo $lang->upgrade->product;?></strong></div>
        <div class='item'><strong><?php echo $lang->upgrade->project;?></strong></div>
      </div>
      <?php foreach($noMergedProducts as $productID => $product):?>
      <div class='lineGroup'>
        <div class='productList'>
          <?php echo html::checkBox("products", array($product->id => "{$lang->productCommon} #{$product->id} {$product->name}"), $product->id, "data-productid='{$product->id}' data-begin='{$product->createdDate}'");?>
        </div>
        <div class='projectList'>
          <div class='scroll-handle'>
          <?php if(isset($productGroups[$productID])):?>
          <?php foreach($productGroups[$productID] as $sprint):?>
          <?php echo html::checkBox("sprints[$productID]", array($sprint->id => "{$lang->upgrade->project} #{$sprint->id} {$sprint->name}"), $sprint->id, "data-product='{$productID}' data-begin='{$sprint->begin}' data-end='{$sprint->end}'");?>
          <?php echo html::hidden("sprintIdList[$productID][$sprint->id]", $sprint->id);?>
          <?php endforeach;?>
          <?php endif;?>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
  <div class='table-col divider strong'></div>
  <div class='table-col pgmWidth' id='programBox'>
    <div class='cell'>
      <?php include "./createprogram.html.php";?>
    </div>
  </div>
</div>
