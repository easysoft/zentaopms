<?php
/**
 * The import release view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
    <table class='table table-fixed' id='releaseList'>
      <thead>
        <tr>
          <th class="c-id">
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-name'><?php echo $lang->release->name;?></th>
          <?php if($config->systemMode == 'new'):?>
          <th class='c-name'><?php echo $lang->release->project;?></th>
          <?php endif;?>
          <th class='c-name'><?php echo $lang->release->build;?></th>
          <th class='c-date'><?php echo $lang->release->date;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($releases2Imported as $release):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='releases[]' value='<?php echo $release->id;?>'/>
              <label></label>
            </div>
            <?php printf('%03d', $release->id);?>
          </td>
          <?php if(common::hasPriv('release', 'view')):?>
          <td title='<?php echo $release->name;?>'>
            <a href='javascript:void(0);' onclick="locateView('release', <?php echo $release->id;?>)"><?php echo $release->name;?></a>
          </td>
          <?php else:?>
          <td title='<?php echo $release->name;?>'><?php echo $release->name;?></td>
          <?php endif;?>
          <?php if($config->systemMode == 'new'):?>
          <td title='<?php echo $release->projectName;?>'><?php echo $release->projectName;?></td>
          <?php endif;?>
          <td title='<?php echo $release->buildName;?>'><?php echo $release->buildName;?></td>
          <td title='<?php echo $release->date;?>'><?php echo $release->date;?></td>
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
<style>#product_chosen {width: 45% !important}</style>
<?php include '../../common/view/footer.lite.html.php';?>
