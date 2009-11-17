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
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/treeview.html.php';?>
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
        echo "<li id='bymoduletab'><nobr>"      . html::a($this->createLink('bug', 'browse', "productid=$productID&type=byModule&param=$currentModuleID"), $currentModuleName)    . "</nobr></li>";
        echo "<li id='assigntometab'><nobr>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&type=assignToMe&param=0"),    $lang->bug->assignToMe)    . "</nobr></li>";
        echo "<li id='openedbymetab'><nobr>"    . html::a($this->createLink('bug', 'browse', "productid=$productID&type=openedByMe&param=0"),    $lang->bug->openedByMe)    . "</nobr></li>";
        echo "<li id='resolvedbymetab'><nobr>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&type=resolvedByMe&param=0"),  $lang->bug->resolvedByMe)  . "</nobr></li>";
        echo "<li id='assigntonulltab'><nobr>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&type=assignToNull&param=0"),  $lang->bug->assignToNull)  . "</nobr></li>";
        echo "<li id='longlifebugstab'><nobr>"  . html::a($this->createLink('bug', 'browse', "productid=$productID&type=longLifeBugs&param=0"),  $lang->bug->longLifeBugs)  . "</nobr></li>";
        echo "<li id='postponedbugstab'><nobr>" . html::a($this->createLink('bug', 'browse', "productid=$productID&type=postponedBugs&param=0"), $lang->bug->postponedBugs) . "</nobr></li>";
        echo <<<EOT
<script language="Javascript">
$("#{$type}tab").addClass('active');
</script>
EOT;
        ?>
        </ul>
        <?php if(common::hasPriv('bug', 'create')) echo '<div>' . html::a($this->createLink('bug', 'create', "type=product&productID=$productID&moduleID=$currentModuleID"), $lang->bug->create) . '</div>';?>
      </div> 
      <?php
      $app->global->vars    = "productID=$productID&type=$type&param=$param&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage";
      $app->global->orderBy = $orderBy;
      function printOrderLink($fieldName)
      {
          global $app, $lang;
          if(strpos($app->global->orderBy, $fieldName) !== false)
          {
              if(stripos($app->global->orderBy, 'desc') !== false) $orderBy = str_replace('desc', 'asc', $app->global->orderBy);
              if(stripos($app->global->orderBy, 'asc')  !== false) $orderBy = str_replace('asc', 'desc', $app->global->orderBy);
          }
          else
          {
              $orderBy = $fieldName . '|' . 'asc';
          }
          $link = helper::createLink('bug', 'browse', sprintf($app->global->vars, $orderBy));
          echo html::a($link, $lang->bug->$fieldName);
      }
      ?>

      <table class='table-1'>
        <thead>
        <tr class='colhead'>
          <th><?php printOrderLink('id');?></th>
          <th><?php printOrderLink('severity');?></th>
          <th><?php printOrderLink('title');?></th>
          <th><?php printOrderLink('openedBy');?></th>
          <th><?php printOrderLink('assignedTo');?></th>
          <th><?php printOrderLink('resolvedBy');?></th>
          <th><?php printOrderLink('resolution');?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($bugs as $bug):?>
        <tr class='a-center'>
          <td class='a-right'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), sprintf('%03d', $bug->id));?></td>
          <td><?php echo $bug->severity?></td>
          <td width='50%' class='a-left'><?php echo $bug->title;?></td>
          <td><?php echo $users[$bug->openedBy];?></td>
          <td <?php if($bug->assignedTo == $this->app->user->account) echo 'style=color:red';?>><?php echo $users[$bug->assignedTo];?></td>
          <td><?php echo $users[$bug->resolvedBy];?></td>
          <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <div class='a-right'><?php echo $pager;?></div>
    </div>
  </div>
</div>  
<?php include '../../common/footer.html.php';?>
