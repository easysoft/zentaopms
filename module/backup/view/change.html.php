<?php
/**
 * The change view file of backup module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     backup
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['edit']);?></span>
    <strong><?php echo $lang->backup->change;?></strong>
  </div>
</div>

<form class='form-condensed' method='post' target='hiddenwin' style='padding: 30px 5%'>
  <table class='w-p100'>
    <tr>
      <td>
        <div class='input-group'>
          <?php echo html::input('holdDays', $config->backup->holdDays, "class='form-control' autocomplete='off'");?>
          <strong class='input-group-addon'><?php echo $lang->day;?></strong>
        </div>
      </td>
      <td><?php echo html::submitButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>

