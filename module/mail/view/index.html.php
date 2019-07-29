<?php
/**
 * The index view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . 'message/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-700px'>
    <div class='main-header'>
      <div class='heading'>
        <strong><?php echo $lang->mail->selectMTA;?></strong>
      </div>
    </div>
    <table class='table table-form' id='selectmta'>
      <tr>
        <td class='text-center'>
          <?php if(!common::checkNotCN() and common::hasPriv('mail', 'ztCloud')):?>
          <?php echo html::a(inlink('ztCloud'), $lang->mail->ztCloud, '', "class='btn w-120px'")?>
          <?php endif;?>
          <?php if(common::hasPriv('mail', 'detect')):?>
          <?php echo html::a(inlink('detect'), $lang->mail->smtp, '', "class='btn w-120px'")?>
          <?php endif;?>
        </td>
      </tr>
    </table>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
