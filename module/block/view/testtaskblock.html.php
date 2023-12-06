<?php
/**
 * The testtask block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php if(empty($testtasks)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed-head tablesorter block-testtask table-fixed'>
    <thead>
      <tr class='text-center'>
        <?php if($longBlock):?>
        <th class='c-id'><?php echo $lang->idAB?></th>
        <th class='text-left'><?php echo $lang->testtask->product;?></th>
        <?php endif;?>
        <th class='text-left'><?php echo $lang->testtask->name;?></th>
        <?php if($longBlock):?>
        <th class='text-left'><?php echo $lang->testtask->execution . '/' . $lang->testtask->build;?></th>
        <?php endif;?>
        <th class='c-date'><?php echo $lang->testtask->begin;?></th>
        <th class='c-date'><?php echo $lang->testtask->end;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($testtasks as $testtask):?>
      <?php
      $appid            = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      $productViewLink  = $this->createLink('product', 'browse', "productID={$testtask->product}");
      $buildViewLink    = $this->createLink('build', 'view', "buildID={$testtask->build}");
      $testtaskViewLink = $this->createLink('testtask', 'view', "testtaskID={$testtask->id}");

      if($testtask->shadow)
      {
          $testtask->productName = zget($projects, $testtask->project);
          $productViewLink = $this->createLink('projectstory', 'story', "projectID={$testtask->project}&productID={$testtask->product}");
      }
      ?>
      <tr class='text-center' <?php echo $appid?>>
        <?php if($longBlock):?>
        <td><?php echo sprintf('%03d', $testtask->id);?></td>
        <td class='text-left' title='<?php echo $testtask->productName?>'><?php echo html::a($productViewLink, $testtask->productName);?></td>
        <?php endif;?>
        <td class='text-left' title='<?php echo $testtask->name?>'><?php echo html::a($testtaskViewLink, $testtask->name);?></td>
        <?php if($longBlock):?>
        <td class='text-left' title='<?php echo $testtask->projectName . '/' . $testtask->buildName?>'><?php echo html::a($buildViewLink, $testtask->projectName . '/' . $testtask->buildName);?></td>
        <?php endif;?>
        <td><?php echo $testtask->begin?></td>
        <td><?php echo $testtask->end?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
