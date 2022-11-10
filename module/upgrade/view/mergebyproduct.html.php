<style>
#source .lineGroup .productList{width: 250px; word-break: break-all;}
.checkbox-primary > label{height: auto;}
</style>
<div class='alert alert-info'>
  <?php
  $content = '';
  if($noMergedProductCount) $content .= sprintf($lang->upgrade->productCount, $noMergedProductCount);
  if($content) $content .= ',';
  if($noMergedSprintCount)  $content .= sprintf($lang->upgrade->projectCount, $noMergedSprintCount);
  printf($lang->upgrade->mergeSummary, $content);
  echo '<br />' . $lang->upgrade->mergeByProject;
  ?>
</div>
<div class='main-row'>
  <div class='table-col' id='source'>
    <div class='cell'>
      <div class='lineGroup-title'>
        <div class='item checkbox-primary w-250px' title="<?php echo $lang->selectAll?>">
          <input type='checkbox' id='checkAllProducts'><label for='checkAllProducts'><strong><?php echo $lang->productCommon;?></strong></label>
        </div>
        <div class='item checkbox-primary' title="<?php echo $lang->selectAll?>">
          <input type='checkbox' id='checkAllProjects'><label for='checkAllProjects'><strong><?php echo $lang->projectCommon;?></strong></label>
        </div>
      </div>
      <div class='line-groups'>
        <?php foreach($noMergedProducts as $productID => $product):?>
        <div class='lineGroup'>
          <div class='productList'>
            <?php echo html::checkBox("products", array($product->id => $product->name), '', "data-productid='{$product->id}' data-begin='{$product->createdDate}' data-programid='{$product->program}'");?>
          </div>
          <div class='projectList'>
            <div class='scroll-handle'>
              <?php if(isset($productGroups[$productID])):?>
              <?php foreach($productGroups[$productID] as $sprint):?>
              <div class="sprintItem">
                <?php echo html::checkBox("sprints[$productID]", array($sprint->id => $sprint->name), '', "data-product='{$productID}' data-begin='{$sprint->begin}' data-end='{$sprint->end}' data-status='{$sprint->status}' data-pm='{$sprint->PM}'");?>
                <?php echo html::hidden("sprintIdList[$productID][$sprint->id]", $sprint->id);?>
              </div>
              <?php endforeach;?>
              <?php endif;?>
            </div>
          </div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </div>
  <div class='table-col divider strong'><i class='icon icon-angle-double-right'></i></div>
  <div class='table-col pgmWidth' id='programBox'>
    <div class='cell'>
      <?php include "./createprogram.html.php";?>
    </div>
  </div>
</div>
