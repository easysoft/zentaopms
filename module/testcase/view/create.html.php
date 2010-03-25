<?php
/**
 * The create view of case module of ZenTaoMS.
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
 * @package     case
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include './header.html.php';?>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table class='table-1'> 
      <caption><?php echo $lang->testcase->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->lblProductAndModule;?></th>
        <td class='a-left'>
          <?php echo html::select('product', $products, $productID, "onchange=loadAll(this.value); class='select-2'");?>
          <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $currentModuleID);?></span>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->lblStory;?></th>
        <td class='a-left'><span id='storyIdBox'><?php echo html::select('story', $stories, '', 'class=select-3');?></span></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->title;?></th>
        <td class='a-left'><?php echo html::input('title', '', "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->steps;?></th>
        <td class='a-left'>
          <table class='w-p90'>
            <tr class='colhead'>
              <th class='w-30px'><?php echo $lang->testcase->stepID;?></th>
              <th><?php echo $lang->testcase->stepDesc;?></th>
              <th><?php echo $lang->testcase->stepExpect;?></th>
              <th class='w-100px'><?php echo $lang->actions;?></th>
            </tr>
            <?php
            for($i = 1; $i <= 5; $i ++)
            {
                echo "<tr id='row$i' class='a-center'>";
                echo "<th class='stepID'>$i</th>";
                echo '<td class="w-p50">' . html::textarea('steps[]', '', "class='w-p100'") . '</td>';
                echo '<td>' . html::textarea('expects[]', '', "class='w-p100'") . '</td>';
                echo "<td class='a-center w-100px'><nobr>";
                echo "<input type='button' tabindex='-1' class='addbutton' onclick='preInsert($i)'  value='{$lang->testcase->insertBefore}' /> ";
                echo "<input type='button' tabindex='-1' class='addbutton' onclick='postInsert($i)' value='{$lang->testcase->insertAfter}'  /> ";
                echo "<input type='button' tabindex='-1' class='delbutton' onclick='deleteRow($i)'  value='{$lang->testcase->deleteStep}'   /> ";
                echo "</nobr></td>";
                echo '</tr>';
            }
            ?>
          </table>
        </td> 
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->lblTypeAndPri;?></th>
        <td class='a-left'>
          <?php echo html::select('type', (array)$lang->testcase->typeList, '', 'class=select-2');?>
          <?php echo html::select('pri', (array)$lang->testcase->priList, '', 'class=select-2');?>
        </td>
      </tr>  
      <tr>
        <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?> </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
