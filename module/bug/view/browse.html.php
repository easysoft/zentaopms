<?php
/**
 * The browse view file of bug module of ZenTaoMS.
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
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<script language='Javascript'>
/* 通过模块浏览。*/
function browseByModule(active)
{
    $('#mainbox').addClass('yui-t7');
    $('#treebox').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#querybox').addClass('hidden');
    $('#' + active + 'Tab').removeClass('active');
}
/* 搜索。*/
function browseBySearch(active)
{
    $('#mainbox').removeClass('yui-t7');
    $('#treebox').addClass('hidden');
    $('#querybox').removeClass('hidden');
    $('#bymoduleTab').removeClass('active');
    $('#' + active + 'Tab').removeClass('active');
    $('#bysearchTab').addClass('active');
}

</script>

<div class='yui-d0'>
  <div id='featurebar'>
    <div class='f-left'>
      <?php
      echo "<span id='bymoduleTab' onclick=\"browseByModule('$browseType')\">" . $lang->bug->moduleBugs . "</span> ";
      echo "<span id='assigntomeTab'>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=assignToMe&param=0"),    $lang->bug->assignToMe)    . "</span>";
      echo "<span id='openedbymeTab'>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=openedByMe&param=0"),    $lang->bug->openedByMe)    . "</span>";
      echo "<span id='resolvedbymeTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=resolvedByMe&param=0"),  $lang->bug->resolvedByMe)  . "</span>";
      echo "<span id='assigntonullTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=assignToNull&param=0"),  $lang->bug->assignToNull)  . "</span>";
      echo "<span id='longlifebugsTab'>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=longLifeBugs&param=0"),  $lang->bug->longLifeBugs)  . "</span>";
      echo "<span id='postponedbugsTab'>" . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=postponedBugs&param=0"), $lang->bug->postponedBugs) . "</span>";
      echo "<span id='bysearchTab' onclick=\"browseBySearch('$browseType')\">{$lang->bug->byQuery}</span> ";
      echo "<span id='allTab'>" . html::a($this->createLink('bug', 'browse', "productid=$productID&browseType=all&param=0&orderBy=$orderBy&recTotal=0&recPerPage=200"), $lang->bug->allBugs) . "</span>";
      ?>
    </div>
    <div class='f-right'>
      <?php common::printLink('bug', 'report', "productID=$productID&browseType=$browseType&moduleID=$moduleID", $lang->bug->report->common); ?>
      <?php common::printLink('bug', 'create', "productID=$productID&extra=moduleID=$moduleID", $lang->bug->create); ?>
    </div>
  </div>
  <div id='querybox' class='<?php if($browseType !='bysearch') echo 'hidden';?>'><?php echo $searchForm;?></div>
</div>

<div class='yui-d0 <?php if($browseType == 'bymodule') echo 'yui-t7';?>' id='mainbox'>
  <div class='yui-b  <?php if($browseType != 'bymodule') echo 'hidden';?>' id='treebox'>
    <div class='box-title'><?php echo $productName;?></div>
    <div class='box-content'>
      <?php echo $moduleTree;?>
      <div class='a-right'>
        <?php if(common::hasPriv('tree', 'browse')) echo html::a($this->createLink('tree', 'browse', "productID=$productID&view=bug"), $lang->tree->manage);?>
      </div>
    </div>
  </div>

  <div class="yui-main">
    <div class="yui-b">
      <?php $vars = "productID=$productID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <table class='table-1 fixed colored'>
        <thead>
        <tr class='colhead'>
          <th><?php common::printOrderLink('id',         $orderBy, $vars, $lang->bug->id);?></th>
          <th><?php common::printOrderLink('severity',   $orderBy, $vars, $lang->bug->severity);?></th>
          <th class='w-p50'><?php common::printOrderLink('title',  $orderBy, $vars, $lang->bug->title);?></th>
          <th><?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->bug->openedBy);?></th>
          <th><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->bug->assignedTo);?></th>
          <th><?php common::printOrderLink('resolvedBy', $orderBy, $vars, $lang->bug->resolvedBy);?></th>
          <th><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->resolution);?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($bugs as $bug):?>
        <tr class='a-center'>
          <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), sprintf('%03d', $bug->id));?></td>
          <td><?php echo $lang->bug->severityList[$bug->severity]?></td>
          <td class='a-left nobr'><?php echo $bug->title;?></td>
          <td><?php echo $users[$bug->openedBy];?></td>
          <td <?php if($bug->assignedTo == $this->app->user->account) echo 'style=color:red';?>><?php echo $users[$bug->assignedTo];?></td>
          <td><?php echo $users[$bug->resolvedBy];?></td>
          <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php $pager->show();?>
    </div>
  </div>
</div>  
<script language='javascript'>
$("#<?php echo $browseType;?>Tab").addClass('active'); 
$("#module<?php echo $moduleID;?>").addClass('active'); 
</script>
<?php include '../../common/view/footer.html.php';?>
