<?php
/**
 * The mail file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: sendmail.html.php 4626 2013-04-10 05:34:36Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php $mailTitle = 'BUG #' . $bug->id . ' ' . $bug->title;?>
<?php include '../../common/view/mail.header.html.php';?>
<tr>
  <td>
    <table cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
      <tr>
        <td style='padding: 10px; background-color: #F8FAFE; border: none; font-size: 14px; font-weight: 500; border-bottom: 1px solid #e5e5e5;'>BUG #<?php echo $bug->id . ' &nbsp;' . html::a(common::getSysURL() . $this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, '', "style='color: #333'");?></td>
        <td style='width: 40px; text-align: right; background-color: #F8FAFE; border: none; vertical-align: top; padding: 10px; border-bottom: 1px solid #e5e5e5;'><?php echo html::a(common::getSysURL() . $this->createLink('bug', 'view', "bugID=$bug->id"), '[+]', 'target="_blank"');?></td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $lang->bug->legendSteps;?></legend>
      <div class='content'><?php echo $bug->steps;?></div>
    </fieldset>
  </td>
</tr>
<?php include '../../common/view/mail.footer.html.php';?>
