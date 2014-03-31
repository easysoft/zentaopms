<div class='side' id='treebox'>
  <button class='side-handle' data-id='storyTree'><i class='icon-caret-left'></i></button>
  <header class='nobr'><?php echo html::icon($lang->icons['product']);?> <strong><?php echo $productName;?></strong></header>
  <div class='side-body'>
    <?php echo $moduleTree;?>
    <div class='text-right'>
      <?php common::printLink('tree', 'browse', "productID=$productID&view=bug", $lang->tree->manage);?>
    </div>
  </div>
</div>
<div class='main'>
  <form method='post' action='<?php echo inLink('batchEdit', "productID=$productID");?>'>
    <table class='table table-condensed table-hover table-striped table-borderless tablesorter table-fixed' id='bugList'>
      <?php $vars = "productID=$productID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <thead>
      <tr>
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
        <td class='text-center'><nobr>
          <?php
          $params = "bugID=$bug->id";
          common::printIcon('bug', 'resolve', $params, $bug, 'list', '', '', 'iframe', true);
          common::printIcon('bug', 'close',   $params, $bug, 'list', '', '', 'iframe text-danger', true);
          common::printIcon('bug', 'edit',    $params, $bug, 'list');
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
            <div class='table-actions clearfix'>
              <div class='btn-group'>
              <?php echo html::selectAll() . html::selectReverse();?>
              </div>
              <div class='btn-group dropup'>
                <?php 
                $actionLink = $this->createLink('bug', 'batchEdit', "productID=$productID");
                $misc       = common::hasPriv('bug', 'batchEdit') ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";
                echo html::commonButton($lang->edit, $misc);
                ?>
                <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
                <ul class='dropdown-menu'>
                <?php 
                $class = "class='disabled'";

                $actionLink = $this->createLink('bug', 'batchConfirm');
                $misc = common::hasPriv('bug', 'batchConfirm') ? "onclick=\"setFormAction('$actionLink','hiddenwin')\"" : "class='disabled'";
                echo "<li>" . html::a('#', $lang->bug->confirmBug, '', $misc) . "</li>";

                $actionLink = $this->createLink('bug', 'batchClose');
                $misc = common::hasPriv('bug', 'batchClose') ? "onclick=\"setFormAction('$actionLink','hiddenwin')\"" : "class='disabled'";
                echo "<li>" . html::a('#', $lang->bug->close, '', $misc) . "</li>";

                $misc = common::hasPriv('bug', 'batchResolve') ? "id='resolveItem'" : $class;
                echo "<li class='dropdown-submenu'>" . html::a('#', $lang->bug->resolve,  '', $misc);
                echo "<ul class='dropdown-menu'>";
                unset($lang->bug->resolutionList['']);
                unset($lang->bug->resolutionList['duplicate']);
                foreach($lang->bug->resolutionList as $key => $resolution)
                {
                    $actionLink = $this->createLink('bug', 'batchResolve', "resolution=$key");
                    if($key == 'fixed')
                    {
                        echo "<li class='dropdown-submenu'>";
                        echo html::a('#', $resolution, '', "id='fixedItem'");
                        echo "<ul class='dropdown-menu'>";
                        unset($builds['']);
                        foreach($builds as $key => $build)
                        {
                            $actionLink = $this->createLink('bug', 'batchResolve', "resolution=fixed&resolvedBuild=$key");
                            echo "<li>";
                            echo html::a('#', $build, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"");
                            echo "</li>";
                        }
                        echo '</ul></li>';
                    }
                    else
                    {
                        echo "<li>";
                        echo html::a('#', $resolution, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"");
                        echo "</li>";
                    }
                }
                echo "</ul></li>";
                ?>
                </ul>
              </div>
            </div>
            <?php endif?>
            <div class='text-right'><?php $pager->show();?></div>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
