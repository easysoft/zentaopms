<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<div class="input-control search-box search-box-circle has-icon-left has-icon-right search-example">
  <input type="search" class="form-control search-input" />
  <label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
  <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
</div>
<div class="list-group">
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
 
  foreach($products as $product)
  {
      if($product->status == 'normal' and $product->PO == $this->app->user->account) 
      {
          echo html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "data-filter='" . zget($productsPinYin, $product->name, '') . "'");
      }
  }
 
  foreach($products as $product)
  {
      if($product->status == 'normal' and !($product->PO == $this->app->user->account))
      {
          echo html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "data-filter='" . zget($productsPinYin, $product->name, '') . "'");
      }
  }

  foreach($products as $product)
  {
      if($product->status == 'closed') echo html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "class='closed' data-filter='" . zget($productsPinYin, $product->name, '') . "'");
  }
  ?>
</div>
