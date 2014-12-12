<?php if(!$products) echo sprintf($lang->product->noMatched, $keywords);?>
<ul>
<?php
foreach($products as $product)
{
    echo "<li>" . html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> " . $product->name, '', "class='$product->status'"). "</li>";
}
?>
</ul>
