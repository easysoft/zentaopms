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
?>
<?php include $this->app->getModuleRoot() . 'message/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-700px'>
    <div class='main-header'>
      <h2>
        <?php echo $lang->mail->common;?>
        <small class='text-success'> <?php echo $lang->saveSuccess;?> <?php echo html::icon('check-circle');?></small>
      </h2>
    </div>
    <div class='alert alert-block with-icon'>
      <div class='content'>
        <?php echo $lang->mail->successSaved;?>
        <?php if($this->post->turnon):?>
        <?php if($mailExist):?>
        <?php echo html::a(inlink('test'), $lang->mail->test . ' <i class="icon-rocket"></i>', '', "class='btn btn-primary btn-sm'");?>
        <?php else:?>
          <span class='content alert-warning'>
            <i class="icon-exclamation-sign"></i><?php echo $lang->mail->setForUser;?>
          </span>
        <?php endif;?>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
