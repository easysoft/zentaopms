<?php
/**
 * The product overview block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php if(empty($products)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<div class="panel-body has-table scrollbar-hover">
  <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter block-projects tablesorter'>
    <thead>
      <tr class='text-center'>
        <th class='c-num'><?php echo $lang->block->productName;?></th>
        <th class='c-num'><?php echo $lang->block->totalStory;?></th>
        <th class='c-num'><?php echo $lang->block->totalBug;?></th>
        <th class='c-num'><?php echo $lang->block->totalRelease;?></th>
      </tr>
    </thead>
    <tbody class="text-center">
      <?php $id = 0; ?>
      <?php foreach($products as $id => $name):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : "";
      $viewLink = $this->createLink('product', 'browse', 'productID=' . $id);
      ?>
        <tr class='text-center' <?php echo $appid?>>
          <td class="c-num text-ellipsis" title="<?php echo $name;?>"><?php echo html::a($viewLink, $name);?></td>
          <td class="c-num"><?php echo empty($stories[$id]) ? 0: $stories[$id];?></td>
          <td class="c-num"><?php echo empty($bugs[$id]) ? 0: $bugs[$id];?></td>
          <td class="c-num"><?php echo empty($releases[$id]) ? 0: $releases[$id];?></td>
        </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
