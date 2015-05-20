<?php
/**
 * The delete view file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <strong><?php echo $lang->user->delete;?></strong>
  </div>
</div>
<form class='form-condensed' method='post' target='hiddenwin' style='padding: 20px 5% 40px'>
  <table class='w-p100 table-form'>
    <tr>
      <th class='w-100px text-right'>
        <?php echo $lang->user->verifyPassword;?>
      </th>
      <td>
        <div class="required required-wrapper"></div>
        <?php echo html::input('verifyPassword', '', "class='form-control' placeholder='{$lang->user->placeholder->verify}'");?>
      </td>
      <td class='w-100px'><?php echo html::submitButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
