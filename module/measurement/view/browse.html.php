<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<?php js::set('confirmDelete', $lang->measurement->confirmDelete);?>
<?php js::set('orderBy', $orderBy);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
  <?php include './menu.html.php';?>
  <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->measurement->byQuery;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('measurement', 'createBasic', "", "<i class='icon icon-plus'></i>" . $lang->measurement->create, '', "class='btn btn-primary '", '');?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='measurement'></div>
    <form class="main-table" data-ride='table' method='post' action='<?php echo $this->createLink('measurement', 'batchEdit');?>'>
      <table class="table table-fixed has-sort-head" id='measList'>
        <thead>
          <tr>
            <?php $batchEdit = common::hasPriv('measurement', 'batchEdit');?>
            <?php $vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
            <th class='c-id'>
              <?php if($batchEdit):?>
                <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll;?>">
                  <label></label>
                </div>
              <?php endif;?>
              <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
            </th>
            <th class='w-60px text-left'><?php common::printOrderLink('purpose', $orderBy, $vars, $lang->measurement->purpose);?></th>
            <th class='w-60px text-left'><?php common::printOrderLink('scope', $orderBy, $vars, $lang->measurement->scope);?></th>
            <th class='w-60px text-left'><?php common::printOrderLink('object', $orderBy, $vars, $lang->measurement->object);?></th>
            <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->measurement->name);?></th>
            <th class='w-170px text-left'><?php common::printOrderLink('code', $orderBy, $vars, $lang->measurement->code);?></th>
            <th class='w-80px'><?php common::printOrderLink('unit', $orderBy, $vars, $lang->measurement->unit);?></th>
            <th class=''><?php common::printOrderLink('definition', $orderBy, $vars, $lang->measurement->definition);?></th>
            <th class='c-actions text-center'><?php echo $lang->actions;?></th>
            <th class='w-60px'><?php echo $lang->measurement->order;?></th>
          </tr>
        </thead>
        <tbody class='sortable' id="measurementList">
          <?php foreach ($measurementList as $key => $measurement):?>
          <tr data-id="<?php echo $measurement->id;?>">
            <td class="c-id">
            <?php if($batchEdit):?>
              <?php echo html::checkbox('measurement', array($measurement->id => '')) . html::a($this->createLink('measurement', 'setSQL', "measurementID=$measurement->id"), sprintf('%03d', $measurement->id));?>
            <?php else:?>
            <?php echo sprintf('%03d', $measurement->id);?>
            <?php endif;?>
            </td>
            <td class='text-left'><?php echo  zget($lang->measurement->purposeList, $measurement->purpose);?></td>
            <td class='text-left'><?php echo  zget($lang->measurement->scopeList, $measurement->scope);?></td>
            <td class='text-left'><?php echo  zget($lang->measurement->objectList, $measurement->object);?></td>
            <td class='text-left' title="<?php echo $measurement->name;?>"><?php echo html::a(inlink('setSQL', "measurementID=$measurement->id"), $measurement->name);?></td>
            <td class='text-left'><?php echo html::a(inlink('setSQL', "measurementID=$measurement->id"), $measurement->code);?></td>
            <td class='nowrap' title=<?php echo $measurement->unit;?>><?php echo $measurement->unit;?></td>
            <td class='text-left nowrap' title="<?php echo $measurement->definition;?>"><?php echo $measurement->definition;?></td>
            <td class="text-center c-actions">
              <?php common::printIcon('measurement', 'setSQL', "measurementID=$measurement->id", $measurement, 'list', 'pencil');?>
              <?php common::printIcon('measurement', 'editBasic', "measurementID=$measurement->id", $measurement, 'list', 'edit');?>
              <?php
              if(common::hasPriv('measurement', 'delete'))
              {
                  $deleteURL = helper::createLink('measurement', 'delete', "measurementID=$measurement->id&confirm=yes");
                  echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"measList\",confirmDelete)", '<i class="icon-common-delete icon-trash"></i>', '', "title='{$lang->measurement->delete}' class='btn'");
              }
              ?>
            </td>
            <td class='sort-handler' title='<?php echo $lang->dragAndSort?>'><i class='icon-move'></i></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php if($batchEdit):?>
          <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
          <div class="table-actions btn-toolbar"><?php echo html::submitButton($lang->edit, '', 'btn');?></div>
        <?php endif;?>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
