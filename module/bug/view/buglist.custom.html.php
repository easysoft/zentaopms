<?php $_GET['onlybody'] = 'no';?>
<table class='table-1 colored tablesorter datatable' id='bugList'>
  <?php $vars = "productID=$productID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
  <thead>
  <tr class='colhead'>
    <?php foreach($customFields as $fieldName):?>
    <th><nobr><?php common::printOrderLink($fieldName, $orderBy, $vars, $lang->bug->$fieldName);?></nobr></th>
    <?php endforeach;?>
    <th class='w-70px {sorter:false}'><nobr><?php echo $lang->actions;?></nobr></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($bugs as $bug):?>
  <?php $bugLink = inlink('view', "bugID=$bug->id");?>
  <tr>
    <?php $i = 0;?>
    <?php foreach($customFields as $fieldName):?>
    <?php $i ++;?>
    <td><nobr>
       <?php if($i == 1):?>
       <input type='checkbox' name='bugIDList[]'  value='<?php echo $bug->id;?>'/> 
       <?php endif;?>
      <?php 
      if(preg_match('/^(id|title)$/i', $fieldName))
      {
          echo html::a($bugLink, $bug->$fieldName);
      }
      elseif(preg_match('/assignedTo|by/i', $fieldName))
      {
          echo $users[$bug->$fieldName];
      }
      elseif(preg_match('/^(severity|pri|resolution|os|type|browse|status|confirmed)$/i', $fieldName))
      {
          $key = $fieldName . 'List';
          $list = $lang->bug->$key;
          echo $list[$bug->$fieldName];
      }
      else
      {
          echo !($bug->$fieldName == '0') ? $bug->$fieldName : '';
      }
      ?>
    </nobr></td>
    <?php endforeach;?>
    <td class='a-center'><nobr>
      <?php
      $params = "bugID=$bug->id";
      common::printIcon('bug', 'resolve', $params, '', 'list');
      common::printIcon('bug', 'close',   $params, '', 'list');
      common::printIcon('bug', 'edit',    $params, '', 'list');
      ?>
      </nobr>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
  <tr>
    <td colspan='<?php echo count($customFields) + 1?>'>
      <?php if(!empty($bugs)):?>
      <div class='f-left'>
        <?php 
        echo html::selectAll() . html::selectReverse(); 
        if(common::hasPriv('bug', 'batchEdit') and $bugs) echo html::submitButton($lang->edit);
       ?>
      </div>
      <?php endif?>
      <div class='f-right'><?php $pager->show();?></div>
    </td>
  </tr>
  </tfoot>
</table>
