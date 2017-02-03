<?php
/**
 * The view file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: view.html.php 594 2010-03-27 13:44:07Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datatable.fix.html.php';?>
<?php include './caseheader.html.php';?>
<?php js::set('confirmUnlink', $lang->testtask->confirmUnlinkCase)?>
<?php js::set('taskCaseBrowseType', ($browseType == 'bymodule' and $this->session->taskCaseBrowseType == 'bysearch') ? 'all' : $this->session->taskCaseBrowseType);?>
<script language="Javascript">
var browseType = '<?php echo $browseType;?>';
var moduleID   = '<?php echo $moduleID;?>';
</script>
<div class='side' id='casesbox'>
  <a class='side-handle' data-id='testtaskTree'><i class='icon-caret-left'></i></a>
  <div class='side-body'>
    <div class='panel panel-sm'>
      <div class='panel-heading nobr'><?php echo html::icon($lang->icons['product']);?> <strong><?php echo $productName;?></strong></div>
      <div class='panel-body'>
        <?php echo $moduleTree;?>
      </div>
    </div>
  </div>
</div>
<div class='main'>
  <script>setTreeBox();</script>
  <form method='post' name='casesform' id='casesForm'>
    <?php
    $vars         = "taskID=$task->id&browseType=$browseType&param=$param&orderBy=%s&recToal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
    $datatableId  = $this->moduleName . ucfirst($this->methodName);
    $useDatatable = (isset($this->config->datatable->$datatableId->mode) and $this->config->datatable->$datatableId->mode == 'datatable');
    $file2Include = $useDatatable ? dirname(__FILE__) . '/datatabledata.html.php' : dirname(__FILE__) . '/casesdata.html.php';

    $canBatchEdit   = common::hasPriv('testcase', 'batchEdit');
    $canBatchAssign = common::hasPriv('testtask', 'batchAssign');
    $canBatchRun    = common::hasPriv('testtask', 'batchRun');
    $hasCheckbox    = ($canBatchEdit or $canBatchAssign or $canBatchRun);
    include $file2Include;
    ?>
      <tfoot>
        <tr>
          <td colspan='13'>
            <?php if($runs):?>
            <div class='table-actions clearfix'>
              <?php if($hasCheckbox) echo html::selectButton();?>
              <div class='btn-group dropup'>
                <?php
                $actionLink = $this->createLink('testcase', 'batchEdit', "productID=$productID");
                $misc       = $canBatchEdit ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";
                echo html::commonButton($lang->edit, $misc);
                ?>
                <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
                <ul class='dropdown-menu'>
                  <?php
                  $actionLink = $this->createLink('testtask', 'batchUnlinkCases', "taskID=$task->id");
                  $misc       = common::hasPriv('testtask', 'batchUnlinkCases') ? "onclick=\"setFormAction('$actionLink')\"" : "class='disabled'";
                  echo "<li>" . html::a('javascript:;', $lang->testtask->unlinkCase, '', $misc) . "</li>";
                  ?>
                </ul>
              </div>
              <?php
              if($canBatchAssign)
              {
                  $actionLink = inLink('batchAssign', "taskID=$task->id");
                  echo "<div class='input-group w-200px'>";
                  echo html::select('assignedTo', $assignedTos, '', 'class="form-control chosen"');
                  echo "<span class='input-group-addon'>";
                  echo html::a("javascript:setFormAction(\"$actionLink\")", $lang->testtask->assign);
                  echo '</span></div>';
              }
              if($canBatchRun)
              {
                  $actionLink = inLink('batchRun', "productID=$productID&orderBy=id_desc&from=testtask&taskID=$taskID");
                  echo html::commonButton($lang->testtask->runCase, "onclick=\"setFormAction('$actionLink')\"");
              }
              ?>
            </div>
            <?php endif;?>
            <?php echo $pager->show();?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$('#module' + moduleID).addClass('active'); 
$('#' + taskCaseBrowseType + 'Tab').addClass('active');
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
