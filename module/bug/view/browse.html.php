<?php
/**
 * The browse view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: browse.html.php 5102 2013-07-12 00:59:54Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/treeview.html.php';
js::set('browseType', $browseType);
js::set('moduleID', $moduleID);
?>
<div id='featurebar'>
  <ul class='nav'>
    <?php
    echo "<li id='unclosedTab'>"      . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=unclosed&param=0"),      $lang->bug->unclosed)      . "</li>";
    echo "<li id='allTab'>"           . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=all&param=0&orderBy=$orderBy&recTotal=0&recPerPage=200"), $lang->bug->allBugs) . "</li>";
    echo "<li id='assigntomeTab'>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=assignToMe&param=0"),    $lang->bug->assignToMe)    . "</li>";
    echo "<li id='openedbymeTab'>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=openedByMe&param=0"),    $lang->bug->openedByMe)    . "</li>";
    echo "<li id='resolvedbymeTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=resolvedByMe&param=0"),  $lang->bug->resolvedByMe)  . "</li>";
    echo "<li id='unconfirmedTab'>"   . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=unconfirmed&param=0"),   $lang->bug->confirmedList[0])  . "</li>";
    echo "<li id='assigntonullTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=assignToNull&param=0"),  $lang->bug->assignToNull)  . "</li>";
    echo "<li id='unresolvedTab'>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=unResolved&param=0"),    $lang->bug->unResolved)    . "</li>";
    echo "<li id='longlifebugsTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=longLifeBugs&param=0"),  $lang->bug->longLifeBugs)  . "</li>";
    echo "<li id='postponedbugsTab'>" . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=postponedBugs&param=0"), $lang->bug->postponedBugs) . "</li>";
    echo "<li id='needconfirmTab'>"   . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=needconfirm&param=0"), $lang->bug->needConfirm) . "</li>";
    echo "<li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;{$lang->bug->byQuery}</a></li> ";
    ?>
  </ul>
  <div class='actions'>
    <div class='btn-group'>
      <div class='btn-group'>
        <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
          <i class='icon-download-alt'></i> <?php echo $lang->export ?>
          <span class='caret'></span>
        </button>
        <ul class='dropdown-menu' id='exportActionMenu'>
          <?php 
          $misc = common::hasPriv('bug', 'export') ? "class='export'" : "class=disabled";
          $link = common::hasPriv('bug', 'export') ?  $this->createLink('bug', 'export', "productID=$productID&orderBy=$orderBy") : '#';
          echo "<li>" . html::a($link, $lang->bug->export, '', $misc) . "</li>";
          ?>
        </ul>
      </div>
      <div class='btn-group'>
        <?php common::printIcon('bug', 'report', "productID=$productID&browseType=$browseType&moduleID=$moduleID"); ?>
      </div>
    </div>
    <div class='btn-group'>
      <?php
      common::printIcon('bug', 'batchCreate', "productID=$productID&projectID=0&moduleID=$moduleID");
      common::printIcon('bug', 'create', "productID=$productID&extra=moduleID=$moduleID");
      ?>
    </div>
  </div>
  <div id='querybox' class='<?php if($browseType =='bysearch') echo 'show';?>'></div>
</div>
<div class='side' id='treebox'>
  <a class='side-handle' data-id='bugTree'><i class='icon-caret-left'></i></a>
  <div class='side-body'>
    <div class='panel panel-sm'>
      <div class='panel-heading nobr'>
        <?php echo html::icon($lang->icons['product']);?> <strong><?php echo $productName;?></strong>
      </div>
      <div class='panel-body'>
        <?php echo $moduleTree;?>
        <div class='text-right'>
          <?php common::printLink('tree', 'browse', "productID=$productID&view=bug", $lang->tree->manage);?>
          <?php common::printLink('tree', 'fix',    "root=$productID&type=bug", $lang->tree->fix, 'hiddenwin');?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class='main'>
  <form method='post'>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed' id='bugList'>
      <?php $vars = "productID=$productID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <thead>
      <tr>
        <th class='w-id'>       <?php common::printOrderLink('id',          $orderBy, $vars, $lang->idAB);?></th>
        <th class='w-severity'> <?php common::printOrderLink('severity',    $orderBy, $vars, $lang->bug->severityAB);?></th>
        <th class='w-pri'>      <?php common::printOrderLink('pri',         $orderBy, $vars, $lang->priAB);?></th>

        <th>                    <?php common::printOrderLink('title',       $orderBy, $vars, $lang->bug->title);?></th>

        <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
        <th class='w-80px'><?php common::printOrderLink('status',           $orderBy, $vars, $lang->bug->statusAB);?></th>
        <?php endif;?>

        <?php if($browseType == 'needconfirm'):?>
        <th class='w-200px'><?php common::printOrderLink('story',           $orderBy, $vars, $lang->bug->story);?></th>
        <th class='w-50px'><?php echo $lang->actions;?></th>
        <?php else:?>
        <th class='w-user'><?php common::printOrderLink('openedBy',         $orderBy, $vars, $lang->openedByAB);?></th>

        <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
        <th class='w-date'><?php common::printOrderLink('openedDate',       $orderBy, $vars, $lang->bug->openedDateAB);?></th>
        <?php endif;?>

        <th class='w-user'><?php common::printOrderLink('assignedTo',       $orderBy, $vars, $lang->assignedToAB);?></th>
        <th class='w-user'><?php common::printOrderLink('resolvedBy',       $orderBy, $vars, $lang->bug->resolvedByAB);?></th>
        <th class='w-resolution'><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->resolutionAB);?></th>

        <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
        <th class='w-date'><?php common::printOrderLink('resolvedDate',     $orderBy, $vars, $lang->bug->resolvedDateAB);?></th>
        <?php endif;?>

        <th class='w-140px {sorter:false}'><?php echo $lang->actions;?></th>
        <?php endif;?>
      </tr>
      </thead>
      <tbody>
      <?php foreach($bugs as $bug):?>
      <?php $bugLink = inlink('view', "bugID=$bug->id");?>
      <tr class='text-center'>
        <td class='bug-<?php echo $bug->status;?> strong text-left'>
          <input type='checkbox' name='bugIDList[]'  value='<?php echo $bug->id;?>'/> 
          <?php echo html::a($bugLink, sprintf('%03d', $bug->id));?>
        </td>
        <td><span class='<?php echo 'severity' . zget($lang->bug->severityList, $bug->severity, $bug->severity);?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?></span></td>
        <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bug->pri, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri);?></span></td>

        <?php $class = 'confirm' . $bug->confirmed;?>
        <td class='text-left' title="<?php echo $bug->title?>"><?php echo "<span class='$class'>[{$lang->bug->confirmedList[$bug->confirmed]}] </span>" . html::a($bugLink, $bug->title);?></td>

        <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
        <td class='bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status];?></td>
        <?php endif;?>

        <?php if($browseType == 'needconfirm'):?>
        <td class='text-left' title="<?php echo $bug->storyTitle?>"><?php echo html::a($this->createLink('story', 'view', "stoyID=$bug->story"), $bug->storyTitle, '_blank');?></td>
        <td><?php $lang->bug->confirmStoryChange = $lang->confirm; common::printIcon('bug', 'confirmStoryChange', "bugID=$bug->id", '', 'list', '', 'hiddenwin')?></td>
        <?php else:?>
        <td><?php echo zget($users, $bug->openedBy, $bug->openedBy);?></td>

        <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
        <td><?php echo substr($bug->openedDate, 5, 11)?></td>
        <?php endif;?>

        <td <?php if($bug->assignedTo == $this->app->user->account) echo 'class="red"';?>><?php echo zget($users, $bug->assignedTo, $bug->assignedTo);?></td>
        <td><?php echo zget($users, $bug->resolvedBy, $bug->resolvedBy)?></td>
        <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>

        <?php if($this->cookie->windowWidth >= $this->config->wideSize):?>
        <td><?php echo substr($bug->resolvedDate, 5, 11)?></td>
        <?php endif;?>

        <td class='text-right'>
          <?php
          $params = "bugID=$bug->id";
          common::printIcon('bug', 'confirmBug', $params, $bug, 'list', 'search', '', 'iframe', true);
          common::printIcon('bug', 'assignTo',   $params, '',   'list', '', '', 'iframe', true);
          common::printIcon('bug', 'resolve',    $params, $bug, 'list', '', '', 'iframe', true);
          common::printIcon('bug', 'close',      $params, $bug, 'list', '', '', 'iframe', true);
          common::printIcon('bug', 'edit',       $params, $bug, 'list');
          common::printIcon('bug', 'create',     "product=$bug->product&extra=bugID=$bug->id", $bug, 'list', 'copy');
          ?>
        </td>
        <?php endif;?>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <?php
          $columns = $this->cookie->windowWidth >= $this->config->wideSize ? 12 : 9;
          if($browseType == 'needconfirm') $columns = $this->cookie->windowWidth >= $this->config->wideSize ? 7 : 6; 
          ?>
          <td colspan='<?php echo $columns;?>'>
            <?php if(!empty($bugs)):?>
            <div class='table-actions clearfix'>
              <div class='btn-group'>
              <?php echo html::selectButton();?>
              </div>
              <div class='btn-group dropup'>
                <?php
                $actionLink = $this->createLink('bug', 'batchEdit', "productID=$productID");
                $misc       = common::hasPriv('bug', 'batchEdit') ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";
                echo html::commonButton($lang->edit, $misc);
                ?>
                <?php $dropdown = (common::hasPriv('bug', 'batchConfirm') and common::hasPriv('bug', 'batchClose') and common::hasPriv('bug', 'batchResolve') and common::hasPriv('bug', 'batchAssignTo'));?>
                <?php if($dropdown):?>
                <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
                <ul class='dropdown-menu'>
                  <?php 
                  $actionLink = $this->createLink('bug', 'batchConfirm');
                  $misc = common::hasPriv('bug', 'batchConfirm') ? "onclick=\"setFormAction('$actionLink','hiddenwin')\"" : "";
                  if($misc) echo "<li>" . html::a('#', $lang->bug->confirmBug, '', $misc) . "</li>";

                  $actionLink = $this->createLink('bug', 'batchClose');
                  $misc = common::hasPriv('bug', 'batchClose') ? "onclick=\"setFormAction('$actionLink','hiddenwin')\"" : "";
                  if($misc) echo "<li>" . html::a('#', $lang->bug->close, '', $misc) . "</li>";

                  $misc = common::hasPriv('bug', 'batchResolve') ? "id='resolveItem'" : '';
                  if($misc)
                  {
                      echo "<li class='dropdown-submenu'>" . html::a('#', $lang->bug->resolve,  '', $misc);
                      echo "<ul class='dropdown-menu'>";
                      unset($lang->bug->resolutionList['']);
                      unset($lang->bug->resolutionList['duplicate']);
                      unset($lang->bug->resolutionList['tostory']);
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
                              echo '<li>' . html::a('#', $resolution, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . '</li>';
                          }
                      }
                      echo '</ul></li>';
                  }
                  $canBatchAssignTo = common::hasPriv('bug', 'batchAssignTo');
                  if($canBatchAssignTo && count($bugs))
                  {   
                      $withSearch = count($memberPairs) > 10;
                      $actionLink = $this->createLink('bug', 'batchAssignTo', "productID={$productID}&type=product");
                      echo html::select('assignedTo', $memberPairs, '', 'class="hidden"');
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript::', $lang->bug->assignedTo, 'id="assignItem"');
                      echo "<ul class='dropdown-menu assign-menu" . ($withSearch ? ' with-search':'') . "'>";
                      foreach ($memberPairs as $key => $value)
                      {
                          if(empty($key)) continue;
                          echo "<li class='option' data-key='$key'>" . html::a("javascript:$(\"#assignedTo\").val(\"$key\");setFormAction(\"$actionLink\")", $value, '', '') . '</li>';
                      }
                      if($withSearch) echo "<li class='assign-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></li>";
                      echo "</ul>";
                      echo "</li>";
                  }
                  ?>
                </ul>
                <?php endif;?>
              </div>
            </div>
            <?php endif;?>
            <div class='text-right'><?php $pager->show();?></div>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>

<?php include '../../common/view/footer.html.php';?>
