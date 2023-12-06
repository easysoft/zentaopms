<?php
/**
 * The suspend file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     execution
 * @version     $Id: suspend.html.php 935 2013-01-16 07:49:24Z wwccss@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='prefix label-id'><strong><?php echo $execution->id;?></strong></span>
      <?php echo isonlybody() ? ("<span title='$execution->name'>" . $execution->name . '</span>') : html::a($this->createLink('execution', 'view', 'execution=' . $execution->id), $execution->name, '_blank');?>
      <?php if(!isonlybody()):?>
      <small> <?php echo $lang->arrow . $lang->execution->close;?></small>
      <?php endif;?>
    </h2>
  </div>
  <form class='load-indicator main-form' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tbody>
        <tr class='hide'>
          <th class='w-50px'><?php echo $lang->execution->status;?></th>
          <td><?php echo html::hidden('status', 'closed');?></td>
        </tr>
        <?php $this->printExtendFields($execution, 'table');?>
        <tr>
          <th><?php echo $lang->execution->realEnd;?></th>
          <td>
            <div class='w-150px'>       
              <?php echo html::input('realEnd',(!empty($execution->realEnd) && $execution->realEnd != '0000-00-00' ? $execution->realEnd : date('Y-m-d')), "class='form-control form-date' required");?>
            </div>         
          </td>
        </tr>        
        <tr>
          <th class='w-50px'><?php echo $lang->comment;?></th>
          <td><?php echo html::textarea('comment', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
        </tr>
        <tr>
          <td class='text-center form-actions' colspan='2'><?php echo html::submitButton($lang->execution->close . $lang->executionCommon) . html::linkButton($lang->goback, $this->session->taskList, 'self', '', 'btn btn-wide'); ?></td>
        </tr>
      </tbody>
    </table>
  </form>
   <hr class='small' />
  <div class='main'><?php include '../../common/view/action.html.php';?></div>
</div>
<?php include '../../common/view/footer.html.php';?>
