<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php js::set('batchCreateNum', $config->task->batchCreate);?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['task']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchCreate']);?></small> <?php echo $lang->task->common . $lang->colon . $lang->task->batchCreate;?></strong>
    <small class='text-muted'><?php echo html::icon($lang->icons['project']) . ' ' . $lang->task->project . $lang->colon . ' ' . $projectName;?></small>
  </div>
</div>
<form class='form-condensed' method='post' target='hiddenwin'>
  <table class='table table-fixed table-form'>
    <thead>
      <tr class='text-center'>
        <th class='w-30px'><?php echo $lang->idAB;?></th> 
        <th><?php echo $lang->task->story;?></th>
        <th class='required'><?php echo $lang->task->name;?></th>
        <th class='w-70px required'><?php echo $lang->typeAB;?></th>
        <th class='w-80px'><?php echo $lang->task->module?></th>
        <th class='w-80px'><?php echo $lang->task->assignedTo;?></th>
        <th class='w-50px'><?php echo $lang->task->estimateAB;?></th>
        <th class='w-p20'><?php echo $lang->task->desc;?></th>
        <th class='w-70px'><?php echo $lang->task->pri;?></th>
      </tr>
    </thead>

    <?php
    $stories['ditto'] = $this->lang->task->ditto; 
    $lang->task->typeList['ditto'] = $lang->task->ditto; 
    $members['ditto'] = $lang->task->ditto;
    $modules['ditto'] = $lang->task->ditto;
    ?>
    <?php for($i = 0; $i < $config->task->batchCreate; $i++):?>
    <?php 
    if($i == 0)
    {
        $story  = $storyID;
        $type   = '';
        $member = '';
        $module = '';
    }
    else
    {
        $story = $type = $member = $module = 'ditto';
    }
    ?>
    <?php $pri = 3;?>
    <tr>
      <td class='text-center'><?php echo $i+1;?></td>
      <td style='overflow: visible'>
        <?php echo html::select("story[$i]", $stories, $story, "class='form-control'");?>
      </td>
      <td>
        <div class='input-group'>
          <span class='input-group-btn'>
          <a href='javascript:copyStoryTitle(<?php echo $i;?>)' class='btn' title='<?php echo $lang->task->copyStoryTitle; ?>'><i class='icon-double-angle-right'></i></a>
          </span>
          <?php echo html::input("name[$i]", '', 'class=form-control');?>
        </div>
      </td>
      <td><?php echo html::select("type[$i]", $lang->task->typeList, $type, 'class=form-control');?></td>
      <td><?php echo html::select("module[$i]", $modules, $module, 'class=form-control')?></td>
      <td><?php echo html::select("assignedTo[$i]", $members, $member, 'class=form-control');?></td>
      <td><?php echo html::input("estimate[$i]", '', 'class=form-control text-center');?></td>
      <td>
        <div class='form-control' contenteditable='true' data-sync-target='#desc\[<?php echo $i;?>\]'></div>
        <?php echo html::textarea("desc[$i]", '', "rows='1' class='form-control hidden'");?>
      </td>
      <td><?php echo html::select("pri[$i]", (array)$lang->task->priList, $pri, 'class=form-control');?></td>
    </tr>
    <?php endfor;?>
    <tr><td colspan='8' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
