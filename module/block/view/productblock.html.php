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
<div class="panel-body has-table">
  <table class='table table-borderless table-hover table-fixed block-product'>
    <thead>
      <tr class='text-center'>
        <th class='text-left'><?php echo $lang->product->name;?></th>
        <?php if(!$longBlock):?>
        <th width='65' title='<?php echo $lang->story->common;?>'><?php echo $lang->story->statusList['changed'];?></th>
        <?php endif;?>
        <?php if($longBlock):?>
        <th width='65'><?php echo $lang->product->plans;?></th>
        <?php endif;?>
        <th width='65'><?php echo $lang->product->releases;?></th>
        <?php if($longBlock):?>
        <th width='150'><?php echo $lang->product->currentProject;?></th>
        <th width='65' title='<?php echo $lang->story->common;?>'><?php echo $lang->story->statusList['active'];?></th>
        <?php endif;?>
        <?php if(!$longBlock):?>
        <th width='80' title='<?php echo $lang->bug->common;?>'><?php echo $lang->product->bugs;?></th>
        <?php endif;?>
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
        <?php if(!$longBlock):?>
        <td><?php echo $product->stories['changed']?></td>
        <?php endif;?>
        <?php if($longBlock):?>
        <td><?php echo $product->plans?></td>
        <?php endif;?>
        <td><?php echo $product->releases?></td>
        <?php if($longBlock):?>
        <td class='text-left'><?php echo zget($projects, $product->id, '');?></td>
        <td><?php echo $product->stories['active']?></td>
        <?php endif;?>
        <?php if(!$longBlock):?>
        <td><?php echo $product->bugs?></td>
        <?php endif;?>
        <td><?php echo $product->unResolved?></td>
      </tr> 
      <?php endforeach;?>
    </tbody>
  </table>
</div>
