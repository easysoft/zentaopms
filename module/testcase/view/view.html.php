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
    if(common::hasPriv('testcase', 'edit'))   echo html::a($this->createLink('testcase', 'edit',     "caseID=$case->id"), $lang->case->buttonEdit);
    if(common::hasPriv('testcase', 'browse')) echo html::a($this->createLink('testcase', 'browse',   "productID=$case->product"), $lang->case->buttonToList);
    ?>
    </div>
  </div>
</div>

<div class='yui-doc3 yui-t7'>
  <div class='yui-g'>  

    <div class='yui-u first'>  
      <fieldset>
        <legend><?php echo $lang->case->legendBasicInfo;?></legend>
        <table class='table-1 a-left' cellpadding='0' cellspacing='0'>
          <tr>
            <td class='rowhead'><?php echo $lang->case->labProductAndModule;?></td>
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
            <td class='rowhead'><?php echo $lang->case->type;?></td>
            <td><?php echo $lang->case->typeList[$case->type];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->case->pri;?></td>
            <td><?php echo $case->pri;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->case->status;?></td>
            <td><?php echo $lang->case->statusList[$case->status];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->case->story;?></td>
            <td><?php echo $case->story;?></td>
          </tr>
          
        </table>
      </fieldset>

      <!--
      <fieldset>
        <legend><?php echo $lang->case->legendMailto;?></legend>
        <div>mailto</div>
      </fieldset>

      <fieldset>
      <legend><?php echo $lang->case->legendAttatch;?></legend>
        <div>attatch</div>
      </fieldset>
      -->
      
    </div>  

    <div class='yui-u'>  
      <fieldset>
        <legend><?php echo $lang->case->legendOpenInfo;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td width='40%' class='rowhead'><?php echo $lang->case->openedBy;?></td>
            <td><?php echo $case->openedBy;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->case->openedDate;?></td>
            <td><?php echo $case->openedDate;?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->case->legendLastInfo;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td class='rowhead'><?php echo $lang->case->lastEditedBy;?></td>
            <td><?php echo $case->lastEditedBy;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->case->lastEditedDate;?></td>
            <td><?php echo $case->lastEditedDate;?></td>
          </tr>
        </table>
      </fieldset>

      <!--
      <fieldset>
        <legend><?php echo $lang->case->legendLinkBugs;?></legend>
        <div> linkcase </div>
      </fieldset>
      -->

    </div>  
  </div>
</div>  

<div class='yui-d0'>
  <fieldset>
    <legend><?php echo $lang->case->legendAction;?></legend>
    <div class='a-center' style='font-size:16px; font-weight:bold'>
      <?php
      if(common::hasPriv('testcase', 'edit'))   echo html::a($this->createLink('testcase', 'edit',     "caseID=$case->id"), $lang->case->buttonEdit);
      if(common::hasPriv('testcase', 'browse')) echo html::a($this->createLink('testcase', 'browse',   "productID=$case->product"), $lang->case->buttonToList);
      ?>
    </div>
  </fieldset>

  <fieldset>
    <legend><?php echo $lang->case->legendSteps;?></legend>
    <div class='content'>
    <?php echo nl2br($case->steps);?>
    </div>
  </fieldset>
  <?php include '../../common/action.html.php';?>

</div>
<?php include '../../common/footer.html.php';?>
