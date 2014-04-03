<?php
/**
 * The save view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <wwccss@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
include '../../common/view/header.html.php';
?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['mail']);?></span>
      <strong><?php echo $lang->mail->common;?></strong>
      <small class='text-success'> <?php echo $lang->mail->save;?> <?php echo html::icon('ok-sign');?></small>
    </div>
  </div>
  <div class='alert alert-success'>
    <i class='icon-ok-sign'></i>
    <div class='content'>
      <?php echo $lang->mail->successSaved;?>
      <div class='pdt-20'>
      <?php if($this->post->turnon and $mailExist) echo html::linkButton($lang->mail->test . ' <i class="icon-rocket"></i>', inlink('test'));?>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
