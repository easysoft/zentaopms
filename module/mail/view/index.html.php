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
include '../../common/view/header.html.php';
?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['mail']);?></span>
      <strong><?php echo $lang->mail->selectMTA;?></strong>
    </div>
  </div>
  <table class='table table-form' id='selectmta'>
    <tr>
      <td class='text-center'>
        <?php if($this->app->getClientLang() != 'en'  and common::hasPriv('mail', 'ztCloud')):?>
        <?php echo html::a(inlink('ztCloud'), $lang->mail->ztCloud, '', "class='btn btn-sm w-120px'")?>
        <?php endif;?>
        <?php if(common::hasPriv('mail', 'detect')):?>
        <?php echo html::a(inlink('detect'), $lang->mail->smtp, '', "class='btn btn-sm w-120px'")?>
        <?php endif;?>
      </td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
