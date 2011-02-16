<?php
/**
 * The create view of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<div class='g'><div class='u-1'>
  <form method='post' enctype='multipart/form-data' target='hiddenwin'>
    <table class='table-1'> 
      <caption><?php echo $lang->testcase->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->lblProductAndModule;?></th>
        <td>
          <?php echo html::select('product', $products, $productID, "onchange=loadAll(this.value); class='select-3'");?>
          <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $currentModuleID);?></span>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->type;?></th>
        <td><?php echo html::select('type', $lang->testcase->typeList, 'feature', 'class=select-3');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->stage;?></th>
        <td><?php echo html::select('stage[]', $lang->testcase->stageList, '', "class='select-3' multiple='multiple'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->pri;?></th>
        <td><?php echo html::select('pri', $lang->testcase->priList, '', 'class=select-3');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->lblStory;?></th>
        <td><span id='storyIdBox'><?php echo html::select('story', $stories, '', 'class=select-3');?></span></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->title;?></th>
        <td><?php echo html::input('title', '', "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->steps;?></th>
        <td>
          <table class='w-p90'>
            <tr class='colhead'>
              <th class='w-30px'><?php echo $lang->testcase->stepID;?></th>
              <th><?php echo $lang->testcase->stepDesc;?></th>
              <th><?php echo $lang->testcase->stepExpect;?></th>
              <th class='w-100px'><?php echo $lang->actions;?></th>
            </tr>
            <?php
            for($i = 1; $i <= 3; $i ++)
            {
                echo "<tr id='row$i' class='a-center'>";
                echo "<th class='stepID'>$i</th>";
                echo '<td class="w-p50">' . html::textarea('steps[]', '', "rows='3' class='w-p100'") . '</td>';
                echo '<td>' . html::textarea('expects[]', '', "rows='3' class='w-p100'") . '</td>';
                echo "<td class='a-center w-100px'><nobr>";
                echo "<input type='button' tabindex='-1' class='addbutton' onclick='preInsert($i)'  value='{$lang->testcase->insertBefore}' /><br /> ";
                echo "<input type='button' tabindex='-1' class='addbutton' onclick='postInsert($i)' value='{$lang->testcase->insertAfter}'  /><br /> ";
                echo "<input type='button' tabindex='-1' class='delbutton' onclick='deleteRow($i)'  value='{$lang->testcase->deleteStep}'   /><br /> ";
                echo "</nobr></td>";
                echo '</tr>';
            }
            ?>
          </table>
        </td> 
      </tr>
      <tr>
        <th class='rowhead'><?php echo $lang->testcase->keywords;?></th>
        <td><?php echo html::input('keywords', '', "class='text-1'");?></td>
      </tr>  
       <tr>
        <th class='rowhead'><?php echo $lang->testcase->files;?></th>
        <td><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>  
      <tr>
        <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?> </td>
      </tr>
    </table>
  </form>
</div></div>
<?php include '../../common/view/footer.html.php';?>
