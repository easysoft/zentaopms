<?php
/**
 * The html template file of execute method of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: execute.html.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='modal-header'>
      <strong><?php echo $lang->upgrade->result;?></strong>
    </div>
    <div class='modal-body'>
      <?php if(in_array($result, array('fail', 'sqlFail'))):?>
      <div class='alert alert-danger mgb-10'><strong><?php echo $lang->upgrade->fail?></strong></div>
      <?php echo html::textarea('errors', join("\n", $errors), "rows='10' class='form-control' readonly");?>
      <?php endif;?>
    </div>
    <form method='post' onsubmit="submit.disabled=1">
      <?php echo html::hidden('fromVersion', $fromVersion);?>
      <?php if(in_array($result, array('fail', 'sqlFail'))):?>
      <?php $buttonType = $this->app->rawMethod == 'execute' ? 'submitButton' : 'commonButton';?>
      <div class='modal-footer text-left'><?php echo $result == 'sqlFail' ? $lang->upgrade->afterExec : $lang->upgrade->afterDeleted;?> <?php echo html::{$buttonType}($lang->refresh, "onclick='refreshPage(this)'", 'btn btn-sm');?></div>
      <?php endif;?>
    </form>
  </div>
</div>
<div class="modal fade" id='progress'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h1 class='title'>1%</h1>
        <div class="progress">
          <div class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 1%">
          </div>
        </div>
        <div id='logBox'></div>
        <span><?php echo $lang->upgrade->upgradingTip;?></span>
      </div>
    </div>
  </div>
</div>
<script>
function refreshPage(obj)
{
    <?php if($this->app->rawMethod == 'execute'):?>
    $(obj).addClass('disabled');

    $('#progress').modal('show');
    updateProgressInterval();
    updateProgress();
    <?php else:?>
    location.href = location.href;
    <?php endif;?>
}
</script>
<?php include '../../common/view/footer.lite.html.php';?>
