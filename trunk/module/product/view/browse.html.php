<?php
/**
 * The browse view file of product module of ZenTaoMS.
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
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/treeview.html.php';?>
<script language='Javascript'>
function selectProduct(productID)
{
    link = createLink('product', 'browse', 'productID=' + productID);
    location.href=link;
}
</script>
<div class="yui-d0 yui-t3">                 
  <div class="yui-b">
    <table class='table-1'>
      <caption>
        <?php echo $lang->product->selectProduct;?>
        <?php echo html::select('productID', $products, $productID, 'onchange="selectProduct(this.value);" style="width:200px"');?>
      </caption>
      <tr>
        <td>
          <div id='main'><?php echo $moduleTree;?></div>
          <div class='a-right'>
            <?php if(common::hasPriv('product', 'edit'))   echo html::a($this->createLink('product', 'edit',   "productID=$productID"), $lang->edit);?>
            <?php if(common::hasPriv('product', 'delete')) echo html::a($this->createLink('product', 'delete', "productID=$productID&confirm=no"),   $lang->delete, 'hiddenwin');?>
            <?php if(common::hasPriv('tree', 'browse'))    echo html::a($this->createLink('tree',    'browse', "productID=$productID&view=product"), $lang->tree->manage);?>
          </div>
        </td>
      </tr>
    </table>
    <!--
    <table align='center' class='table-1'>
      <caption><?php echo $lang->release->browse;?></caption>
      <tr>
        <th><?php echo $lang->release->id;?></th>
        <th><?php echo $lang->release->name;?></th>
        <th><?php echo $lang->release->desc;?></th>
        <th><?php echo $lang->release->status;?></th>
      </tr>
      <?php //foreach($releases as $release):?>
      <tr>
        <td><?php //echo $release->id;?></td>
        <td><?php //echo $release->name;?></td>
        <td><?php //echo $release->desc;?></td>
        <td><?php //echo $release->status;?></td>
      </tr>
      <?php //endforeach;?>
    </table>
    <?php 
     $vars['productID'] = $productID;
     $addLink = $this->createLink('release', 'create', $vars);
     echo "<a href='$addLink'>{$lang->release->create}</a>";
     ?>
     -->
    </div>
    <div class="yui-main">
    <div class="yui-b">
      <table align='center' class='table-1'>
        <caption>
          <div class='half-left'>
          <?php
          echo html::a($this->createLink('product', 'browse', "productID=$productID"), $productName) . $lang->arrow;
          foreach($parentModules as $module)
          {
              echo html::a($this->createLink('product', 'browse', "productID=$productID&moduleID=$module->id"), $module->name) . $lang->arrow;
          }
          echo $lang->story->browse;
          ?>
          </div>
          <div class='half-right'>
          <?php 
          if(common::hasPriv('story', 'create')) echo html::a($this->createLink('story', 'create', "productID=$productID&moduleID=$moduleID"), $lang->story->create);
          ?>
          </div>
        </caption>
        <thead>
          <tr>
            <?php
            $app->global->vars    = "productID=$productID&moduleID=$moduleID&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage";
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
                $link = helper::createLink('product', 'browse', sprintf($app->global->vars, $orderBy));
                echo html::a($link, $lang->story->$fieldName);
            }
            ?>
            <th><?php printOrderLink('id');?></th>
            <th><?php printOrderLink('pri');?></th>
            <th><?php printOrderLink('title');?></th>
            <th><?php printOrderLink('assignedTo');?></th>
            <th><?php printOrderLink('openedBy');?></th>
            <th><?php printOrderLink('estimate');?></th>
            <th><?php printOrderLink('status');?></th>
            <th><?php printOrderLink('lastEditedDate');?></th>
            <th><?php echo $lang->action;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($stories as $key => $story):?>
          <?php
          $viewLink = $this->createLink('story', 'view', "storyID=$story->id");
          $canView  = common::hasPriv('story', 'view');
          ?>
          <tr class='a-center'>
            <td><?php if($canView) echo html::a($viewLink, sprintf('%03d', $story->id)); else printf('%03d', $story->id);?></td>
            <td><?php echo $story->pri;?></td>
            <td class='a-left'><nobr><?php echo $story->title;?></nobr></td>
            <td><?php echo $users[$story->assignedTo];?></td>
            <td><?php echo $users[$story->openedBy];?></td>
            <td><?php echo $story->estimate;?></td>
            <td class='<?php echo $story->status;?>'><?php $statusList = (array)$lang->story->statusList; echo $statusList[$story->status];?></td>
            <td><?php echo substr($story->lastEditedDate, 5, 11);?></td>
            <td>
              <?php if(common::hasPriv('story', 'edit'))   echo html::a($this->createLink('story', 'edit',   "story={$story->id}"), $lang->edit);?>
              <?php if(common::hasPriv('story', 'delete')) echo html::a($this->createLink('story', 'delete', "story={$story->id}&confirm=no"), $lang->delete, 'hiddenwin');?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php echo $pager;?>
      
    </div>
  </div>
</div>  
<?php include '../../common/footer.html.php';?>
