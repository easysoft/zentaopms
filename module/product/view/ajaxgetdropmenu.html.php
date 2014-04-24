<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<input type='text' class='form-control' id='search' value='' onkeyup='searchItems(this.value, "product", productID, module, method, extra)' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'/>

<div id='searchResult'>
  <div id='defaultMenu'>
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
            echo "<li>" . html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "class='mine text-important'"). "</li>";
        }
    }
 
    if($iCharges and $others) echo "<li class='heading'>{$lang->product->other}</li>";
    $class = ($iCharges and $others) ? "class='other text-special'" : '';
    foreach($products as $product)
    {
        if($product->status == 'normal' and !($product->PO == $this->app->user->account))
        {
            echo "<li>" . html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "$class"). "</li>";
        }
    }
    ?>
    </ul>
 
    <?php if($closeds):?>
    <div class='text-right'><a class='gray' id='more' onClick='switchMore()'><?php echo $lang->product->closed . ' <i class="icon-angle-right"></i>';?></a></div>
    <?php endif;?>
 
  </div>
 
  <div id='moreMenu'>
    <ul>
    <?php
      foreach($products as $product)
      {
        if($product->status == 'closed') echo "<li>" . html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "class='closed'"). "</li>";
      }
    ?>
    </ul>
  </div>
</div>
