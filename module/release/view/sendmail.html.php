<?php
/**
 * The mail file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     release
 * @version     $Id: sendmail.html.php 867 2021-08-12 13:37:58Z $
 * @link        http://www.zentao.net
 */
?>
<?php $mailTitle = 'RELEASE #' . $release->id . ' ' . $release->name;?>
<?php $module    = $this->app->tab == 'product' ? 'release' : 'projectrelease';?>
<?php include $this->app->getModuleRoot() . 'common/view/mail.header.html.php';?>
<tr>
  <td>
    <table cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
      <tr>
        <td style='padding: 10px; background-color: #F8FAFE; border: none; font-size: 14px; font-weight: 500; border-bottom: 1px solid #e5e5e5;'>
          <?php echo html::a(zget($this->config->mail, 'domain', common::getSysURL()) . helper::createLink($module, 'view', "releaseID=$release->id", 'html'), $mailTitle, '', "text-decoration: underline;'");?>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $this->lang->release->desc;?></legend>
      <div style='padding:5px;'><?php echo $release->desc;?></div>
    </fieldset>
  </td>
</tr>
<?php include $this->app->getModuleRoot() . 'common/view/mail.footer.html.php';?>
