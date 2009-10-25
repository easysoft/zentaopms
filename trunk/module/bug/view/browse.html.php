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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     bug
 * @version     $Id: browse.html.php 1369 2009-09-29 05:41:15Z wwccss $
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/treeview.html.php';?>
<?php include '../../common/tablesorter.html.php';?>
<script language='Javascript'>
function selectProduct(productID)
{
    link = createLink('bug', 'browse', 'productID=' + productID + '&type=byModule&param=0');
    location.href=link;
}
</script>
<div class="yui-d0 yui-t3">                 
  <div class="yui-b">
    <table class='table-1'>
      <caption>
        <?php echo $lang->bug->selectProduct;?>
        <?php echo html::select('productID', $products, $productID, 'onchange="selectProduct(this.value);" style="width:200px"');?>
      </caption>
      <tr>
        <td>
          <div id='main'><?php echo $moduleTree;?></div>
          <div class='a-right'>
            <?php echo html::a($this->createLink('bug', 'browse', "productID=$productID"), $lang->bug->allBugs);?>
            <?php if(common::hasPriv('tree', 'browse')) echo html::a($this->createLink('tree', 'browse', "productID=$productID&view=bug"), $lang->tree->manageBug);?>
          </div>
        </td>
      </tr>
    </table>
  </div>
  <div class="yui-main">
    <div class="yui-b">
      <div id='tabbar' class='yui-d0' style='clear:right'>
        <ul>
        <?php
        echo "<li><nobr>$productName</nobr></li>";
        echo "<li id='byModuleTab'><nobr>"      . html::a($this->createLink('bug', 'browse', "productid=$productID&type=byModule&param=$currentModuleID"), $currentModuleName)    . "</nobr></li>";
        //echo "<li id='assignToMeTab'><nobr>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&type=assignToMe"),    $lang->bug->assignToMe)    . "</nobr></li>";
        //echo "<li id='openedByMeTab'><nobr>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&type=openedByMe"),    $lang->bug->openedByMe)    . "</nobr></li>";
        //echo "<li id='resolvedByMeTab'><nobr>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&type=resolvedByMe"),  $lang->bug->resolvedByMe)  . "</nobr></li>";
        //echo "<li id='assignToNullTab'><nobr>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&type=assignToNull"),  $lang->bug->assignToNull)  . "</nobr></li>";
        //echo "<li id='longLifeBugsTab'><nobr>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&type=longLifeBugs"),  $lang->bug->longLifeBugs)  . "</nobr></li>";
        //echo "<li id='postponedBugsTab'><nobr>" . html::a($this->createLink('bug', 'browse', "productid=$productID&type=postponedBugs"), $lang->bug->postponedBugs) . "</nobr></li>";
        echo <<<EOT
<script language="Javascript">
$("#{$type}Tab").addClass('active');
</script>
EOT;
        ?>
        </ul>
        <?php if(common::hasPriv('bug', 'create')) echo '<div>' . html::a($this->createLink('bug', 'create', "productID=$productID&moduleID=$currentModuleID"), $lang->bug->create) . '</div>';?>
      </div> 

      <table class='table-1 tablesorter'>
        <thead>
        <tr class='colhead'>
          <th><?php echo $lang->bug->id;?></th>
          <th><?php echo $lang->bug->severity;?></th>
          <th><?php echo $lang->bug->title;?></th>
          <th><?php echo $lang->bug->openedBy;?></th>
          <th><?php echo $lang->bug->assignedTo;?></th>
          <th><?php echo $lang->bug->resolvedBy;?></th>
          <th><?php echo $lang->bug->resolution;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($bugs as $bug):?>
        <tr class='a-center'>
          <td class='a-right'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id);?></td>
          <td><?php echo $bug->severity?></td>
          <td width='50%' class='a-left'><?php echo $bug->title;?></td>
          <td><?php echo $users[$bug->openedBy];?></td>
          <td><?php echo $users[$bug->assignedTo];?></td>
          <td><?php echo $users[$bug->resolvedBy];?></td>
          <td><?php echo $bug->resolution;?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>  
<?php include '../../common/footer.html.php';?>
