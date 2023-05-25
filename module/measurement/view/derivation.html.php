<div id='mainContent' class='main-row'>
  <div class="main-col">
    <div class="main-table">
      <table class="table table-fixed" id='measList'>
        <thead>
          <tr class='text-left'>
            <th class='c-id text-center'><?php echo $lang->measurement->id;?></th>
            <th><?php echo $lang->measurement->name;?></th>
            <th class='w-150px'><?php echo $lang->measurement->purpose;?></th>
            <th class='w-150px'><?php echo $lang->measurement->aim;?></th>
            <th class='w-80px text-center'><?php echo $lang->measurement->collectType;?></th>
            <th class='w-120px text-center'><?php echo $lang->measurement->analyst;?></th>
            <th class='w-200px'><?php echo $lang->measurement->analysisMethod;?></th>
            <th class='w-200px'><?php echo $lang->measurement->noticeScope;?></th>
            <th class='w-120px text-center'><?php echo $lang->measurement->options;?></th>
          </tr>
        </thead>
        <tbody id="measurementList">
          <?php foreach ($measurementList as $key => $measurement):?>
          <tr class='text-left'>
            <td class="c-id text-center"><?php echo $measurement->id;?></td>
            <td><?php echo $measurement->name;?></td>
            <td><?php echo $measurement->purpose;?></td>
            <td title="<?php echo $measurement->aim;?>"><?php echo $measurement->aim;?></td>
            <td class='text-center'><?php echo zget($lang->measurement->collectTypeList, $measurement->collectType);?></td>
            <td class="text-center"><?php echo $measurement->analyst;?></td>
            <td title="<?php echo $measurement->analysisMethod;?>"><?php echo $measurement->analysisMethod;?></td>
            <td><?php echo $measurement->scope;?></td>
            <td class="text-center c-actions">
              <?php common::printIcon('measurement', 'editDerivation', "measurementID=$measurement->id", $measurement, 'list', 'edit');?>
              <?php
              if(common::hasPriv('measurement', 'delete'))
              {
                  $deleteURL = helper::createLink('measurement', 'delete', "type=derivation&measurementID=$measurement->id&confirm=yes");
                  echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"measList\",confirmDelete)", '<i class="icon-common-delete icon-trash"></i>', '', "title='{$lang->measurement->delete}' class='btn'");
              }
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </div>
  </div>
</div>
