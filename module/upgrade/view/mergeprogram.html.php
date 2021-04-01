<?php
/**
 * The mergeProgram view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('weekend', $config->execution->weekend);?>
<div class='container'>
  <form method='post' target='hiddenwin'>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <strong><?php echo $lang->upgrade->mergeProgram;?></strong>
      </div>
      <div class='modal-body'>
        <?php if($type == 'productline'):?>
        <?php include './mergebyline.html.php';?>
        <?php elseif($type == 'product'):?>
        <?php include './mergebyproduct.html.php';?>
        <?php elseif($type == 'sprint'):?>
        <?php include './mergebysprint.html.php';?>
        <?php elseif($type == 'moreLink'):?>
        <div class='alert alert-info'>
          <?php
          printf($lang->upgrade->mergeSummary, $noMergedProductCount, $noMergedSprintCount);
          echo '<br />' . $lang->upgrade->mergeByMoreLink;
          ?>
        </div>
        <table class='table table-hover table-form'>
          <thead>
            <tr>
              <th><?php echo $lang->upgrade->project;?></th>
              <th class='divider'></th>
              <th class='w-p50'><?php echo $lang->upgrade->program;?></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($noMergedSprints as $sprintID => $sprint):?>
          <tr>
            <td><?php echo "{$sprint->name}" . html::hidden("sprints[]", $sprint->id);?></td>
            <td class='divider'><i class='icon icon-angle-double-right'></i></td>
            <td><?php echo html::select("projects[]", $sprint->projects, '', "class='form-control chosen'");?></td>
          </tr>
          <?php endforeach;?>
          </tbody>
          <tfoot>
            <tr>
              <td class='text-center' colspan='3'><?php echo html::submitButton();?></td>
            </tr>
          </tfoot>
        </table>
        <?php endif;?>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
