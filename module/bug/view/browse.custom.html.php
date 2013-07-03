<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side <?php echo $treeClass;?>' id='treebox'>
      <nobr>
      <div class='box-title'><?php echo $productName;?></div>
      <div class='box-content'>
        <?php echo $moduleTree;?>
        <div class='a-right'><?php common::printLink('tree', 'browse', "productID=$productID&view=bug", $lang->tree->manage);?></div>
      </div>
      </nobr>
    </td>
    <td class='divider'></td>
    <td>
      <form method='post' action='<?php echo $this->inLink('batchEdit', "from=bugBrowse&productID=$productID&orderBy=$orderBy");?>'>
      <?php include './buglist.custom.html.php'?>
      </form>
    </td>
  </tr>
</table>
