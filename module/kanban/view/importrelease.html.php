<?php
/**
 * The import release view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie<xieqiyu@cnezsoft.com>
 * @package     kanban
 * @version     $Id: importrelease.html.php 5090 2022-01-19 14:19:24Z xieqiyu@cnezsoft.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('kanbanID', $kanbanID);?>
<?php js::set('regionID', $regionID);?>
<?php js::set('groupID', $groupID);?>
<?php js::set('columnID', $columnID);?>
<?php js::set('methodName', $this->app->rawMethod);?>
<?php if(count($releases2Imported) <= 3):?>
<style>#importReleaseForm, .table-empty-tip {margin-bottom: 120px}</style>
<?php endif;?>
<div id='mainContent' class='main-content importModal'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanban->importAB . $lang->kanban->importRelease;?></h2>
    </div>
  </div>
  <div class='input-group space'>
    <span class='input-group-addon'><?php echo $lang->kanban->selectedProduct;?></span>
    <?php echo html::select('product', $products, $selectedProductID, "onchange='reloadObjectList(this.value)' class='form-control chosen' data-drop_direction='down'");?>
    <span class='input-group-addon'><?php echo $lang->kanban->selectedLane;?></span>
    <?php echo html::select('lane', $lanePairs, '', "onchange='setTargetLane(this.value)' class='form-control chosen' data-drop_direction='down'");?>
  </div>
  <?php if($releases2Imported):?>
  <form class='main-table' method='post' data-ride='table' target='hiddenwin' id='importReleaseForm'>
    <table class='table table-bordered table-fixed' id='releaseList'>
      <thead>
        <tr>
          <th class="c-id">
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-name'><?php echo $lang->release->name;?></th>
          <th class='c-name'><?php echo $lang->release->includedBuild;?></th>
          <th class='c-name'><?php echo $lang->release->relatedProject;?></th>
          <th class='c-date'><?php echo $lang->release->date;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($releases2Imported as $release):?>
        <?php $i = 1;?>
        <?php $rowspan = !count($release->builds) ? 1 : count($release->builds);?>
        <tr>
          <td rowspan="<?php echo $rowspan;?>" class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='releases[]' value='<?php echo $release->id;?>'/>
              <label></label>
            </div>
            <?php printf('%03d', $release->id);?>
          </td>
          <td rowspan="<?php echo $rowspan;?>" title='<?php echo $release->name;?>'>
            <?php if(common::hasPriv('release', 'view')):?>
            <a href='javascript:void(0);' onclick="locateView('release', <?php echo $release->id;?>)"><?php echo $release->name;?></a>
            <?php else:?>
            <?php echo $release->name;?>
            <?php endif;?>
          </td>
          <?php if(count($release->builds) == 0):?>
          <td></td>
          <td></td>
          <?php else:?>
          <?php foreach($release->builds as $build):?>
          <?php if($i > 1):?>
        <tr>
          <?php endif;?>
          <td title='<?php echo $build->name;?>'><?php echo $build->name;?></td>
          <td title='<?php echo $build->projectName;?>'><?php echo $build->projectName;?></td>
          <?php if($i == 1):?>
          <td rowspan="<?php echo $rowspan;?>" title='<?php echo $release->date;?>'><?php echo $release->date;?></td>
        </tr>
        <?php else:?>
        </tr>
        <?php endif;?>
        <?php $i++;?>
        <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>
        <tr><?php echo html::hidden('targetLane', key($lanePairs));?></tr>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar show-always"><?php echo html::submitButton($lang->kanban->importAB, '', 'btn btn-default');?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php else:?>
  <div class='table-empty-tip'><?php echo $lang->noData;?></div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
