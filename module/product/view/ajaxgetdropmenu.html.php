<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<input type='text' class='form-control' id='search' value='' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'/>
<div id='searchResult'>
  <div id='defaultMenu' class='search-list'>
    <ul>
    <?php
    $iCharges = 0;
    $others   = 0;
    $closeds  = 0;
    foreach($products as $product)
    {
        if($product->status == 'normal' and $product->PO == $this->app->user->account) $iCharges++;
        if($product->status == 'normal' and !($product->PO == $this->app->user->account)) $others++;
        if($product->status == 'closed') $closeds++;
    }
 
    if($iCharges and $others) echo "<li class='heading'>{$lang->product->mine}</li>";
    foreach($products as $product)
    {
        if($product->status == 'normal' and $product->PO == $this->app->user->account) 
        {
            echo "<li data-id='{$product->id}' data-tag=':{$product->status} @{$product->PO} @mine' data-key='{$product->key}'>" . html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "class='mine text-important'"). "</li>";
        }
    }
 
    if($iCharges and $others) echo "<li class='heading'>{$lang->product->other}</li>";
    $class = ($iCharges and $others) ? "class='other text-special'" : '';
    foreach($products as $product)
    {
        if($product->status == 'normal' and !($product->PO == $this->app->user->account))
        {
            echo "<li data-id='{$product->id}' data-tag=':{$product->status} @{$product->PO}' data-key='{$product->key}'>" . html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "$class"). "</li>";
        }
    }
    ?>
    </ul>
 
    <div>
      <?php echo html::a($this->createLink('product', 'all', "productID=$productID"), "<i class='icon-cubes mgr-5px'></i> " . $lang->product->allProduct)?>
      <?php if($closeds):?>
      <div class='pull-right actions'><a id='more' href='javascript:switchMore()'><?php echo $lang->product->closed;?> <i class='icon-angle-right'></i></a></div>
      <?php endif;?>
    </div>
  </div>
  <div id='moreMenu'>
    <ul>
    <?php
      foreach($products as $product)
      {
        if($product->status == 'closed') echo "<li data-id='{$product->id}' data-tag=':{$product->status} @{$product->PO}' data-key='{$product->key}'>" . html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "class='closed'"). "</li>";
      }
    ?>
    </ul>
  </div>
</div>
