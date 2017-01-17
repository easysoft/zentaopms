<?php
/**
 * The save view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
  <div class='alert alert-block with-icon'>
    <div class='content'>
      <?php echo $lang->mail->successSaved;?>
      <?php if($this->post->turnon and $mailExist) echo html::a(inlink('test'), $lang->mail->test . ' <i class="icon-rocket"></i>', '', "class='btn btn-primary btn-sm'");?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
