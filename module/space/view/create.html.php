<?php
/**
 * The create file of spce module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     space
 * @version     $Id: create.html.php 935 2021-12-08 10:49:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span><?php echo $lang->space->create;?></span>
    </h2>
  </div>
  <form class='load-indicator main-form' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tbody>
        <tr>
          <th><?php echo $lang->space->name;?></th>
          <td><?php echo html::input('name','',"class='form-control input-train-title' requird")?></td>
        </tr>
        <tr>
          <th><?php echo $lang->space->owner;?></th>
        </tr>
      </tbody>
    </table>
  </form>
</div>
