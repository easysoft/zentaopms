<?php
/**
 * The checkExtension view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <form method='post'>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <strong><?php echo $lang->upgrade->consistency;?></strong>
      </div>
      <div class='modal-body'>
        <h4><?php echo $lang->upgrade->noticeSQL;?></h4>
        <p class='text-danger code'>
          SET @@sql_mode= '';<br />
          <?php echo nl2br($alterSQL);?>
        </p>
      </div>
      <div class='modal-footer'><?php echo html::a('#', $this->lang->refresh, '', "class='btn btn-wide' onclick='location.reload()'");?></div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
