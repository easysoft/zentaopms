<?php if(!$products) echo sprintf($lang->product->noMatchedProduct, $keywords);?>
<ul>
<?php
foreach($products as $product)
{
    echo "<li>" . html::a(sprintf($link, $product->id), $product->name). "</li>";
}
?>
</ul>
