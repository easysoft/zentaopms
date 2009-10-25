<?php
/**
 * The index view file of product module of ZenTaoMS.
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
 * @version     $Id: index.html.php 1262 2009-09-03 08:32:28Z wwccss $
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<style> #main ul li { list-style-type:none;}</style>
<link rel="stylesheet" href="/theme/default/treeview.css" />
<script src="/js/jquery/lib.js" type="text/javascript"></script>
<script src="/js/jquery/treeview/jquery.treeview.js" type="text/javascript"></script>
<script language='javascript'>
$(function()
{
    $("#module").treeview(
    {
        persist: "cookie",
        collapsed: true,
    });
});

function selectProduct(product)
{
    link = createLink('product', 'index', 'product=' + product);
    location.href=link;
}
</script>
<div class="yui-d0 yui-t3">                 
  <div class="yui-b">
    <table class='table-1'>
      <caption>
        <?php echo $lang->product->selectProduct;?>
        <?php echo html::select('product', $products, $product, 'onchange="selectProduct(this.value);" style="width:200px"');?>
      </caption>
      <tr>
        <td>
          <div id='main'><?php echo $modules;?></div>
          <div class='a-right'>
            <?php echo html::a($this->createLink('product', 'edit', "pid=$product"), $lang->product->edit);?>
            <?php echo html::a($this->createLink('product', 'delete', "product=$product&confirm=no"), $lang->product->delete, 'hiddenwin');?>
            <?php echo html::a($this->createLink('product', 'mangemodule', "product=$product"), $lang->product->mangeModule);?>
          </div>
        </td>
      </tr>
    </table>
    <table align='center' class='table-1'>
      <caption><?php echo $lang->release->browse;?></caption>
      <tr>
        <th><?php echo $lang->release->id;?></th>
        <th><?php echo $lang->release->name;?></th>
        <th><?php echo $lang->release->desc;?></th>
        <th><?php echo $lang->release->status;?></th>
      </tr>
      <?php foreach($releases as $release):?>
      <tr>
        <td><?php echo $release->id;?></td>
        <td><?php echo $release->name;?></td>
        <td><?php echo $release->desc;?></td>
        <td><?php echo $release->status;?></td>
      </tr>
      <?php endforeach;?>
    </table>
    <?php 
     $vars['product'] = $product;
     $addLink = $this->createLink('release', 'create', $vars);
     echo "<a href='$addLink'>{$lang->release->create}</a>";
     ?>
    </div>
    <div class="yui-main">
    <div class="yui-b">
      <table align='center' class='table-1'>
        <caption><?php echo $lang->story->browse;?></caption>
        <tr>
          <th><?php echo $lang->story->id;?></th>
          <th><?php echo $lang->story->title;?></th>
          <th><?php echo $lang->story->spec;?></th>
          <th></th>
        </tr>
        <?php foreach($stories as $story):?>
        <tr>
          <td><?php echo $story->id;?></td>
          <td><?php echo $story->title;?></td>
          <td><?php echo $story->spec;?></td>
          <td><?php echo html::a($this->createLink('story', 'delete', "story={$story->id}&confirm=no"), $lang->story->delete, 'hiddenwin2');?>
        </tr>
        <?php endforeach;?>
      </table>
      <?php 
      $addLink = $this->createLink('story', 'create', "product=$product");
      echo "<a href='$addLink'>{$lang->story->create}</a>";
      ?>
    </div>
  </div>
</div>  
<?php include '../../common/footer.html.php';?>
