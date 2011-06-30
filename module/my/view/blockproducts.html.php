<div class='block'>
<?php if(empty($productStats['products'])):?>
<table class='table-1 a-center' height='100%'>
  <caption><?php echo $lang->my->home->products;?></caption>
  <tr>
    <td valign='middle'>
    <?php 
    $productLink = $this->createLink('product', 'create');
    printf($lang->my->home->noProductsTip, $productLink);
    ?>
    </td>
  </tr>
</table>
<?php else:?>
  <table class='table-1 tab-box' id='productbox' height='100%'>
    <tr>
      <td valign='top'>
      <?php foreach($productStats['charts'] as $productID => $chart):?>
      <h2 class='tab-title' ><?php echo $lang->my->home->products . $lang->colon . $productStats['products'][$productID]->name;?></h2>
      <div class='pane a-center'>
      <?php
      echo $chart;
      echo html::a($this->createLink('product', 'browse', "productID=$productID"), $lang->my->home->productHome);
      ?>
      </div>
      <?php endforeach;?>
      </td>
    </tr>
  </table>
</div>
<?php endif;?>
</div>
