<?php
/**
 * The browse view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: browse.html.php 5102 2013-07-12 00:59:54Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/datatable.fix.html.php';
js::set('browseType', $browseType);
js::set('moduleID', $moduleID);
?>
<div id='featurebar'>
  <ul class='nav'>
    <?php
    echo "<li id='unclosedTab'>"      . html::a($this->createLink('bug', 'browse', "productid=$productID&branch=$branch&browseType=unclosed&param=0"),      $lang->bug->unclosed)      . "</li>";
    echo "<li id='allTab'>"           . html::a($this->createLink('bug', 'browse', "productid=$productID&branch=$branch&browseType=all&param=0&orderBy=$orderBy&recTotal=0&recPerPage=200"), $lang->bug->allBugs) . "</li>";
    echo "<li id='assigntomeTab'>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&branch=$branch&browseType=assignToMe&param=0"),    $lang->bug->assignToMe)    . "</li>";
    echo "<li id='openedbymeTab'>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&branch=$branch&browseType=openedByMe&param=0"),    $lang->bug->openedByMe)    . "</li>";
    echo "<li id='resolvedbymeTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&branch=$branch&browseType=resolvedByMe&param=0"),  $lang->bug->resolvedByMe)  . "</li>";
    echo "<li id='unconfirmedTab'>"   . html::a($this->createLink('bug', 'browse', "productid=$productID&branch=$branch&browseType=unconfirmed&param=0"),   $lang->bug->confirmedList[0])  . "</li>";
    echo "<li id='assigntonullTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&branch=$branch&browseType=assignToNull&param=0"),  $lang->bug->assignToNull)  . "</li>";
    echo "<li id='unresolvedTab'>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&branch=$branch&browseType=unResolved&param=0"),    $lang->bug->unResolved)    . "</li>";
    echo "<li id='toclosedTab'>"      . html::a($this->createLink('bug', 'browse', "productid=$productID&branch=$branch&browseType=toClosed&param=0"),      $lang->bug->toClosed)      . "</li>";
    echo "<li id='longlifebugsTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&branch=$branch&browseType=longLifeBugs&param=0"),  $lang->bug->longLifeBugs)  . "</li>";
    echo "<li id='postponedbugsTab'>" . html::a($this->createLink('bug', 'browse', "productid=$productID&branch=$branch&browseType=postponedBugs&param=0"), $lang->bug->postponedBugs) . "</li>";
    echo "<li id='needconfirmTab'>"   . html::a($this->createLink('bug', 'browse', "productid=$productID&branch=$branch&browseType=needconfirm&param=0"), $lang->bug->needConfirm) . "</li>";
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
      common::printIcon('bug', 'batchCreate', "productID=$productID&branch=$branch&projectID=0&moduleID=$moduleID");
      common::printIcon('bug', 'create', "productID=$productID&branch=$branch&extra=moduleID=$moduleID");
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
        <?php echo html::icon($lang->icons['product']);?> <strong><?php echo $branch ? $branches[$branch] : $productName;?></strong>
      </div>
      <div class='panel-body'>
        <?php echo $moduleTree;?>
        <div class='text-right'>
          <?php common::printLink('tree', 'browse', "productID=$productID&view=bug", $lang->tree->manage);?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class='main'>
  <form method='post' id='bugForm'>
    <?php
    $datatableId  = $this->moduleName . $this->methodName;
    $useDatatable = (isset($this->config->datatable->$datatableId->mode) and $this->config->datatable->$datatableId->mode == 'datatable');
    $vars         = "productID=$productID&branch=$branch&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
    include $useDatatable ?  dirname(__FILE__) . '/datatabledata.html.php' : dirname(__FILE__) . '/browsedata.html.php';
    ?>
      <tfoot>
        <tr>
          <?php
          $columns = $this->cookie->windowWidth >= $this->config->wideSize ? 12 : 10;
          if($browseType == 'needconfirm') $columns = 7; 
          ?>
          <td colspan='<?php echo $columns;?>'>
            <?php if(!empty($bugs)):?>
            <div class='table-actions clearfix'>
              <?php if(!$useDatatable) echo html::selectButton();?>
              <div class='btn-group dropup'>
                <?php
                $actionLink = $this->createLink('bug', 'batchEdit', "productID=$productID&branch=$branch");
                $misc       = common::hasPriv('bug', 'batchEdit') ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";
                echo html::commonButton($lang->edit, $misc);
                ?>
                <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
                <ul class='dropdown-menu'>
                  <?php 
                  $class = "class='disabled'";
                  $actionLink = $this->createLink('bug', 'batchConfirm');
                  $misc = common::hasPriv('bug', 'batchConfirm') ? "onclick=\"setFormAction('$actionLink','hiddenwin')\"" : $class;
                  if($misc) echo "<li>" . html::a('javascript:;', $lang->bug->confirmBug, '', $misc) . "</li>";

                  $actionLink = $this->createLink('bug', 'batchClose');
                  $misc = common::hasPriv('bug', 'batchClose') ? "onclick=\"setFormAction('$actionLink','hiddenwin')\"" : $class;
                  if($misc) echo "<li>" . html::a('javascript:;', $lang->bug->close, '', $misc) . "</li>";

                  $misc = common::hasPriv('bug', 'batchResolve') ? "id='resolveItem'" : '';
                  if($misc)
                  {
                      echo "<li class='dropdown-submenu'>" . html::a('javascript:;', $lang->bug->resolve,  '', $misc);
                      echo "<ul class='dropdown-menu'>";
                      unset($lang->bug->resolutionList['']);
                      unset($lang->bug->resolutionList['duplicate']);
                      unset($lang->bug->resolutionList['tostory']);
                      foreach($lang->bug->resolutionList as $key => $resolution)
                      {
                          $actionLink = $this->createLink('bug', 'batchResolve', "resolution=$key");
                          if($key == 'fixed')
                          {
                              $withSearch = count($builds) > 4;
                              echo "<li class='dropdown-submenu'>";
                              echo html::a('javascript:;', $resolution, '', "id='fixedItem'");
                              echo "<ul class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                              unset($builds['']);
                              foreach($builds as $key => $build)
                              {
                                  $actionLink = $this->createLink('bug', 'batchResolve', "resolution=fixed&resolvedBuild=$key");
                                  echo "<li class='option' data-key='$key'>";
                                  echo html::a('javascript:;', $build, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"");
                                  echo "</li>";
                              }
                              if($withSearch) echo "<li class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></li>";
                              echo '</ul></li>';
                          }
                          else
                          {
                              echo '<li>' . html::a('javascript:;', $resolution, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . '</li>';
                          }
                      }
                      echo '</ul></li>';
                  }
                  else
                  {
                      echo "<li>" . html::a('javascript:;', $lang->bug->resolve,  '', $class);
                  }

                  $canBatchAssignTo = common::hasPriv('bug', 'batchAssignTo');
                  if($canBatchAssignTo && count($bugs))
                  {   
                      $withSearch = count($memberPairs) > 10;
                      $actionLink = $this->createLink('bug', 'batchAssignTo', "productID={$productID}&type=product");
                      echo html::select('assignedTo', $memberPairs, '', 'class="hidden"');
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript::', $lang->bug->assignedTo, 'id="assignItem"');
                      echo "<ul class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                      foreach ($memberPairs as $key => $value)
                      {
                          if(empty($key)) continue;
                          echo "<li class='option' data-key='$key'>" . html::a("javascript:$(\".table-actions #assignedTo\").val(\"$key\");setFormAction(\"$actionLink\")", $value, '', '') . '</li>';
                      }
                      if($withSearch) echo "<li class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></li>";
                      echo "</ul>";
                      echo "</li>";
                  }
                  else
                  {
                      echo "<li>" . html::a('javascript:;', $lang->bug->assignedTo,  '', $class);
                  }
                  ?>
                </ul>
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
