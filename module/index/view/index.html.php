<?php
/**
 * The html template file of index method of index module of ZenTaoMS.
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
 * @package     ZenTaoMS
 * @version     $Id$
 */
?>
<?php include '../../common/header.html.php';?>
<div class="yui-d0 yui-t7">  
  <div class="yui-gb">  
    <?php foreach($projects as $key => $project):?>
    <?php
    $class = 0;
    if($key == 0) $class = 'first';
    if($key == 3) break;
    ?>
    <div class="yui-u <?php echo $class;?>">  
      <table class='table-1'>
        <caption><?php echo $project->name;?></caption>
        <tr>
          <th width='60'><?php echo $lang->project->name;?></th>
          <td><?php echo html::a($this->createLink('project', 'browse', "projectid=$project->id"), $project->name);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->code;?></th>
          <td><?php echo $project->code;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->begin;?></th>
          <td><?php echo $project->begin;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->end;?></th>
          <td><?php echo $project->end;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->status;?></th>
          <td><?php echo $project->status;?></td>
        </tr>
      </table>
    </div>  
    <?php endforeach;?>
  </div>
</div>

<div class="yui-d0 yui-t7">  
  <div class="yui-gb">  
    <?php foreach($products as $key => $product):?>
    <?php
    $class = 0;
    if($key == 0) $class = 'first';
    if($key == 3) break;
    ?>
    <div class="yui-u <?php echo $class;?>">  
      <table class='table-1'>
      <caption><?php echo $product->name;?></caption>
        <tr>
          <th width='60'><?php echo $lang->product->name;?></th>
          <td><?php echo html::a($this->createLink('product', 'browse', "productID=$product->id"), $product->name);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->product->code;?></th>
          <td><?php echo $product->code;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->product->desc;?></th>
          <td><?php echo nl2br($product->desc);?></td>
        </tr>
      </table>
    </div>  
    <?php endforeach;?>
  </div>
</div>  
<?php include '../../common/footer.html.php';?>
