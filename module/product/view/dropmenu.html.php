<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<script>
function showMore()
{
    $('#moreMenu').removeClass('hidden')
}
</script>

搜索：<input type='text' id='search' value='' onkeyup='searchProduct(this.value, productID, module, method, extra)'>
<div id='searchResult'>
  <div class='f-left'>
    <ul>
    <?php
      foreach($products as $product)
      {
          $isOwner = $product->PO == $this->app->user->account or $product->QD == $this->app->user->account or $product->RD == $this->app->user->account;
          if($product->status == 'normal' and $isOwner) echo "<li>" . html::a(sprintf($link, $product->id), $product->name). "</li>";
      }
      foreach($products as $product)
      {
          $isOwner = $product->PO == $this->app->user->account or $product->QD == $this->app->user->account or $product->RD == $this->app->user->account;
          if($product->status == 'normal' and !$isOwner) echo "<li>" . html::a(sprintf($link, $product->id), $product->name). "</li>";
      }
    ?>
    </ul>
    <div class='a-right'><a id='more' onClick='showMore()'>更多&raquo;</a></div>
  </div>

  <div id='moreMenu' class='hidden f-left'>
    <ul>
    <?php
      foreach($products as $product)
      {
          if($product->status == 'closed') echo "<li>" . html::a(sprintf($link, $product->id), $product->name). "</li>";
      }
    ?>
    </ul>
  </div>
</div>
