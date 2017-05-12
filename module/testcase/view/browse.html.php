<?php
/**
 * The browse view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: browse.html.php 5108 2013-07-12 01:59:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/datepicker.html.php';
include '../../common/view/datatable.fix.html.php';
include './caseheader.html.php';
js::set('browseType',     $browseType);
js::set('caseBrowseType', ($browseType == 'bymodule' and $this->session->caseBrowseType == 'bysearch') ? 'all' : $this->session->caseBrowseType);
js::set('moduleID'  ,     $moduleID);
js::set('confirmDelete',  $lang->testcase->confirmDelete);
js::set('batchDelete',    $lang->testcase->confirmBatchDelete);
?>
<div class='side' id='treebox'>
  <a class='side-handle' data-id='testcaseTree'><i class='icon-caret-left'></i></a>
  <div class='side-body'>
    <div class='panel panel-sm'>
      <div class='panel-heading nobr'><?php echo html::icon($lang->icons['product']);?> <strong><?php echo $branch ? $branches[$branch] : $productName;?></strong></div>
      <div class='panel-body'>
        <?php echo $moduleTree;?>
        <div class='text-right'>
          <?php common::printLink('tree', 'browse', "productID=$productID&view=case", $lang->tree->manage);?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class='main'>
  <script>setTreeBox();</script>
  <form id='batchForm' method='post'>
    <?php
    $datatableId  = $this->moduleName . ucfirst($this->methodName);
    $useDatatable = (isset($this->config->datatable->$datatableId->mode) and $this->config->datatable->$datatableId->mode == 'datatable');
    $file2Include = $useDatatable ? dirname(__FILE__) . '/datatabledata.html.php' : dirname(__FILE__) . '/browsedata.html.php';
    $vars         = "productID=$productID&branch=$branch&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
    include $file2Include;
    ?>
      <tfoot>
        <tr>
          <?php $mergeColums = $browseType == 'needconfirm' ? 5 : 13;?>
          <td colspan='<?php echo $mergeColums?>'>
            <?php if($cases):?>
            <div class='table-actions clearfix'>
              <?php echo html::selectButton();?>
              <div class='btn-group dropup'>
                <?php
                $class = "class='disabled'";

                $actionLink = $this->createLink('testcase', 'batchEdit', "productID=$productID&branch=$branch");
                $misc       = common::hasPriv('testcase', 'batchEdit') ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";
                echo html::commonButton($lang->edit, $misc);
                ?>
                <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
                <ul class='dropdown-menu' id='moreActionMenu'>
                  <?php 
                  $actionLink = $this->createLink('testcase', 'batchDelete', "productID=$productID");
                  $misc = common::hasPriv('testcase', 'batchDelete') ? "onclick=\"confirmBatchDelete('$actionLink')\"" : $class;
                  echo "<li>" . html::a('#', $lang->delete, '', $misc) . "</li>";

                  if(common::hasPriv('testcase', 'batchReview') and ($config->testcase->needReview or !empty($config->testcase->forceReview)))
                  {
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->testcase->review, '', "id='reviewItem'");
                      echo "<ul class='dropdown-menu'>";
                      unset($lang->testcase->reviewResultList['']);
                      foreach($lang->testcase->reviewResultList as $key => $result)
                      {
                          $actionLink = $this->createLink('testcase', 'batchReview', "result=$key");
                          echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . '</li>';
                      }
                      echo '</ul></li>';
                  }
                  elseif($config->testcase->needReview or !empty($config->testcase->forceReview))
                  {
                      echo '<li>' . html::a('javascript:;', $lang->testcase->review,  '', $class) . '</li>';
                  }

                  if(common::hasPriv('testcase', 'batchConfirmStoryChange'))
                  {
                      $actionLink = $this->createLink('testcase', 'batchConfirmStoryChange', "productID=$productID");
                      $misc = common::hasPriv('testcase', 'batchConfirmStoryChange') ? "onclick=\"setFormAction('$actionLink')\"" : $class;
                      echo "<li>" . html::a('#', $lang->testcase->confirmStoryChange, '', $misc) . "</li>";
                  }
                  else
                  {
                      echo '<li>' . html::a('javascript:;', $lang->testcase->batchConfirmStoryChange,  '', $class) . '</li>';
                  }


                  $actionLink = $this->createLink('testtask', 'batchRun', "productID=$productID&orderBy=$orderBy");
                  $misc = common::hasPriv('testtask', 'batchRun') ? "onclick=\"setFormAction('$actionLink')\"" : $class;
                  echo "<li>" . html::a('#', $lang->testtask->runCase, '', $misc) . "</li>";

                  if(common::hasPriv('testcase', 'batchChangeModule'))
                  {
                      $withSearch = count($modules) > 8;
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->testcase->moduleAB, '', "id='moduleItem'");
                      echo "<div class='dropdown-menu" . ($withSearch ? ' with-search' : '') . "'>";
                      echo '<ul class="dropdown-list">';
                      foreach($modules as $moduleId => $module)
                      {
                          $actionLink = $this->createLink('testcase', 'batchChangeModule', "moduleID=$moduleId");
                          echo "<li class='option' data-key='$moduleID'>" . html::a('#', $module, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . "</li>";
                      }
                      echo '</ul>';
                      if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                      echo '</div></li>';
                  }
                  else
                  {
                      echo '<li>' . html::a('javascript:;', $lang->testcase->moduleAB, '', $class) . '</li>';
                  }

                  if(common::hasPriv('testcase', 'batchCaseTypeChange'))
                  {
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->testcase->type, '', "id='typeChangeItem'");
                      echo "<ul class='dropdown-menu'>";
                      unset($lang->testcase->typeList['']);
                      foreach($lang->testcase->typeList as $key => $result)
                      {
                          $actionLink = $this->createLink('testcase', 'batchCaseTypeChange', "result=$key");
                          echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . '</li>';
                      }
                      echo '</ul></li>';
                  }
                  else
                  {
                      echo '<li>' . html::a('javascript:;', $lang->testcase->type,  '', $class) . '</li>';
                  }

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
<script>
$('#module' + moduleID).addClass('active'); 
$('#' + caseBrowseType + 'Tab').addClass('active');
<?php if($browseType == 'bysearch'):?>
$shortcut = $('#QUERY<?php echo (int)$param;?>Tab');
if($shortcut.size() > 0)
{
    $shortcut.addClass('active');
    $('#bysearchTab').removeClass('active');
    $('#querybox').removeClass('show');
}
<?php endif;?>
</script>
<?php include '../../common/view/footer.html.php';?>
