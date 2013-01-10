<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side <?php echo $treeClass;?>' id='treebox'>
      <nobr>
      <div class='box-title'><?php echo $productName;?></div>
      <div class='box-content'>
        <?php echo $moduleTree;?>
        <div class='a-right'>
          <?php if(common::hasPriv('tree', 'browse')) echo html::a($this->createLink('tree', 'browse', "productID=$productID&view=bug"), $lang->tree->manage);?>
        </div>
      </div>
      </nobr>
    </td>
    <td class='divider'></td>
    <td>
      <?php $vars = "productID=$productID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <form method='post' action='<?php echo $this->inLink('batchEdit', "from=bugBrowse&productID=$productID&orderBy=$orderBy");?>'>
        <table class='table-1 colored tablesorter datatable'>
          <thead>
          <tr class='colhead'>
            <?php foreach($customFields as $fieldName):?>
            <th><nobr><?php common::printOrderLink($fieldName, $orderBy, $vars, $lang->bug->$fieldName);?></nobr></th>
            <?php endforeach;?>
            <th class='{sorter:false}'><nobr><?php echo $lang->actions;?></nobr></th>
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
            <td><nobr>
              <?php
              $params = "bugID=$bug->id";
              if(!($bug->status == 'active'   and common::printLink('bug', 'resolve', $params, $lang->bug->buttonResolve))) echo $lang->bug->buttonResolve . ' ';
              if(!($bug->status == 'resolved' and common::printLink('bug', 'close', $params, $lang->bug->buttonClose)))     echo $lang->bug->buttonClose . ' ';
              common::printLink('bug', 'edit', $params, $lang->bug->buttonEdit);
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
                if(common::hasPriv('bug', 'batchEdit') and $bugs) echo html::submitButton($lang->bug->batchEdit);
               ?>
              </div>
              <?php endif?>
              <div class='f-right'><?php $pager->show();?></div>
            </td>
          </tr>
          </tfoot>
        </table>
      </form>
    </td>
  </tr>
</table>
