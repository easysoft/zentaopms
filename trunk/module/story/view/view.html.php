<?php
/**
 * The view file of story module of ZenTaoMS.
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
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>

<div class='yui-d0'>
  <div id='titlebar'>
    <div id='main'>STORY #<?php echo $story->id . $lang->colon . $story->title;?></div>
    <div>
    <?php
    if(common::hasPriv('story', 'edit')) echo html::a($this->createLink('story', 'edit', "storyID=$story->id"),  $lang->story->buttonEdit);
    if(common::hasPriv('product', 'browse'))
    {
        if($app->session->storyList != '') echo html::a($app->session->storyList, $lang->story->buttonToList);
        else echo html::a($this->createLink('product', 'browse', "productID=$story->product&moduleID=$story->module"), $lang->story->buttonToList);
    }
    ?>
    </div>
  </div>
</div>

<div class='yui-d0 yui-t6'>
  <div class='yui-main'>
    <div class='yui-b'>
      <fieldset>
        <legend><?php echo $lang->story->legendSpec;?></legend>
        <div><?php echo nl2br($story->spec);?></div>
      </fieldset>
      <?php include '../../common/action.html.php';?>
      <fieldset>
        <legend><?php echo $lang->story->legendAction;?></legend>
        <div class='a-center' style='font-size:16px; font-weight:bold'>
          <?php
          if(common::hasPriv('story', 'edit')) echo html::a($this->createLink('story', 'edit', "storyID=$story->id"),  $lang->story->buttonEdit);
          if(common::hasPriv('product', 'browse'))
          {
              if($app->session->storyList != '') echo html::a($app->session->storyList, $lang->story->buttonToList);
              else echo html::a($this->createLink('product', 'browse', "productID=$story->product&moduleID=$story->module"), $lang->story->buttonToList);
          }
          ?>
        </div>
      </fieldset>
    </div>
  </div>
  <div class='yui-b'>
   <fieldset>
     <legend><?php echo $lang->story->legendBasicInfo;?></legend>
     <table class='table-1 a-left' cellpadding='0' cellspacing='0'>
       <tr>
         <td class='rowhead'><?php echo $lang->story->labProductAndModule;?></td>
         <td>
           <?php
           echo $product->name;
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
         <td class='rowhead'><?php echo $lang->story->plan;?></td>
         <td><?php echo $plan;?></td>
       </tr>
       <!--
       <tr>
         <td class='rowhead'><?php echo $lang->story->type;?></td>
         <td><?php //echo $lang->story->typeList->{$story->type};?></td>
       </tr>
       -->
       
       <tr>
         <td class='rowhead'><?php echo $lang->story->status;?></td>
         <td><?php $lang->show($lang->story->statusList, $story->status);?></td>
       </tr>
       <tr>
         <td class='rowhead'><?php echo $lang->story->assignedTo;?></td>
         <td><?php echo $users[$story->assignedTo];?></td>
       </tr>
       <tr>
         <td width='40%' class='rowhead'><?php echo $lang->story->assignedDate;?></td>
         <td><?php echo $story->assignedDate;?></td>
       </tr>
       <tr>
         <td class='rowhead'><?php echo $lang->story->lastEditedBy;?></td>
         <td><?php echo $users[$story->lastEditedBy];?></td>
       </tr>
       <tr>
         <td class='rowhead'><?php echo $lang->story->lastEditedDate;?></td>
         <td><?php echo $story->lastEditedDate;?></td>
       </tr>
     </table>
   </fieldset>

   <fieldset>
     <legend><?php echo $lang->story->legendMailto;?></legend>
     <div></div>
   </fieldset>

   <fieldset>
   <legend><?php echo $lang->story->legendAttatch;?></legend>
     <div>&nbsp;</div>
   </fieldset>
   <fieldset>
     <legend><?php echo $lang->story->legendOpenInfo;?></legend>
     <table class='table-1 a-left'>
       <tr>
         <td width='40%' class='rowhead'><?php echo $lang->story->openedBy;?></td>
         <td><?php echo $users[$story->openedBy];?></td>
       </tr>
       <tr>
         <td class='rowhead'><?php echo $lang->story->openedDate;?></td>
         <td><?php echo $story->openedDate;?></td>
       </tr>
     </table>
   </fieldset>

   <fieldset>
     <legend><?php echo $lang->story->legendProjectAndTask;?></legend>
     <table class='table-1 a-left'>
       <tr>
         <td class='rowhead'><?php echo $lang->story->project;?></td>
         <td><?php //echo $story->project;?></td>
       </tr>
       <tr>
         <td class='rowhead'><?php echo $lang->story->tasks;?></td>
         <td><?php //echo $story->tasks;?></td>
       </tr>
     </table>
   </fieldset>

   <!--
   <fieldset>
     <legend><?php echo $lang->story->legendCloseInfo;?></legend>
     <table class='table-1 a-left'>
       <tr>
         <td width='40%' class='rowhead'><?php echo $lang->story->closedBy;?></td>
         <td><?php echo $users[$story->closedBy];?></td>
       </tr>
       <tr>
         <td class='rowhead'><?php echo $lang->story->closedDate;?></td>
         <td><?php echo $story->closedDate;?></td>
       </tr>
        </table>
   </fieldset>
   -->

   <fieldset>
     <legend><?php echo $lang->story->legendLinkBugs;?></legend>
     <div>&nbsp;</div>
   </fieldset>

   <fieldset>
     <legend><?php echo $lang->story->legendCases;?></legend>
     <div>&nbsp;</div>
   </fieldset>
  </div>
</div>
<?php include '../../common/footer.html.php';?>
