<?php
/**
 * The view file of case module of ZenTaoMS.
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
 * @package     case
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<?php include '../../common/colorize.html.php';?>

<div class='yui-d0'>
  <div id='titlebar'>
    CASE #<?php echo $case->id . $lang->colon . $case->title;?>
    <div class='f-right'>
      <?php
      common::printLink('testcase', 'edit',   "caseID=$case->id", $lang->testcase->buttonEdit);
      common::printLink('testcase', 'browse', "productID=$case->product", $lang->testcase->buttonToList);
      ?>
    </div>
  </div>
</div>

<div class='yui-d0 yui-t8'>
  <div class='yui-main'>
    <div class='yui-b'>
      <fieldset>
        <legend><?php echo $lang->testcase->legendSteps;?></legend>
        <table class='table-1 bd-1px bd-gray colored'>
          <tr class='colhead'>
          <th class='w-30px bd-1px bd-gray'><?php echo $lang->testcase->stepID;?></td>
          <th class='w-p70 bd-1px bd-gray'><?php echo $lang->testcase->stepDesc;?></td>
          <th class='bd-1px bd-gray'><?php echo $lang->testcase->stepExpect;?></td>
          </tr> 
        <?php
        foreach($case->steps as $stepID => $step)
        {
            $stepID += 1;
            echo "<tr class='bd-1px bd-gray'><th class='bd-1px bd-gray'>$stepID</th>";
            echo "<td class='bd-1px bd-gray'>" . nl2br($step->desc) . "</td>";
            echo "<td>" . nl2br($step->expect) . "</td>";
            echo "</tr>";
        }
        ?>
        </table>
      </fieldset>
      <!--
      <fieldset>
        <legend><?php echo $lang->testcase->legendAttatch;?></legend>
        <div><?php foreach($case->files as $file) echo html::a($file->fullPath, $file->title, '_blank');?></div>
      </fieldset>
      -->

      <?php include '../../common/action.html.php';?>
      <fieldset>
        <legend><?php echo $lang->testcase->legendAction;?></legend>
        <div class='a-center' style='font-size:16px; font-weight:bold'>
         <?php
         common::printLink('testcase', 'edit',   "caseID=$case->id", $lang->testcase->buttonEdit);
         common::printLink('testcase', 'browse', "productID=$case->product", $lang->testcase->buttonToList);
         ?>
        </div>
      </fieldset>
    </div>
  </div>
  <div class='yui-b'>
    <fieldset>
      <legend><?php echo $lang->testcase->legendBasicInfo;?></legend>
      <table class='table-1 a-left fixed'>
        <tr>
          <td class='rowhead w-p20'><?php echo $lang->testcase->product;?></td>
          <td><?php if(!common::printLink('testcase', 'browse', "productID=$case->product", $productName)) echo $productName;?></td>
        </tr>
        <tr>
          <td class='rowhead w-p20'><?php echo $lang->testcase->module;?></td>
          <td>
          <?php 
          foreach($modulePath as $key => $module)
          {
              if(!common::printLink('testcase', 'browse', "productID=$case->product&browseType=byModule&param=$module->id", $module->name)) echo $module->name;
              if(isset($modulePath[$key + 1])) echo $lang->arrow;
          }
          ?>
          </td>
        </tr>
        <tr>
          <td class='rowhead w-p20'><?php echo $lang->testcase->type;?></td>
          <td><?php echo $lang->testcase->typeList[$case->type];?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->testcase->pri;?></td>
          <td><?php echo $case->pri;?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->testcase->status;?></td>
          <td><?php echo $lang->testcase->statusList[$case->status];?></td>
        </tr>
        <tr>
          <td class='rowhead'><?php echo $lang->testcase->story;?></td>
          <td class='nobr'>
            <?php
            if(isset($case->storyTitle)) echo html::a($this->createLink('story', 'view', "storyID=$case->story"), "#$case->story:$case->storyTitle");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend><?php echo $lang->testcase->legendOpenAndEdit;?></legend>
      <table class='table-1 a-left'>
        <tr>
          <td class='rowhead w-p20'><?php echo $lang->testcase->openedBy;?></td>
          <td><?php echo $case->openedBy . $lang->at . $case->openedDate;?></td>
        </tr>
        <tr>
          <td class='rowhead w-p20'><?php echo $lang->testcase->lblLastEdited;?></td>
          <td><?php if($case->lastEditedBy) echo $case->lastEditedBy . $lang->at . $case->lastEditedDate;?></td>
        </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend><?php echo $lang->testcase->legendVersion;?></legend>
      <div>
        <?php for($i = $case->version; $i >= 1; $i --) echo html::a(inlink('view', "caseID=$case->id&version=$i"), '#' . $i) . ' ';?>    
      </div>
    </fieldset>
  </div>
</div>
<?php include '../../common/footer.html.php';?>
