<?php
/**
 * The browse view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<?php include '../../common/view/table2csv.html.php';?>
<script language='Javascript'>
/* Switch to module browse. */
function browseByModule(active)
{
    $('#mainbox').addClass('yui-t1');
    $('#treebox').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#' + active + 'Tab').removeClass('active');
    $('#bysearchTab').removeClass('active');
}

/* Swtich to search module. */
function browseBySearch(active)
{
    $('#mainbox').removeClass('yui-t1');
    $('#treebox').addClass('hidden');
    $('#querybox').removeClass('hidden');
    $('#' + active + 'Tab').removeClass('active');
    $('#bysearchTab').addClass('active');
    $('#bymoduleTab').removeClass('active');
}
</script>
<div class='g'><div class='u-1'>
  <div id='featurebar'>
    <div class='f-left'>
      <?php
      echo "<span id='bymoduleTab' onclick=\"browseByModule('$browseType')\"><a href='#'>" . $lang->testcase->moduleCases . "</a></span> ";
      echo "<span id='allTab'>"         . html::a($this->createLink('testcase', 'browse', "productid=$productID&browseType=all&param=0&orderBy=$orderBy&recTotal=0&recPerPage=200"), $lang->testcase->allCases) . "</span>";
      echo "<span id='needconfirmTab'>" . html::a($this->createLink('testcase', 'browse', "productid=$productID&browseType=needconfirm&param=0"), $lang->testcase->needConfirm) . "</span>";
      echo "<span id='bysearchTab' onclick=\"browseBySearch('$browseType')\"><a href='#'>{$lang->testcase->bySearch}</a></span> ";
      ?>
    </div>
    <div class='f-right'>
      <?php echo html::export2csv($lang->exportCSV, $lang->setFileName);?>
      <?php common::printLink('testcase', 'create', "productID=$productID&moduleID=$moduleID", $lang->testcase->create); ?>
    </div>
  </div>
  <div id='querybox' class='<?php if($browseType != 'bysearch') echo 'hidden';?>'><?php echo $searchForm;?></div>
</div>

<div class='yui-d0 <?php if($browseType == 'bymodule') echo 'yui-t1';?>' id='mainbox'>
  <div class="yui-main">
    <div class='yui-b'>
      <?php $vars = "productID=$productID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <table class='table-1 colored tablesorter datatable'>
        <thead>
          <tr class='colhead'>
            <th class='w-id'> <?php common::printOrderLink('id',    $orderBy, $vars, $lang->idAB);?></th>
            <th class='w-pri'><?php common::printOrderLink('pri',   $orderBy, $vars, $lang->priAB);?></th>
            <th><?php common::printOrderLink('title', $orderBy, $vars, $lang->testcase->title);?></th>
            <?php if($browseType == 'needconfirm'):?>
            <th>  <?php common::printOrderLink('story', $orderBy, $vars, $lang->testcase->story);?></th>
            <th class='w-50px'><?php echo $lang->actions;?></th>
            <?php else:?>
            <th class='w-type'>  <?php common::printOrderLink('type',     $orderBy, $vars, $lang->typeAB);?></th>
            <th class='w-user'>  <?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->openedByAB);?></th>
            <th class='w-status'><?php common::printOrderLink('status',   $orderBy, $vars, $lang->statusAB);?></th>
            <th class='w-80px {sorter:false}'><?php echo $lang->actions;?></th>
            <?php endif;?>
          </tr>
          <?php foreach($cases as $case):?>
          <tr class='a-center'>
            <?php $viewLink = inlink('view', "caseID=$case->id");?>
            <td><?php echo html::a($viewLink, sprintf('%03d', $case->id));?></td>
            <td><?php echo $case->pri?></td>
            <td class='a-left'><?php echo html::a($viewLink, $case->title);?></td>
            <?php if($browseType == 'needconfirm'):?>
            <td class='a-left'><?php echo html::a($this->createLink('story', 'view', "storyID=$case->story"), $case->storyTitle, '_blank');?></td>
            <td><?php echo html::a(inlink('confirmStoryChange', "caseID=$case->id"), $lang->confirm, 'hiddenwin');?></td>
            <?php else:?>
            <td><?php echo $lang->testcase->typeList[$case->type];?></td>
            <td><?php echo $users[$case->openedBy];?></td>
            <td><?php echo $lang->testcase->statusList[$case->status];?></td>
            <td>
              <?php
              common::printLink('testcase', 'edit',   "caseID=$case->id", $lang->testcase->buttonEdit);
              common::printLink('testcase', 'delete', "caseID=$case->id", $lang->delete, 'hiddenwin');
              ?>
            </td>
            <?php endif;?>
          </tr>
        <?php endforeach;?>
        </thead>
      </table>
      <?php $pager->show();?>
    </div>
  </div>

  <div class='yui-b  <?php if($browseType != 'bymodule') echo 'hidden';?>' id='treebox'>
    <div class='box-title'><?php echo $productName;?></div>
    <div class='box-content'>
      <?php echo $moduleTree;?>
      <div class='a-right'>
        <?php common::printLink('tree', 'browse', "productID=$productID&view=case", $lang->tree->manage);?>
      </div>
    </div>
  </div>

</div>  
<script language="Javascript">
$("#<?php echo $browseType;?>Tab").addClass('active');
$("#module<?php echo $moduleID;?>").addClass('active'); 
</script>
<?php include '../../common/view/footer.html.php';?>
