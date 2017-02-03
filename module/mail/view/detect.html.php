<?php
/**
 * The detect view file of mail module of ZenTaoPMS.
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
      <small class='text-muted'> <?php echo $lang->mail->detect;?> <?php echo html::icon('cog');?></small>
    </div>
  </div>
  <form class='form-condensed pdt-20' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tr><th class='w-p25'><?php echo $lang->mail->inputFromEmail; ?></th><td class='w-p50'><?php echo html::input('fromAddress', $fromAddress, "class='form-control' autocomplete='off'");?></td><td><?php echo html::submitButton($lang->mail->nextStep);?></td></tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
