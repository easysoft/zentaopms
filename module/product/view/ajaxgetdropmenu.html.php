<div class="list-group">
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
 
  foreach($products as $product)
  {
      if($product->status == 'normal' and $product->PO == $this->app->user->account) 
      {
          if($product->type != 'platform' && $module == 'branch' && $method == 'manage')
          {
              echo html::a(sprintf($link, $productID), "<i class='icon-cube'></i> " . $product->name, '', "title='{$product->name}' data-key='" . zget($productsPinYin, $product->name, '') . "'");
          }
          else
          {
              echo html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "title='{$product->name}' data-key='" . zget($productsPinYin, $product->name, '') . "'");
          }
      }
  }
 
  foreach($products as $product)
  {
      if($product->status == 'normal' and !($product->PO == $this->app->user->account)) 
      {
          if($product->type != 'platform' && $module == 'branch' && $method == 'manage')
          {
              echo html::a(sprintf($link, $productID), "<i class='icon-cube'></i> " . $product->name, '', "title='{$product->name}' data-key='" . zget($productsPinYin, $product->name, '') . "'");
          }
          else
          {
              echo html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "title='{$product->name}' data-key='" . zget($productsPinYin, $product->name, '') . "'");
          }
      }
  }

  foreach($products as $product)
  {
      if($product->status == 'closed')
      {

          if($product->type != 'platform' && $module == 'branch' && $method == 'manage')
          {
              echo html::a(sprintf($link, $productID), "<i class='icon-cube'></i> " . $product->name, '', "title='{$product->name}' class='closed' data-key='" . zget($productsPinYin, $product->name, '') . "'");
          }
          else
          {
              echo html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "title='{$product->name}' class='closed' data-key='" . zget($productsPinYin, $product->name, '') . "'");
          }
      }
  }
  ?>
</div>
