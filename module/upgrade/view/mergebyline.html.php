<?php reset($productlines);?>
<?php $selected = key($productlines);?>
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
<div class='main-row mergeByLine'>
  <div class='side-col' id='lineBox'>
    <div class='cell'>
      <div class='detial'>
        <div class="checkbox-primary" title="<?php echo $lang->selectAll?>">
          <input type='checkbox' id='checkAllLines'><label for='checkAllLines'><strong><?php echo $lang->upgrade->allLines;?></strong></label>
        </div>
        <div class="detail-content article-content">
          <ul class='nav scrollbar-hover' id='lineList'>
          <?php foreach($productlines as $line):?>
          <li <?php if($line->id == $selected) echo "class='currentPage' id='activeLine'";?> lineID='<?php echo $line->id;?>'>
            <div>
              <?php echo html::checkbox("productLines[$line->id]", array($line->id => ''), '' ,"class='tile'") . html::a("###", $line->name, '', "data-target=#line{$line->id}");?>
            </div>
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
        <div class='item checkbox-primary' title="<?php echo $lang->selectAll?>">
          <input type='checkbox' id='checkAllProducts'><label for='checkAllProducts'><strong><?php echo $lang->productCommon;?></strong></label>
        </div>
        <div class='item checkbox-primary' title="<?php echo $lang->selectAll?>">
          <input type='checkbox' id='checkAllProjects'><label for='checkAllProjects'><strong><?php echo $lang->projectCommon;?></strong></label>
        </div>
      </div>
      <div class='line-groups'>
        <?php $i = 0;?>
        <?php foreach($productlines as $line):?>
        <div id='line<?php echo $line->id;?>' class='<?php if($line->id != $selected) echo 'hidden';?> lineBox'>
          <?php foreach($lineGroups[$line->id] as $productID => $product):?>
          <div class='lineGroup'>
            <div class='productList'>
            <?php echo html::checkBox("products[$line->id]", array($productID => $product->name), '', "title='{$product->name}' data-productid='{$product->id}' data-line='{$line->id}' data-begin='{$product->createdDate}' data-programid='{$product->program}' class='tile'");?>
            <?php echo html::hidden("productIdList[$line->id][$productID]", $productID);?>
            </div>
            <div class='projectList'>
              <div class='scroll-handle'>
                <?php if(isset($productGroups[$productID])):?>
                <?php foreach($productGroups[$productID] as $sprint):?>
                <div class="sprintItem">
                  <?php echo html::checkBox("sprints[$line->id][$productID]", array($sprint->id => $sprint->name), '', "title='{$sprint->name}' data-product='{$product->id}' data-line='{$line->id}' data-begin='{$sprint->begin}' data-end='{$sprint->end}' data-status='{$sprint->status}' data-pm='{$sprint->PM}' class='tile'");?>
                  <?php echo html::hidden("sprintIdList[$line->id][$productID][$sprint->id]", $sprint->id);?>
                </div>
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
