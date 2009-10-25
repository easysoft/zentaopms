<?php
/**
 * The edit file of bug module of ZenTaoMS.
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
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<script language='Javascript'>
function loadModuleMenu(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug');
    $('#moduleIdBox').load(link);
}
</script>
<form method='post'>
<div class='yui-d0'>
  <div id='titlebar'>
    <div id='main'>
    BUG #<?php echo $bug->id . $lang->colon;?>
    <?php echo html::input('title', $bug->title, 'class=text-1');?>
    </div>
    <div><?php echo html::submitButton()?></div>
  </div>
</div>

<div class='yui-doc3 yui-t7'>
  <div class='yui-g'>  

    <div class='yui-u first'>  
      <fieldset>
        <legend><?php echo $lang->bug->legendBasicInfo;?></legend>
        <table class='table-1 a-left' cellpadding='0' cellspacing='0'>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->labProductAndModule;?></td>
            <td>
              <?php echo html::select('product', $products, $productID, "onchange=loadModuleMenu(this.value); class='select-2'");?>
              <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $currentModuleID);?></span>
            </td>
          </tr>

          <tr>
            <td class='rowhead'><?php echo $lang->bug->type;?></td>
            <td><?php echo html::select('type', (array)$lang->bug->typeList, $bug->type, 'class=select-2');?>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->severity;?></td>
            <td><?php echo html::select('severity', (array)$lang->bug->severityList, $bug->severity, 'class=select-2');?>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->os;?></td>
            <td><?php echo html::select('os', (array)$lang->bug->osList, $bug->os, 'class=select-2');?></td>
          </tr>

          <tr>
            <td class='rowhead'><?php echo $lang->bug->status;?></td>
            <td><?php echo html::select('status', (array)$lang->bug->statusList, $bug->status, 'class=select-2');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->assignedTo;?></td>
            <td><?php echo html::select('assignedTo', $users, $bug->assignedTo, 'class=select-2');?></td>
          </tr>
          <tr>
            <td width='40%' class='rowhead'><?php echo $lang->bug->assignedDate;?></td>
            <td><?php echo $bug->assignedDate;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->lastEditedBy;?></td>
            <td><?php echo $bug->lastEditedBy;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->lastEditedDate;?></td>
            <td><?php echo $bug->lastEditedDate;?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendStoryAndTask;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->story;?></td>
            <td></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->task;?></td>
            <td></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendMailto;?></legend>
        <div>&nbsp;</div>
      </fieldset>

      <fieldset>
      <legend><?php echo $lang->bug->legendAttatch;?></legend>
        <div>&nbsp;</div>
      </fieldset>
      
    </div>  

    <div class='yui-u'>  
      <fieldset>
        <legend><?php echo $lang->bug->legendOpenInfo;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td width='40%' class='rowhead'><?php echo $lang->bug->openedBy;?></td>
            <td><?php echo $users[$bug->openedBy];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->openedDate;?></td>
            <td><?php echo $bug->openedDate;?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->openedBuild;?></td>
            <td><?php echo html::input('openedBuild', $bug->openedBuild, 'class=text-2');?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendResolveInfo;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td width='40%' class='rowhead'><?php echo $lang->bug->resolvedBy;?></td>
            <td><?php echo html::select('resolvedBy', $users, $bug->resolvedBy, 'class=select-2');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->resolvedDate;?></td>
            <td><?php echo html::input('resolvedDate', $bug->resolvedDate, 'class=text-2');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->resolvedBuild;?></td>
            <td><?php echo html::input('resolvedBuild', $bug->resolvedBuild, 'class=text-2');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->resolution;?></td>
            <td><?php echo html::select('resolution', array(''=> '') + (array)$lang->bug->resolutionList, $bug->resolution, 'class=select-2');?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendCloseInfo;?></legend>
        <table class='table-1 a-left'>
          <tr>
            <td width='40%' class='rowhead'><?php echo $lang->bug->closedBy;?></td>
            <td><?php echo html::select('closedBy', $users, $bug->closedBy, 'class=select-2');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->bug->closedDate;?></td>
            <td><?php echo html::input('closedDate', $bug->closedDate, 'class=text-2');?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendLinkBugs;?></legend>
        <div>&nbsp;</div>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->bug->legendCases;?></legend>
        <div>&nbsp;</div>
      </fieldset>

    </div>  
  </div>
</div>  

<div class='yui-d0'>
  <fieldset>
  <legend><?php echo $lang->bug->legendComment;?></legend>
    <table class='table-1'>
      <tr>
        <td width='90%'><textarea name='comment' rows='4' class='area-1'></textarea></td>
        <td>
          <?php echo html::submitButton();?>
          <input type='button' value='<?php echo $lang->bug->buttonToList;?>' class='button-s' 
           onclick='location.href="<?php echo $this->createLink('bug', 'browse', "productID=$productID");?>"' />
        </td>
      </tr>
    </table>
  </fieldset>

  <fieldset>
  <legend><?php echo $lang->bug->legendSteps;?></legend>
    <table class='table-1'>
      <tr>
        <td width='90%'><textarea name='steps' rows='4' class='area-1'><?php echo $bug->steps;?></textarea></td>
        <td></td>
      </tr>
    </table>
  </fieldset>
</div>
<?php include '../../common/footer.html.php';?>
