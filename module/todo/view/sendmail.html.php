<?php
/**
 * The mail view file of todo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     todo
 * @link        https://www.zentao.net
 */
?>
<?php $mailTitle = 'TODO #' . $object->id . ' ' . $object->name;?>
<?php include $this->app->getModuleRoot() . 'common/view/mail.header.html.php';?>
<tr>
  <td>
    <table cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
      <tr>
        <td style='padding: 10px; background-color: #F8FAFE; border: none; font-size: 14px; font-weight: 500; border-bottom: 1px solid #e5e5e5;'>
          <?php echo html::a($domain . helper::createLink('todo', 'view', "id=$object->id", 'html'), $mailTitle, '', "style='color: #333; text-decoration: underline;'");?>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
    <div style='padding-bottom: 5px; font-size: 14px; font-weight: 500; color: #114f8e;'><?php echo $this->lang->todo->desc;?></div>
    <div style='padding: 10px; border: 1px solid #e5e5e5;'><?php echo $object->desc;?></div>
  </td>
</tr>
<?php include $this->app->getModuleRoot() . 'common/view/mail.footer.html.php';?>
