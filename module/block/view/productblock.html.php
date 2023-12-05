<?php
/**
 * The product block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
.block-products.block-sm .c-execution {display: none;}
</style>
<div class="panel-body has-table scrollbar-hover block-products">
  <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter table-fixed'>
    <thead>
      <tr>
        <th class='c-name'><?php echo $lang->product->name;?></th>
        <?php if($longBlock):?>
        <th class='c-name c-execution'><?php echo $lang->product->currentExecution;?></th>
        <?php endif;?>
        <th title='<?php echo $lang->product->plans?>' class='c-num w-120px'><?php echo $lang->product->plans;?></th>
        <th title='<?php echo $lang->product->releases?>' class='c-num w-100px'><?php echo $lang->product->releases;?></th>
        <th title='<?php echo $lang->product->activeStoriesTitle?>' class='c-num w-120px'><?php echo $lang->product->activeStories;?></th>
        <th title='<?php echo $lang->product->unResolvedBugsTitle?>' class='c-num w-100px'><?php echo $lang->product->unResolvedBugs;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($productStats as $product):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : "";
      $viewLink = $this->createLink('product', 'browse', 'productID=' . $product->id);
      ?>
      <tr class='text-center' <?php echo $appid?>>
        <td class='c-name text-left' title='<?php echo $product->name?>'><?php echo html::a($viewLink, $product->name);?></td>
        <?php if($longBlock):?>
        <?php $executionName = zget($executions, $product->id, '');?>
        <td class='c-name c-execution text-left' title='<?php echo $executionName;?>'><?php echo $executionName;?></td>
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
