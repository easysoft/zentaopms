<?php
/**
 * The browse view file of testcase module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<script language='Javascript'>
/* 切换至按模块浏览。*/
function browseByModule(active)
{
    $('#mainbox').addClass('yui-t1');
    $('#treebox').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#' + active + 'Tab').removeClass('active');
    $('#bysearchTab').removeClass('active');
}

/* 通过搜索方式。*/
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
<div class='yui-d0'>
  <div id='featurebar'>
    <div class='f-left'>
      <?php
      echo "<span id='bymoduleTab' onclick=\"browseByModule('$browseType')\">" . $lang->testcase->moduleCases . "</span> ";
      echo "<span id='bysearchTab' onclick=\"browseBySearch('$browseType')\">{$lang->testcase->bySearch}</span> ";
      echo "<span id='allTab'>"         . html::a($this->createLink('testcase', 'browse', "productid=$productID&browseType=all&param=0&orderBy=$orderBy&recTotal=0&recPerPage=200"), $lang->testcase->allCases) . "</span>";
      echo "<span id='needconfirmTab'>" . html::a($this->createLink('testcase', 'browse', "productid=$productID&browseType=needconfirm&param=0"), $lang->testcase->needConfirm) . "</span>";
      ?>
    </div>
    <div class='f-right'>
      <?php common::printLink('testcase', 'create', "productID=$productID&moduleID=$moduleID", $lang->testcase->create); ?>
    </div>
  </div>
  <div id='querybox' class='<?php if($browseType != 'bysearch') echo 'hidden';?>'><?php echo $searchForm;?></div>
</div>

<div class='yui-d0 <?php if($browseType == 'bymodule') echo 'yui-t1';?>' id='mainbox'>
  <div class="yui-main">
    <div class='yui-b'>
      <?php $vars = "productID=$productID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <table class='table-1 colored tablesorter'>
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
            <td><?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$case->id"), sprintf('%03d', $case->id));?></td>
            <td><?php echo $case->pri?></td>
            <td class='a-left'><?php echo $case->title;?></td>
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
