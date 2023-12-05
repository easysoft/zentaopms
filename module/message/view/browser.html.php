<?php
/**
 * The browser view file of message module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='mw-800px'>
    <div class='main-header'>
      <h2>
        <?php echo $lang->message->browser;?>
        <small class='text-muted'> <?php echo $lang->arrow . $lang->message->setting;?></small>
      </h2>
    </div>
    <form class="main-form form-ajax" method='post' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='rowhead w-130px'><?php echo $lang->message->browserSetting->turnon; ?></th>
          <td class='w-p25-f'><?php echo html::radio('turnon', $lang->message->browserSetting->turnonList, $browserConfig->turnon);?></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->message->browserSetting->pollTime; ?></th>
          <td><?php echo html::input('pollTime', $browserConfig->pollTime, "class='form-control'");?></td>
          <td colspan='2'><?php echo $lang->message->browserSetting->pollTimePlaceholder?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
