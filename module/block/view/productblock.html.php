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
<?php if(empty($productStats)): ?>
<div class='empty-tip'><?php common::printLink('product', 'create', '', "<i class='icon-plus'></i> " . $lang->product->create, '', "class='btn btn-primary'") ?></div>
<?php else:?>
<style>
.block-products.block-sm .c-project {display: none;}
</style>
<div class="panel-body has-table scrollbar-hover block-products">
  <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter table-fixed'>
    <thead>
      <tr>
        <th class='c-name'><?php echo $lang->product->name;?></th>
        <?php if($longBlock):?>
        <th class='c-name c-project'><?php echo $lang->product->currentProject;?></th>
        <?php endif;?>
        <th title='<?php echo $lang->product->plans?>' class='c-num'><?php echo $lang->product->plans;?></th>
        <th title='<?php echo $lang->product->releases?>' class='c-num'><?php echo $lang->product->releases;?></th>
        <th title='<?php echo $lang->product->activeStoriesTitle?>' class='c-num <?php echo 'w-90px'?>'><?php echo $lang->product->activeStories;?></th>
        <th title='<?php echo $lang->product->unResolvedBugsTitle?>' class='c-num <?php echo 'w-90px'?>'><?php echo $lang->product->unResolvedBugs;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($productStats as $product):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : "";
      $viewLink = $this->createLink('product', 'browse', 'productID=' . $product->id);
      ?>
      <tr class='text-center' data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
        <td class='c-name text-left' title='<?php echo $product->name?>'><?php echo $product->name?></td>
        <?php if($longBlock):?>
        <?php $projectName = zget($projects, $product->id, '');?>
        <td class='c-name c-project text-left' title='<?php echo $projectName;?>'><?php echo $projectName;?></td>
        <?php endif;?>
        <td class="c-num"><?php echo $product->plans?></td>
        <td class="c-num"><?php echo $product->releases?></td>
        <td class="c-num"><?php echo $product->stories['active']?></td>
        <td class="c-num"><?php echo $product->unResolved?></td>
      </tr> 
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
