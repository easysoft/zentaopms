<?php
/**
 * The import build view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie<xieqiyu@cnezsoft.com>
 * @package     kanban
 * @version     $Id: importbuild.html.php 5090 2022-01-19 14:19:24Z xieqiyu@cnezsoft.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('kanbanID', $kanbanID);?>
<?php js::set('regionID', $regionID);?>
<?php js::set('groupID', $groupID);?>
<?php js::set('columnID', $columnID);?>
<?php js::set('methodName', $this->app->rawMethod);?>
<?php if(count($builds2Imported) <= 3):?>
<style>#importBuildForm, .table-empty-tip {margin-bottom: 120px}</style>
<?php endif;?>
<div id='mainContent' class='main-content importModal'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanban->importAB . $lang->kanban->importBuild;?></h2>
    </div>
  </div>
  <div class='input-group space'>
    <span class='input-group-addon'><?php echo $lang->kanban->selectedProject;?></span>
    <?php echo html::select('project', $projects, $selectedProjectID, "onchange='reloadObjectList(this.value)' class='form-control chosen' data-drop_direction='down'");?>
    <span class='input-group-addon'><?php echo $lang->kanban->selectedLane;?></span>
    <?php echo html::select('lane', $lanePairs, '', "onchange='setTargetLane(this.value)' class='form-control chosen' data-drop_direction='down'");?>
  </div>
  <?php if($builds2Imported):?>
  <form class='main-table' method='post' data-ride='table' target='hiddenwin' id='importBuildForm'>
    <table class='table table-fixed' id='buildList'>
      <thead>
        <tr>
          <th class="c-id">
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-name'><?php echo $lang->build->product;?></th>
          <th class='c-name'><?php echo $lang->build->name;?></th>
          <th class='c-name'><?php echo $lang->execution->common;?></th>
          <th class='c-date'><?php echo $lang->build->date;?></th>
          <th class='c-user'><?php echo $lang->build->builder;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($builds2Imported as $build):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='builds[]' value='<?php echo $build->id;?>'/>
              <label></label>
            </div>
            <?php printf('%03d', $build->id);?>
          </td>
          <td title='<?php echo $build->productName;?>'><?php echo $build->productName;?></td>
          <?php if(common::hasPriv('build', 'view')):?>
          <td title='<?php echo $build->name;?>'>
            <a href='javascript:void(0);' onclick="locateView('build', <?php echo $build->id;?>)"><?php echo $build->name;?></a>
          </td>
          <?php else:?>
          <td title='<?php echo $build->name;?>'><?php echo $build->name;?></td>
          <?php endif;?>
          <td title='<?php echo $build->executionName;?>'><?php echo $build->executionName;?></td>
          <td title='<?php echo $build->date;?>'><?php echo $build->date;?></td>
          <td title='<?php echo zget($users, $build->builder);?>'><?php echo zget($users, $build->builder);?></td>
        </tr>
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
<style>#project_chosen {width: 45% !important}</style>
<?php include '../../common/view/footer.lite.html.php';?>
