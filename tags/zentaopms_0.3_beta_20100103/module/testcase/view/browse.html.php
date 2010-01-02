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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/treeview.html.php';?>
<?php include '../../common/colorize.html.php';?>
<script language='Javascript'>
/* 切换浏览方式。*/
function browseByModule(active)
{
    $('#mainbox').addClass('yui-t7');
    $('#treebox').removeClass('hidden');
    $('#bymoduletab').addClass('active');
    $('#' + active + 'tab').removeClass('active');
}
</script>

<div class='yui-d0'>
  <div id='featurebar'>
    <div class='f-left'>
      <?php
      echo "<span id='bymoduletab' onclick=\"browseByModule('$type')\">" . $lang->testcase->moduleCases . "</span> ";
      echo "<span id='alltab'>" . html::a($this->createLink('testcase', 'browse', "productid=$productID&type=all&param=0"), $lang->testcase->allCases) . "</span>";
      ?>
    </div>
    <div class='f-right'>
      <?php common::printLink('testcase', 'create', "productID=$productID&moduleID=$moduleID", $lang->testcase->create); ?>
    </div>
  </div>
</div>

<div class='yui-d0 <?php if($type == 'bymodule') echo 'yui-t7';?>' id='mainbox'>
  <div class='yui-b  <?php if($type != 'bymodule') echo 'hidden';?>' id='treebox'>
    <div class='box-title'><?php echo $productName;?></div>
    <div class='box-content'>
      <?php echo $moduleTree;?>
      <div class='a-right'>
        <?php common::printLink('tree', 'browse', "productID=$productID&view=case", $lang->tree->manage);?>
      </div>
    </div>
  </div>
  <div class="yui-main">
    <div class='yui-b'>
      <table class='table-1 colored'>
        <tr class='colhead'>
          <?php $vars = "productID=$productID&type=$type&param=$param&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage"; ?>
          <th><?php common::printOrderLink('id',       $orderBy, $vars, $lang->testcase->id);?></th>
          <th><?php common::printOrderLink('pri',      $orderBy, $vars, $lang->testcase->pri);?></th>
          <th><?php common::printOrderLink('title',    $orderBy, $vars, $lang->testcase->title);?></th>
          <th><?php common::printOrderLink('type',     $orderBy, $vars, $lang->testcase->type);?></th>
          <th><?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->testcase->openedBy);?></th>
          <th><?php common::printOrderLink('status',   $orderBy, $vars, $lang->testcase->status);?></th>
        </tr>
        <?php foreach($cases as $case):?>
        <tr class='a-center'>
          <td><?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$case->id"), sprintf('%03d', $case->id));?></td>
          <td><?php echo $case->pri?></td>
          <td width='50%' class='a-left'><?php echo $case->title;?></td>
          <td><?php echo $lang->testcase->typeList[$case->type];?></td>
          <td><?php echo $users[$case->openedBy];?></td>
          <td><?php echo $lang->testcase->statusList[$case->status];?></td>
        </tr>
        <?php endforeach;?>
      </table>
      <div class='a-right'><?php echo $pager;?></div> 
    </div>
  </div>
</div>  
<script language="Javascript">
$("#<?php echo $type;?>tab").addClass('active');
$("#module<?php echo $moduleID;?>").addClass('active'); 
</script>
<?php include '../../common/footer.html.php';?>
