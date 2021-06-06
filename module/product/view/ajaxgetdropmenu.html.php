<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<?php
$iCharges = 0;
$others   = 0;
$closeds  = 0;
$productNames = array();

foreach($products as $product)
{
    if($product->status == 'normal' and $product->PO == $this->app->user->account) $iCharges++;
    if($product->status == 'normal' and !($product->PO == $this->app->user->account)) $others++;
    if($product->status == 'closed') $closeds++;
    $productNames[] = $product->name;
}
$productsPinYin     = common::convert2Pinyin($productNames);
$myProductsHtml     = '';
$normalProductsHtml = '';
$closedProductsHtml = '';

foreach($products as $product)
{
    $selected     = $product->id == $productID ? 'selected' : '';
    $productName  = $product->program ? zget($programs, $product->program, '') . '/' : '';
    $productName .= $product->line ? zget($lines, $product->line, '') . '/' . $product->name : $product->name;
    if($product->status == 'normal' and $product->PO == $this->app->user->account)
    {
        $objectID = ($product->type != 'platform' && $module == 'branch' && $method == 'manage') ? $productID : $product->id;
        $linkHtml = $this->product->setParamsForLink($module, $link, $projectID, $product->id);
        $myProductsHtml .= html::a($linkHtml, $productName, '', "class='text-important $selected' title='{$productName}' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='$openApp'");
    }
    else if($product->status == 'normal' and !($product->PO == $this->app->user->account))
    {
        $objectID = ($product->type != 'platform' && $module == 'branch' && $method == 'manage') ? $productID : $product->id;
        $linkHtml = $this->product->setParamsForLink($module, $link, $projectID, $product->id);
        $normalProductsHtml .= html::a($linkHtml, $productName, '', "class='$selected' title='{$productName}' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='$openApp'");
    }
    else if($product->status == 'closed')
    {
        $objectID = ($product->type != 'platform' && $module == 'branch' && $method == 'manage') ? $productID : $product->id;
        $linkHtml = $this->product->setParamsForLink($module, $link, $projectID, $objectID);
        $closedProductsHtml .= html::a($linkHtml, $productName, '', "class='$selected' title='{$productName}' class='closed' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='$openApp'");
    }
}
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <?php
      if(!empty($myProductsHtml))
      {
          echo "<div class='heading'>{$lang->product->mine}</div>";
          echo $myProductsHtml;
          if(!empty($myProductsHtml))
          {
              echo "<div class='heading'>{$lang->product->other}</div>";
          }
      }
      echo $normalProductsHtml;
      ?>
    </div>
    <div class="col-footer">
      <?php //echo html::a(helper::createLink('product', 'all'), '<i class="icon icon-cards-view muted"></i> ' . $lang->product->all, '', 'class="not-list-item"'); ?>
      <?php //echo html::a(helper::createLink('project', 'browse', 'programID=0&browseType=all'), '<i class="icon icon-cards-view muted"></i> ' . $lang->project->all, '', 'class="not-list-item"'); ?>
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->product->closed?><i class='icon icon-angle-right'></i></a>
    </div>
  </div>
  <div class="table-col col-right">
   <div class='list-group'><?php echo $closedProductsHtml;?></div>
  </div>
</div>
<script>scrollToSelected();</script>
