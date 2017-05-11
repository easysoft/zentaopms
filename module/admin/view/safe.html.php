<?php
/**
 * The safe view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <ul class='nav'>
    <li class='active'><?php common::printLink('admin', 'safe', '', $lang->admin->safe->set);?></li>
    <li><?php common::printLink('admin', 'checkWeak', '', $lang->admin->safe->checkWeak);?></li>
  </ul>
</div>
<div class='container mw-800px'>
  <form method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='w-130px'><?php echo $lang->admin->safe->password?></th>
        <td><?php echo html::radio('mode', $lang->admin->safe->modeList, isset($config->safe->mode) ? $config->safe->mode : 0, "onclick=showModeRule(this.value)")?></td>
        <td><?php echo $lang->admin->safe->noticeMode?></td>
      </tr>
      <tr id='mode1Rule' class='hidden'>
        <th></th>
        <td colspan='2'><span style='color:#03b8cf;font-weight:bold;'><?php echo $lang->admin->safe->modeRuleList[1] . $lang->admin->safe->noticeStrong;?></span></td>
      </tr>
      <tr id='mode2Rule' class='hidden'>
        <th></th>
        <td colspan='2'><span style='color:#03b8cf;font-weight:bold;'><?php echo $lang->admin->safe->modeRuleList[2] . $lang->admin->safe->noticeStrong;?></span></td>
      </tr>
      <tr>
        <th><?php echo $lang->admin->safe->weak?></th>
        <td colspan='2'><?php echo html::textarea('weak', $config->safe->weak, "class='form-control' rows='4'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->admin->safe->changeWeak?></th>
        <td colspan='2'><?php echo html::radio('changeWeak', $lang->admin->safe->modifyPasswordList, isset($config->safe->changeWeak) ? $config->safe->changeWeak : 0)?></td>
      </tr>
      <tr>
        <th><?php echo $lang->admin->safe->modifyPasswordFirstLogin?></th>
        <td colspan='2'><?php echo html::radio('modifyPasswordFirstLogin', $lang->admin->safe->modifyPasswordList, isset($config->safe->modifyPasswordFirstLogin) ? $config->safe->modifyPasswordFirstLogin : 0)?></td>
      </tr>
      <tr>
        <th></th>
        <td colspan='2'><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<script>
$(function()
{
    var mode = $("input[name='mode']:checked").val();
    showModeRule(mode);
});
function showModeRule(mode)
{
    if(mode == 0)
    {
        $('#mode1Rule').addClass('hidden');
        $('#mode2Rule').addClass('hidden');
    }
    else
    {
        mode == 1 ? $('#mode1Rule').removeClass('hidden') : $('#mode1Rule').addClass('hidden');
        mode == 2 ? $('#mode2Rule').removeClass('hidden') : $('#mode2Rule').addClass('hidden');
    }
}
</script>
<?php include '../../common/view/footer.html.php';?>
