<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-500px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon('cloud');?></span>
      <strong><?php echo $lang->admin->bind->caption;?></strong>
    </div>
  </div>
  <form class='form-condensed mw-400px' method="post" target="hiddenwin">
    <table align='center' class='table table-form'>
      <tr>
        <th class='w-100px'><?php echo $lang->user->account;?></th>
        <td><?php echo html::input('account', '', "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->password;?></th>
        <td><?php echo html::password('password', '', "class='form-control'");?></td>
      </tr>  
      <tr>
        <th></th><td class="text-center"><?php echo html::submitButton() . html::hidden('sn', $sn);?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
