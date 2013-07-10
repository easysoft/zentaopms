<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<input type='text' class='gray' id='search' value='' onkeyup='searchItems(this.value, "product", productID, module, method, extra)' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'>

<div id='searchResult'>
  <div id='defaultMenu' class='f-left'>
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

    if($iCharges and $others) echo "<span class='black'>{$lang->product->mine}</span>";
    foreach($products as $product)
    {
        if($product->status == 'normal' and $product->PO == $this->app->user->account) 
        {
            echo "<li>" . html::a(sprintf($link, $product->id), $product->name, '', "class='mine'"). "</li>";
        }
    }

    if($iCharges and $others) echo "<span class='black'>{$lang->product->other}</span>";
    $class = ($iCharges and $others) ? "class='other'" : '';
    foreach($products as $product)
    {
        if($product->status == 'normal' and !($product->PO == $this->app->user->account))
        {
            echo "<li>" . html::a(sprintf($link, $product->id), $product->name, '', "$class"). "</li>";
        }
    }
    ?>
    </ul>

    <?php if($closeds):?>
    <div class='a-right'><a class='gray' id='more' onClick='switchMore()'><?php echo $lang->product->closed;?></a></div>
    <?php endif;?>

  </div>

  <div id='moreMenu' class='hidden f-left'>
    <ul>
    <?php
      foreach($products as $product)
      {
          if($product->status == 'closed') echo "<li>" . html::a(sprintf($link, $product->id), $product->name, '', "class='closed'"). "</li>";
      }
    ?>
    </ul>
  </div>
</div>
