<div class='block'>
<?php if(empty($productStats['products'])):?>
<table class='table-1 a-center' height='100%'>
  <caption><?php echo $lang->index->products;?></caption>
  <tr>
    <td valign='middle'>
    <?php 
    $productLink = $this->createLink('product', 'create');
    printf($lang->index->noProductsTip, $productLink);
    ?>
    </td>
  </tr>
</table>
<?php else:?>
  <table class='table-1 tab-box' id='productbox' height='100%'>
    <tr>
      <td valign='top'>
      <?php foreach($productStats['products'] as $id => $product):?>
      <h2 class='tab-title' ><?php echo $lang->index->products . $lang->colon . $product->name;?></h2>
      <div class='pane a-center'>
      <?php
      echo $productStats['charts'][$product->id];
      echo html::a($this->createLink('product', 'browse', "productID=$product->id"), $lang->index->productHome);
      ?>
      </div>
      <?php endforeach;?>
      </td>
    </tr>
  </table>
</div>
<?php endif;?>
</div>
