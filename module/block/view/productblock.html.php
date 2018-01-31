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
  <table class='table table-borderless table-hover table-fixed-head block-products'>
    <thead>
      <tr>
        <th class='c-name'><?php echo $lang->product->name;?></th>
        <th class='c-plans'><?php echo $lang->product->plans;?></th>
        <th class="c-publishs"><?php echo $lang->product->releases;?></th>
        <?php if($longBlock):?>
        <th class="c-project"><?php echo $lang->product->currentProject;?></th>
        <?php endif;?>
        <th class="c-stories" title='<?php echo $lang->story->common;?>'><?php echo $lang->story->statusList['active'];?></th>
        <th class="c-bugs" title='<?php echo $lang->bug->common;?>'><?php echo $lang->bug->unResolved;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($productStats as $product):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : "";
      $viewLink = $this->createLink('product', 'browse', 'productID=' . $product->id);
      ?>
      <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
        <td class='c-name' title='<?php echo $product->name?>'><?php echo $product->name?></td>
        <td class="c-plans"><?php echo $product->plans?></td>
        <td class="c-publishs"><?php echo $product->releases?></td>
        <?php if($longBlock):?>
        <td class='c-project'><?php echo zget($projects, $product->id, '');?></td>
        <?php endif;?>
        <td class="c-stories"><?php echo $product->stories['active']?></td>
        <td class="c-bugs"><?php echo $product->unResolved?></td>
      </tr> 
      <?php endforeach;?>
    </tbody>
  </table>
</div>
