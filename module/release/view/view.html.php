<?php
/**
 * The view file of release module's view method of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<table class='cont-rt5'> 
  <caption>RELEASE #<?php echo $release->id . ' ' . $release->name;?></caption>
  <tr valign='top'>
    <td>
      <fieldset>
        <legend><?php echo $lang->release->desc;?></legend>
        <div class='content'><?php echo $release->desc;?></div>
      </fieldset>
      <?php include '../../common/view/action.html.php';?>
      <div class='a-center f-16px strong'>
      <?php
      $browseLink = $this->session->releaseList ? $this->session->releaseList : inlink('browse', "productID=$release->product");
      if(!$release->deleted)
      { 
          common::printLink('release', 'edit',   "releaseID=$release->id", $lang->edit);
          common::printLink('release', 'delete', "releaseID=$release->id", $lang->delete, 'hiddenwin');
      } 
      echo html::a($browseLink, $lang->goback);
      ?>
      </div>
      <table class='table-1 fixed'>
        <caption class='caption-t1'>
          <?php echo $lang->release->stories;?>
          <div class='f-right'><?php if(count($stories)) common::printLink('release', 'export', 'type=story', $lang->release->export, '', "class='export'");?></div>
        </caption>
        <tr>
          <th class='w-id'><?php echo $lang->idAB;?></th>
          <th class='w-pri'><?php echo $lang->priAB;?></th>
          <th><?php echo $lang->story->title;?></th>
          <th class='w-user'><?php echo $lang->openedByAB;?></th>
          <th class='w-hour'><?php echo $lang->story->estimateAB;?></th>
          <th class='w-hour'><?php echo $lang->statusAB;?></th>
          <th class='w-100px'><?php echo $lang->story->stageAB;?></th>
        </tr>
        <?php foreach($stories as $storyID => $story):?>
        <?php $storyLink = $this->createLink('story', 'view', "storyID=$story->id");?>
        <tr class='a-center'>
          <td><?php echo sprintf('%03d', $story->id);?></td>
          <td><span class='<?php echo 'pri' . $lang->story->priList[$story->pri]?>'><?php echo $lang->story->priList[$story->pri];?></span></td>
          <td class='a-left nobr'><?php echo html::a($storyLink,$story->title, '', "class='preview'");?></td>
          <td><?php echo $users[$story->openedBy];?></td>
          <td><?php echo $story->estimate;?></td>
          <td class='<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
          <td><?php echo $lang->story->stageList[$story->stage];?></td>
        </tr>
        <?php endforeach;?>
        <tr><td colspan="7" class='a-left strong'><?php echo sprintf($lang->release->finishStories, count($stories));?></td></tr>
      </table>
      <table class='table-1 fixed'>
        <caption class='caption-t1'>
          <?php echo $lang->release->bugs;?>
          <div class='f-right'><?php if(count($bugs)) common::printLink('release', 'export', 'type=bug', $lang->release->export, '', "class='export'");?></div>
        </caption>
        <tr>
          <th class='w-id'>       <?php echo $lang->idAB;?></th>
          <th><?php echo $lang->bug->title;?></th>
          <th class='w-100px'><?php echo $lang->bug->status;?></th>
          <th class='w-user'><?php echo $lang->openedByAB;?></th>
          <th class='w-date'><?php echo $lang->bug->openedDateAB;?></th>
          <th class='w-user'><?php echo $lang->bug->resolvedByAB;?></th>
          <th class='w-date'><?php echo $lang->bug->resolvedDateAB;?></th>
        </tr>
        <?php foreach($bugs as $bug):?>
        <?php $bugLink = $this->createLink('bug', 'view', "bugID=$bug->id");?>
        <tr class='a-center'>
          <td><?php echo sprintf('%03d', $bug->id);?></td>
          <td class='a-left nobr'><?php echo html::a($bugLink, $bug->title, '', "class='preview'");?></td>
          <td><?php echo $lang->bug->statusList[$bug->status];?></td>
          <td><?php echo $users[$bug->openedBy];?></td>
          <td><?php echo substr($bug->openedDate, 5, 11)?></td>
          <td><?php echo $users[$bug->resolvedBy];?></td>
          <td><?php echo substr($bug->resolvedDate, 5, 11)?></td>
        </tr>
        <?php endforeach;?>
        <tr><td colspan="7" class='a-left strong'><?php echo sprintf($lang->release->resolvedBugs, count($bugs));?></td></tr>
      </table>
    </td>
    <td class="divider"></td>
    <td class="side">
      <fieldset>
      <legend><?php echo $lang->release->basicInfo?></legend>
      <table class='table-1 a-left'>
        <tr>
          <th width='25%' class='a-right'><?php echo $lang->release->product;?></th>
          <td><?php echo $release->productName;?></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->release->name;?></th>
          <td class='<?php if($release->deleted) echo 'deleted';?>'><?php echo $release->name;?></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->release->build;?></th>
          <td><?php echo $release->buildName;?></td>
        </tr>  
        <tr>
          <th class='rowhead'><?php echo $lang->release->date;?></th>
          <td><?php echo $release->date;?></td>
        </tr>
      </table>
      </fieldset>
    </td>
  </tr>
</table> 
<?php include '../../common/view/footer.html.php';?>
