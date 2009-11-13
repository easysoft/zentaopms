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
<script language='Javascript'>
function selectProduct(productID)
{
    link = createLink('testcase', 'browse', 'productID=' + productID + '&type=byModule&param=0');
    location.href=link;
}
</script>
<div class="yui-d0 yui-t3">                 
  <div class="yui-b">
    <table class='table-1'>
      <caption>
        <?php echo $lang->testcase->selectProduct;?>
        <?php echo html::select('productID', $products, $productID, 'onchange="selectProduct(this.value);" style="width:200px"');?>
      </caption>
      <tr>
        <td>
          <div id='main'><?php echo $moduleTree;?></div>
          <div class='a-right'>
            <?php echo html::a($this->createLink('testcase', 'browse', "productId=$productID"), $lang->testcase->allCases);?>
            <?php echo html::a($this->createLink('tree', 'browse', "productID=$productID&view=case"), $lang->tree->manageCase);?>
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
        echo "<li id='byModuleTab'><nobr>"      . html::a($this->createLink('testcase', 'browse', "productid=$productID&type=byModule&param=$currentModuleID"), $currentModuleName)    . "</nobr></li>";
        //echo "<li id='assignToMeTab'><nobr>"    . html::a($this->createLink('testcase', 'browse', "productid=$productID&type=assignToMe"),    $lang->testcase->assignToMe)    . "</nobr></li>";
        //echo "<li id='openedByMeTab'><nobr>"    . html::a($this->createLink('testcase', 'browse', "productid=$productID&type=openedByMe"),    $lang->testcase->openedByMe)    . "</nobr></li>";
        //echo "<li id='resolvedByMeTab'><nobr>"  . html::a($this->createLink('testcase', 'browse', "productid=$productID&type=resolvedByMe"),  $lang->testcase->resolvedByMe)  . "</nobr></li>";
        //echo "<li id='assignToNullTab'><nobr>"  . html::a($this->createLink('testcase', 'browse', "productid=$productID&type=assignToNull"),  $lang->testcase->assignToNull)  . "</nobr></li>";
        //echo "<li id='longLifeBugsTab'><nobr>"  . html::a($this->createLink('testcase', 'browse', "productid=$productID&type=longLifeBugs"),  $lang->testcase->longLifeBugs)  . "</nobr></li>";
        //echo "<li id='postponedBugsTab'><nobr>" . html::a($this->createLink('testcase', 'browse', "productid=$productID&type=postponedBugs"), $lang->testcase->postponedBugs) . "</nobr></li>";
        echo <<<EOT
<script language="Javascript">
$("#{$type}Tab").addClass('active');
</script>
EOT;
        ?>
        </ul>
        <div>
        <?php if(common::hasPriv('testcase', 'create')) echo html::a($this->createLink('testcase', 'create', "productID=$productID&moduleID=$currentModuleID"), $lang->testcase->create);?>
        </div>
      </div> 

      <table class='table-1'>
        <tr class='colhead'>
          <th><?php echo $lang->testcase->id;?></th>
          <th><?php echo $lang->testcase->pri;?></th>
          <th><?php echo $lang->testcase->title;?></th>
          <th><?php echo $lang->testcase->type;?></th>
          <th><?php echo $lang->testcase->openedBy;?></th>
          <th><?php echo $lang->testcase->status;?></th>
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
<?php include '../../common/footer.html.php';?>
