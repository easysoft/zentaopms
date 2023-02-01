<?php
/**
 * The create file of snapshot of zanode.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php if($task):?>
<?php js::set('taskID', $task->task);?>
<?php js::set('nodeID', $node->id);?>
<?php js::set('zanodeLang', $lang->zanode); ?>
<style>.body-modal #mainContent{width:90%}
</style>
<?php endif;?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span><?php echo $lang->zanode->createSnapshot;?></span>
      </h2>
    </div>
    <?php if($task):?>
    <h5 class='text-center status-title'><?php echo $lang->zanode->pending;?></h5>
    <div class="progress progress-striped">
      <div class="progress-bar progress-bar-success rate" role="progressbar" aria-valuenow="<?php echo $task->rate;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $task->rate;?>%">
      </div>
    </div>
    <?php
        $link = helper::createLink('zanode', 'create', "hostID={$node->parent}");
        $link = str_replace('onlybody=yes', '', $link);
        $link = trim($link, '?');
    ?>
    <h6 class='hide text-center success'><?php echo $lang->zanode->createSnapshotSuccess . html::a($link, $lang->zanode->createSnapshotButton, "_parent", "style='color:#2e7fff;'");?></h6>
    <h6 class='hide text-center fail'><?php echo $lang->zanode->createSnapshotFail;?></h6>
    <?php else:?>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->zanode->snapshotName;?></th>
          <td class='required'><?php echo html::input('name', '', "class='form-control'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->zanode->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', '', "rows='6' class='form-control'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center'>
            <?php echo html::submitButton('', "");?>
          </td>
        </tr>
      </table>
    </form>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
