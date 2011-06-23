<?php
/**
 * The create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<form method='post' enctype='multipart/form-data' target='hiddenwin'>
  <table align='center' class='table-1'> 
    <caption><?php echo $lang->story->create;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->story->product;?></th>
      <td>
        <?php echo html::select('product', $products, $productID, "onchange=loadProduct(this.value); class='select-3'");?>
        <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID);?></span>
      </td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->story->plan;?></th>
      <td><span id='planIdBox'><?php echo html::select('plan', $plans, $planID, 'class=select-3');?></span></td>
    </tr>
   <tr>
      <th class='rowhead'><?php echo $lang->story->title;?></th>
      <td><?php echo html::input('title', $title, "class='text-1'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->story->spec;?></th>
      <td><?php echo html::textarea('spec', $spec, "rows='9' class='text-1'");?><br /><?php echo $lang->story->specTemplate;?></td>
    </tr>  
       <tr>
      <th class='rowhead'><?php echo $lang->story->verify;?></th>
      <td><?php echo html::textarea('verify', $verify, "rows='6' class='text-1'");?></td>
    </tr> 
     <tr>
      <th class='rowhead'><?php echo $lang->story->pri;?></th>
      <td><?php echo html::select('pri', (array)$lang->story->priList, $pri, 'class=select-3');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->story->estimate;?></th>
      <td><?php echo html::input('estimate', $estimate, "class='text-3'");?></td>
    </tr> 
    <tr>
      <th class='rowhead'><?php echo $lang->story->reviewedBy;?></th>
      <td><?php echo html::select('assignedTo', $users, '', 'class=select-3') . html::checkbox('needNotReview', $lang->story->needNotReview, '', "id='needNotReview'");?></td>
    </tr>  
     <tr>
      <th class='rowhead'><nobr><?php echo $lang->story->mailto;?></nobr></th>
      <td><?php echo html::input('mailto', $mailto, 'class="text-1"');?></td>
    </tr>

    <tr>
      <th class='rowhead'><nobr><?php echo $lang->story->keywords;?></nobr></th>
      <td><?php echo html::input('keywords', $keywords, 'class="text-1"');?></td>
    </tr>
   <tr>
      <th class='rowhead'><?php echo $lang->story->legendAttatch;?></th>
      <td><?php echo $this->fetch('file', 'buildform');?></td>
    </tr>  
    <tr><td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
