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
<style>#produc{width:245px} #story {width:90%}</style>
<script language='Javascript'>
storyID ='<?php echo $case->story;?>'
/* 加载产品对应的模块和需求。*/
function loadAll(productID)
{
    loadModuleMenu(productID);
    loadStory(productID);
}

/* 加载模块。*/
function loadModuleMenu(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case');
    $('#moduleIdBox').load(link);
}

/* 加载需求列表。*/
function loadStory(productID)
{
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&moduleID=0&storyID=' + storyID);
    $('#storyIdBox').load(link);
}

</script>

<form method='post'>
<div class='yui-d0'>
  <div id='titlebar'>
    <div id='main'>
    CASE #<?php echo $case->id . $lang->colon;?>
    <?php echo html::input('title', $case->title, 'class=text-1');?>
    </div>
    <div><?php echo html::submitButton();?></div>
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
              <?php echo html::select('product', $products, $productID, "onchange=loadAll(this.value); class='select-2'");?>
              <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $currentModuleID);?></span>
            </td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->testcase->type;?></td>
            <td><?php echo html::select('type', (array)$lang->testcase->typeList, $case->type, 'class=select-2');?>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->testcase->pri;?></td>
            <td><?php echo html::select('pri', (array)$lang->testcase->priList, $case->pri, 'class=select-2');?>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->testcase->status;?></td>
            <td><?php echo html::select('status', (array)$lang->testcase->statusList, $case->status, 'class=select-2');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->testcase->story;?></td>
            <td class='a-left'><span id='storyIdBox'><?php echo html::select('story', $stories, $case->story, 'class=select-3');?></span></td>
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
    <legend><?php echo $lang->testcase->legendSteps;?></legend>
    <div class='a-center'>
      <textarea name='steps' rows='5' class='area-1'><?php echo $case->steps;?></textarea>
    </div>
  </fieldset>
  <fieldset>
    <legend><?php echo $lang->testcase->legendComment;?></legend>
    <div class='a-center'>
      <textarea name='comment' rows='4' class='area-1'></textarea></td>
    </div>
  </fieldset>
  <fieldset>
    <legend><?php echo $lang->testcase->legendAction;?></legend>
    <div class='a-center'>
      <?php echo html::submitButton();?>
      <input type='button' value='<?php echo $lang->testcase->buttonToList;?>' class='button-s' 
           onclick='location.href="<?php echo $this->createLink('testcase', 'browse', "productID=$productID");?>"' />
    </div>
  </fieldset>
</div>
<?php include '../../common/footer.html.php';?>
