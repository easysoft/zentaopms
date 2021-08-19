<?php
/**
 * The create view file of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     mr
 * @version     $Id: create.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <form id='mrForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->gitlab->common;?></th>
            <td class='required'><?php echo html::select('gitlabID', $gitlabHosts, '', "class='form-control'");?></td>
          </tr>
          <tr>
             <th><?php echo $lang->mr->sourceProject;?></th>
             <td class='required'>
               <div class='input-group'>
                 <?php echo html::select('sourceProject', array(''), '', "class='form-control chosen'");?>
                 <span class='input-group-addon fix-border'><?php echo $lang->mr->sourceBranch ?></span>
                 <?php echo html::select('sourceBranch', array(''), '', "class='form-control chosen'");?>
               </div>
             </td>
          </tr>
          <tr>
             <th><?php echo $lang->mr->targetProject;?></th>
             <td class='required'>
               <div class='input-group'>
                 <?php echo html::select('targetProject', array(''), '', "class='form-control chosen'");?>
                 <span class='input-group-addon fix-border'><?php echo $lang->mr->targetBranch ?></span>
                 <?php echo html::select('targetBranch', array(''), '', "class='form-control chosen'");?>
               </div>
             </td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->name;?></th>
            <td class='required'><?php echo html::input('title', '', "class='form-control'"); ?></td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->description; ?></th>
            <td colspan='1'><?php echo html::textarea('description', '', "rows='3' class='form-control'"); ?></td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->assignee;?></th>
            <td><?php echo html::select('assignee', $users, '', "class='form-control chosen'")?></td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->reviewer;?></th>
            <td><?php echo html::select('reviewer', $users, '', "class='form-control chosen'")?></td>
          </tr>
          <tr>
            <th></th>
            <td colspan='2' class='text-center form-actions'>
              <?php echo html::submitButton(); ?>
              <?php if(!isonlybody()) echo html::a(inlink('browse', ""), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
