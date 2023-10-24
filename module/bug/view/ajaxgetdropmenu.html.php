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
$productsPinYin = common::convert2Pinyin($productNames);
$linkHtml       = $this->product->setParamsForLink($module, $link, $projectID, 0);
$productsHtml   = (count($products) > 1 and strpos(',zeroCase,browseUnits,groupCase,', ",$method,") === false) ? html::a($linkHtml, $this->lang->bug->allProduct, '', "class='text-primary' title='{$this->lang->bug->allProduct}' data-key='" . zget($productsPinYin, $this->lang->bug->allProduct, '') . "' data-app='{$this->app->tab}'") : '';

foreach($products as $product)
{
    $selected     = $product->id == $productID ? 'selected' : 'text-primary';
    $productName  = ($product->program and $this->config->systemMode == 'ALM') ? zget($programs, $product->program, '') . '/' : '';
    $productName .= ($this->config->systemMode == 'ALM' and $product->line) ? zget($lines, $product->line, '') . '/' . $product->name : $product->name;
    $objectID = ($product->type != 'platform' && $module == 'branch' && $method == 'manage') ? $productID : $product->id;
    $linkHtml = $this->product->setParamsForLink($module, $link, $projectID, $product->id);
    $productsHtml .= html::a($linkHtml, $productName, '', "class='$selected' title='{$productName}' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='{$this->app->tab}'");
}
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'><?php echo $productsHtml;?></div>
  </div>
</div>
<script>scrollToSelected();</script>
