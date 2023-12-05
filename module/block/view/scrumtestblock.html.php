<?php
/**
 * The testtask block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(empty($testtasks)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed-head tablesorter block-testtask table-fixed'>
    <thead>
      <tr class='text-center'>
        <th class='text-left'><?php echo $lang->testtask->name;?></th>
        <?php if($longBlock and $project->hasProduct):?>
        <th class='text-left'><?php echo $lang->testtask->product;?></th>
        <?php endif;?>
        <?php if($longBlock):?>
        <th class='text-left'><?php echo $lang->testtask->project?></th>
        <?php endif;?>
        <th class='text-left'><?php echo $lang->testtask->build;?></th>
        <th class='w-date'><?php echo $lang->testtask->status;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($testtasks as $testtask):?>
      <?php
      $appid            = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      $productViewLink  = $this->createLink('product', 'browse', "productID={$testtask->product}");
      $projectViewLink  = $this->createLink('project', 'view', "projectID={$testtask->project}");
      $testtaskViewLink = $this->createLink('testtask', 'view', "testtaskID={$testtask->id}");
      $buildViewLink    = $this->createLink('build', 'view', "buildID={$testtask->build}");
      ?>
      <tr class='text-center' <?php echo $appid?>>
        <td class='text-left text-ellipsis' title='<?php echo $testtask->name?>'><?php echo html::a($testtaskViewLink, $testtask->name);?></td>
        <?php if($longBlock and $project->hasProduct):?>
        <td class='text-left text-ellipsis' title='<?php echo $testtask->productName?>'><?php echo html::a($productViewLink, $testtask->productName);?></td>
        <?php endif;?>
        <?php if($longBlock):?>
        <td class='text-left text-ellipsis' title='<?php echo $testtask->projectName?>'><?php echo html::a($projectViewLink, $testtask->projectName);?></td>
        <?php endif;?>
        <td class='text-left text-ellipsis' title='<?php echo $testtask->buildName?>'><?php echo html::a($buildViewLink, $testtask->buildName);?></td>
        <td><?php echo zget($lang->testtask->statusList, $testtask->status)?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
