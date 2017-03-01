<?php
/**
 * The product block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<table class='table tablesorter table-data table-hover table-fixed table-striped block-product'>
  <thead>
    <tr class='text-center'>
      <th class='text-left'><?php echo $lang->product->name;?></th>
      <th width='65' title='<?php echo $lang->story->common;?>'><?php echo $lang->story->statusList['active'];?></th>
      <th width='65' title='<?php echo $lang->story->common;?>'><?php echo $lang->story->statusList['changed'];?></th>
      <th width='65' title='<?php echo $lang->story->common;?>'><?php echo $lang->story->statusList['draft'];?></th>
      <th width='65'><?php echo $lang->product->plans;?></th>
      <th width='65'><?php echo $lang->product->releases;?></th>
      <th width='65' title='<?php echo $lang->bug->common;?>'><?php echo $lang->product->bugs;?></th>
      <th width='65' title='<?php echo $lang->bug->common;?>'><?php echo $lang->bug->unResolved;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($productStats as $product):?>
    <?php
    $appid    = isset($_GET['entry']) ? "class='app-btn text-center' data-id='{$this->get->entry}'" : "class='text-center'";
    $viewLink = $this->createLink('product', 'browse', 'productID=' . $product->id);
    ?>
    <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
      <td class='text-left' title='<?php echo $product->name?>'><?php echo $product->name?></td>
      <td><?php echo $product->stories['active']?></td>
      <td><?php echo $product->stories['changed']?></td>
      <td><?php echo $product->stories['draft']?></td>
      <td><?php echo $product->plans?></td>
      <td><?php echo $product->releases?></td>
      <td><?php echo $product->bugs?></td>
      <td><?php echo $product->unResolved?></td>
    </tr> 
    <?php endforeach;?>
  </tbody>
</table>
<script>
if(typeof(dataTable) == 'function')$('.block-product').dataTable();
</script>
