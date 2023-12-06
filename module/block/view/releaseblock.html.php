<?php
/**
 * The release block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php if(empty($releases)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed tablesorter block-release'>
    <thead>
      <tr>
        <?php if($longBlock):?>
        <th class='c-id-xs text-center'><?php echo $lang->idAB?></th>
        <th><?php echo $lang->release->product;?></th>
        <?php endif;?>
        <th><?php echo $lang->release->name;?></th>
        <?php if($longBlock):?>
        <th><?php echo $lang->release->build;?></th>
        <?php endif;?>
        <th class='c-date text-center'><?php echo $lang->release->date;?></th>
        <th class='text-center w-90px'><?php echo $lang->release->status;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($releases as $release):?>
      <?php
      $appid           = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      $productViewLink = $this->createLink('product', 'browse', "productID={$release->product}");
      $buildViewLink   = $this->createLink('build', 'view', "buildID={$release->build}");
      $releaseViewLink = $this->createLink('release', 'view', "releaseID={$release->id}");
      ?>
      <tr <?php echo $appid?>>
        <?php if($longBlock):?>
        <td class='text-center'><?php echo sprintf('%03d', $release->id);?></td>
        <td title='<?php echo $release->productName?>'><?php echo html::a($productViewLink, $release->productName);?></td>
        <?php endif;?>
        <td title='<?php echo $release->name?>'><?php echo html::a($releaseViewLink, $release->name);?></td>
        <?php if($longBlock):?>
        <td title='<?php echo $release->buildName?>'><?php echo html::a($buildViewLink, $release->buildName);?></td>
        <?php endif;?>
        <td class='text-center'><?php echo $release->date?></td>
        <td class='c-status' title='<?php echo zget($lang->release->statusList, $release->status);?>'>
          <span class="status-release status-<?php echo $release->status?>"><?php echo zget($lang->release->statusList, $release->status);?></span>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
