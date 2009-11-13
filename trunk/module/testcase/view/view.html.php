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

<div class='yui-d0'>
  <div id='titlebar'>
    CASE #<?php echo $case->id . $lang->colon . $case->title;?>
    <div class='f-right'>
    <?php
    if(common::hasPriv('testcase', 'edit'))   echo html::a($this->createLink('testcase', 'edit',     "caseID=$case->id"), $lang->testcase->buttonEdit);
    if(common::hasPriv('testcase', 'browse')) echo html::a($this->createLink('testcase', 'browse',   "productID=$case->product"), $lang->testcase->buttonToList);
    ?>
    </div>
  </div>
</div>

<div class='yui-doc3 yui-t7'>
  <div class='yui-g'>  

    <div class='yui-u first'>  
      <fieldset>
        <legend><?php echo $lang->testcase->legendBasicInfo;?></legend>
        <table class='table-1 a-left' cellpadding='0' cellspacing='0'>
          <tr>
            <td class='rowhead'><?php echo $lang->testcase->labProductAndModule;?></td>
            <td>
              <?php 
              echo $productName;
              if(!empty($modulePath)) echo $lang->arrow;
              foreach($modulePath as $key => $module)
              {
                  echo $module->name;
                  if(isset($modulePath[$key + 1])) echo $lang->arrow;
              }
              ?>
            </td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->testcase->type;?></td>
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
            <td><?php echo $case->story;?></td>
          </tr>
          
        </table>
      </fieldset>

      <!--
      <fieldset>
        <legend><?php echo $lang->testcase->legendMailto;?></legend>
        <div>mailto</div>
      </fieldset>

      <fieldset>
      <legend><?php echo $lang->testcase->legendAttatch;?></legend>
        <div>attatch</div>
      </fieldset>
      -->
      
    </div>  

    <div class='yui-u'>  
      <fieldset>
        <legend><?php echo $lang->testcase->legendOpenInfo;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td width='40%' class='rowhead'><?php echo $lang->testcase->openedBy;?></td>
            <td><?php echo $case->openedBy;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->testcase->openedDate;?></td>
            <td><?php echo $case->openedDate;?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->testcase->legendLastInfo;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td class='rowhead'><?php echo $lang->testcase->lastEditedBy;?></td>
            <td><?php echo $case->lastEditedBy;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->testcase->lastEditedDate;?></td>
            <td><?php echo $case->lastEditedDate;?></td>
          </tr>
        </table>
      </fieldset>

      <!--
      <fieldset>
        <legend><?php echo $lang->testcase->legendLinkBugs;?></legend>
        <div> linkcase </div>
      </fieldset>
      -->

    </div>  
  </div>
</div>  

<div class='yui-d0'>
  <fieldset>
    <legend><?php echo $lang->testcase->legendAction;?></legend>
    <div class='a-center' style='font-size:16px; font-weight:bold'>
      <?php
      if(common::hasPriv('testcase', 'edit'))   echo html::a($this->createLink('testcase', 'edit',     "caseID=$case->id"), $lang->testcase->buttonEdit);
      if(common::hasPriv('testcase', 'browse')) echo html::a($this->createLink('testcase', 'browse',   "productID=$case->product"), $lang->testcase->buttonToList);
      ?>
    </div>
  </fieldset>

  <fieldset>
    <legend><?php echo $lang->testcase->legendSteps;?></legend>
    <div class='content'>
    <?php echo nl2br($case->steps);?>
    </div>
  </fieldset>
  <?php include '../../common/action.html.php';?>

</div>
<?php include '../../common/footer.html.php';?>
