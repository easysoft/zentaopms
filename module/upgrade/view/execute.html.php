<?php
/**
 * The html template file of execute method of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
    <?php if(in_array($result, array('fail', 'sqlFail'))):?>
    <div class='modal-footer text-left'><?php echo $result == 'sqlFail' ? $lang->upgrade->afterExec : $lang->upgrade->afterDeleted;?> <?php echo html::a('#', $this->lang->refresh, '', "class='btn btn-sm' onclick='refreshPage(this)'");?></div>
    <?php endif;?>
  </div>
</div>
<script>
function refreshPage(obj)
{
    $(obj).attr('disabled', true);
    location.reload()
}
</script>
<?php include '../../common/view/footer.lite.html.php';?>
