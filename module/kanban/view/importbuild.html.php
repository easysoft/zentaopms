<?php
/**
 * The import build view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Qiyu Xie<xieqiyu@cnezsoft.com>
 * @package     kanban
 * @version     $Id: importbuild.html.php 5090 2022-01-19 14:19:24Z xieqiyu@cnezsoft.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('kanbanID', $kanbanID);?>
<?php js::set('regionID', $regionID);?>
<?php js::set('groupID', $groupID);?>
<?php js::set('columnID', $columnID);?>
<?php js::set('methodName', $this->app->rawMethod);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanban->importBuild;?></h2>
    </div>
  </div>
  <div class='table-row' style="padding: 10px">
    <div class='table-col w-150px text-center'><h4><?php echo $lang->kanban->selectedProject;?></h4></div>
    <div class='table-col'><?php echo html::select('project', $projects, $selectedProjectID, "onchange='reloadObjectList(this.value)' class='form-control chosen'");?></div>
  </div>
  <div class='table-row' style="padding: 10px">
    <div class='table-col w-150px text-center'><h4><?php echo $lang->kanban->selectedLane;?></h4></div>
    <div class='table-col'><?php echo html::select('lane', $lanePairs, '', "onchange='setTargetLane(this.value)' class='form-control chosen'");?></div>
  </div>
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
          <th class='c-name'><?php echo $lang->executionCommon;?></th>
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
          <td title='<?php echo $build->name;?>'><?php common::printLink('build', 'view', "buildID=$build->id", $build->name, '', "class='iframe'", true, true);?></td>
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
    <?php if($builds2Imported):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar show-always"><?php echo html::submitButton($lang->kanban->importBuild, '', 'btn btn-default');?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
