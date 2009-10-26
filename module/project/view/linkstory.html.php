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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/tablesorter.html.php';?>
<div id='doc3'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-1 a-left tablesorter'> 
      <caption><?php echo $header['title']?></caption>
      <thead>
      <tr class='colhead'>
        <th width='60'><?php echo $lang->story->id;?></th>
        <th width='60'><?php echo $lang->story->pri;?></th>
        <th width='200'><?php echo $lang->story->product;?></th>
        <th><?php echo $lang->story->title;?></th>
        <th width='60'><?php echo $lang->story->linkStory;?></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach($allStories as $story):?>
      <?php if(isset($prjStories[$story->id])) continue;?>
      <tr>
        <td width='60' class='a-right'><?php echo $story->id;?></td>
        <td width='60' class='a-center'><?php echo $story->pri;?></td>
        <td width='200'><?php echo html::a($this->createLink('product', 'browse', "productID=$story->product"), $products[$story->product], '_blank');?></td>
        <td><?php echo $story->title;?></td>
        <td width='60' class='a-center'>
          <input type='checkbox' name='stories[]'  value='<?php echo $story->id;?>' />
          <input type='hidden'   name='products[]' value='<?php echo $story->product;?>' />
        </td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tr>
        <td colspan='5' class='a-center'><input type='submit' name='submit' value='<?php echo $lang->save;?>' /></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/footer.html.php';?>
