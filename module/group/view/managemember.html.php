<?php
/**
 * The manage member view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managemember.html.php 4627 2013-04-10 05:42:20Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2 title='<?php echo $group->name;?>'>
      <span class='label label-id'><?php echo $group->id;?></span>
      <?php echo $group->name;?>
    </h2>
  </div>
  <div class='main-row'>
    <div class="side-col">
      <div class='side-body'>
        <div class='panel panel-sm'>
          <div class='panel-heading nobr'><strong><?php echo $lang->dept->common;?></strong></div>
          <?php echo $deptTree;?>
        </div>
      </div>
    </div>
    <div class="main-col">
      <form class='main-table table-members' method='post' target='hiddenwin'>
        <table class='table table-form'> 
          <?php if($groupUsers):?>
          <tr>
            <th class='w-140px'>
              <div class="checkbox-primary checkbox-inline checkbox-right check-all">
                <input type='checkbox' id='allInsideChecker' checked />
                <label class='text-right' for='allInsideChecker'><?php echo $lang->group->inside;?></label>
              </div>
            </th>
            <td id='group' class='pv-10px'><?php $i = 1;?>
              <?php foreach($groupUsers as $account => $realname):?>
              <div class='group-item'><?php echo html::checkbox('members', array($account => $realname), $account);?></div>
              <?php endforeach;?>
            </td>
          </tr>
          <?php endif;?>
          <tr>
            <th class='w-140px'>
              <div class="checkbox-primary checkbox-inline checkbox-right check-all">
                <input type='checkbox' id='allOutSideChecker'>
                <label class='text-right' for='allOutSideChecker'><?php echo $lang->group->outside;?></label>
              </div>
            </th>
            <td id='other' class='pv-10px'><?php $i = 1;?>
              <?php foreach($otherUsers as $account => $realname):?>
              <div class='group-item'><?php echo html::checkbox('members', array($account => $realname), '');?></div>
              <?php endforeach;?>
            </td>
          </tr>
          <tr>
            <td class='text-center form-actions' colspan='2'>
              <?php 
              echo html::submitButton();
              echo html::linkButton($lang->goback, $this->createLink('group', 'browse'));
              echo html::hidden('foo'); // Just a var, to make sure $_POST is not empty.
              ?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
