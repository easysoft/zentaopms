<?php
/**
 * The set view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='w-150px text-top'><?php echo $lang->custom->working;?></th>
          <td><?php echo html::radio('flow', $lang->custom->workingList, isset($config->global->flow) ? $config->global->flow : 'full', '', 'block');?></td>
        </tr>
        <tr><td></td><td><?php echo html::submitButton('', '', 'btn btn-primary btn-wide')?></td></tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
