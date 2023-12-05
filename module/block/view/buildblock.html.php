<?php
/**
 * The build block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(empty($builds)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter block-builds'>
    <thead>
      <tr>
        <th class='c-id text-center'><?php echo $lang->idAB?></th>
        <th><?php echo $lang->build->name;?></th>
        <?php if($longBlock):?>
        <th><?php echo $lang->build->product;?></th>
        <th><?php echo $lang->build->project;?></th>
        <?php endif;?>
        <th class='c-date'><?php echo $lang->build->date;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($builds as $build):?>
      <?php
      $appid           = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      $buildViewLink   = $this->createLink('build', 'view', "buildID={$build->id}");
      $productViewLink = $this->createLink('product', 'browse', "productID={$build->product}");
      $projectViewLink = $this->createLink('projectbuild', 'browse', "projectID={$build->project}");
      ?>
      <tr <?php echo $appid?>>
        <td class='text-center'><?php echo sprintf('%03d', $build->id);?></td>
        <td title='<?php echo $build->name?>'><?php echo html::a($buildViewLink, $build->name);?></td>
        <?php if($longBlock):?>
        <td title='<?php echo $build->productName?>'><?php echo html::a($build->shadow ? $projectViewLink : $productViewLink, $build->productName);?></td>
        <td title='<?php echo $build->projectName?>'><?php echo html::a($projectViewLink, $build->projectName);?></td>
        <?php endif;?>
        <td><?php echo $build->date?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
