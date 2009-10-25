<?php
/**
 * The edit file of case module of ZenTaoMS.
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
<script language='Javascript'>
function loadModuleMenu(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case');
    $('#moduleIdBox').load(link);
}
</script>
<form method='post'>
<div class='yui-d0'>
  <div id='titlebar'>
    CASE #<?php echo $case->id . $lang->colon;?>
    <?php echo html::input('title', $case->title, 'class=text-5');?>
    <div class='f-right'><input type='submit' value='<?php echo $lang->save;?>' name='submit' /></div>
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
              <?php echo html::select('productID', $products, $productID, "onchange=loadModuleMenu(this.value); class='select-2'");?>
              <span id='moduleIdBox'><?php echo html::select('moduleID', $moduleOptionMenu, $currentModuleID);?></span>
            </td>
          </tr>

          <tr>
            <td class='rowhead'><?php echo $lang->case->type;?></td>
            <td><?php echo html::select('type', (array)$lang->case->typeList, $case->type, 'class=select-2');?>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->case->pri;?></td>
            <td><?php echo html::select('pri', (array)$lang->case->priList, $case->pri, 'class=select-2');?>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->case->status;?></td>
            <td><?php echo html::select('status', (array)$lang->case->statusList, $case->status, 'class=select-2');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->case->story;?></td>
            <td><?php echo $case->story;?></td>
          </tr>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->case->legendMailto;?></legend>
        <div>mailto</div>
      </fieldset>

      <fieldset>
      <legend><?php echo $lang->case->legendAttatch;?></legend>
        <div>attatch</div>
      </fieldset>
      
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

      <fieldset>
        <legend><?php echo $lang->case->legendLinkBugs;?></legend>
        <div> linkcase </div>
      </fieldset>

    </div>  
  </div>
</div>  

<div class='yui-d0'>
  <fieldset>
    <legend><?php echo $lang->case->legendSteps;?></legend>
    <div class='a-center'>
      <textarea name='steps' rows='10' class='area-1'><?php echo $case->steps;?></textarea>
    </div>
  </fieldset>

  <fieldset>
    <legend><?php echo $lang->case->legendAction;?></legend>
    <div class='a-center'>
      <input type='submit' value='<?php echo $lang->save;?>' name='submit' />
      <input type='button' value='<?php echo $lang->case->buttonToList;?>' />
    </div>
  </fieldset>

  <fieldset>
  <legend><?php echo $lang->case->legendHistory;?></legend>
    <table class='table-1' cellpadding='0' cellspacing='0'>
      <tr>
      </tr>
    </table>
  </fieldset>

</div>
<?php include '../../common/footer.html.php';?>
