<?php
/**
 * The link story view of project module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-1 tablesorter a-center'> 
      <thead>
      <tr class='colhead'>
        <th class='w-id'><?php echo $lang->idAB;?></th>
        <th class='w-pri'><?php echo $lang->priAB;?></th>
        <th><?php echo $lang->story->product;?></th>
        <th><?php echo $lang->story->title;?></th>
        <th><?php echo $lang->story->plan;?></th>
        <th class='w-user'><?php echo $lang->openedByAB;?></th>
        <th class='w-hour'><?php echo $lang->story->estimateAB;?></th>
        <th class='w-50px'><?php echo $lang->link;?></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach($allStories as $story):?>
      <?php if(isset($prjStories[$story->id])) continue;?>
      <?php $storyLink = $this->createLink('story', 'view', "storyID=$story->id");?>
      <tr>
        <td><?php echo html::a($storyLink, $story->id);?></td>
        <td><?php echo $lang->story->priList[$story->pri];?></td>
        <td><?php echo html::a($this->createLink('product', 'browse', "productID=$story->product"), $products[$story->product], '_blank');?></td>
        <td class='a-left nobr'><?php echo html::a($storyLink, $story->title);?></td>
        <td><?php echo $story->planTitle;?></td>
        <td><?php echo $users[$story->openedBy];?></td>
        <td><?php echo $story->estimate;?></td>
        <td>
          <input type='checkbox' name='stories[]'  value='<?php echo $story->id;?>' />
          <input type='hidden'   name='products[]' value='<?php echo $story->product;?>' />
        </td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot><tr><td colspan='8'><?php echo html::submitButton();?></td></tr></tfoot>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
