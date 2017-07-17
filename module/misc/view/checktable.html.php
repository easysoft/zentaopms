<?php
/**
 * The reset view file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='alert alert-info'><strong><?php echo $lang->misc->repairTable;?></strong></div>
<div class='container mw-700px'>
<?php if($status == 'createFile'):?>
  <div class='panel-body'>
    <?php printf($lang->misc->noticeRepair, $this->session->checkFileName);?>
  </div>
  <p><?php echo html::a(inlink('checkTable'), $this->lang->refresh, '', "class='btn'")?></p>
<?php elseif($status == 'check'):?>
  <div class='panel'>
    <table class='table table-form'>
      <thead>
        <tr>
          <th><?php echo $lang->misc->tableName?></th>
          <th><?php echo $lang->misc->tableStatus?></th>
        </tr>
      </thead>
      <tbody>
      <?php $needRepair = false;?>
      <?php foreach($tables as $tableName => $tableStatus):?>
      <?php if($tableStatus != 'ok') $needRepair = true;?>
        <tr>
          <td><?php echo $tableName;?></td>
          <td><span style='color:<?php echo $tableStatus == 'ok' ? 'green' : 'red'?>'><?php echo $tableStatus;?></span></td>
        </tr>
      </tbody>
      <?php endforeach;?>
      <?php if($needRepair):?>
      <tfoot>
        <tr><td class='text-center' colspan='2'><?php echo html::a(inlink('checkTable', "type=repair"), $lang->misc->needRepair, '', "class='btn btn-primary'")?></td></tr>
      </tfoot>
      <?php endif;?>
    </table>
  </div>
<?php endif;?>
</div>
<script laguage='Javascript'>
<?php if(isset($pageJS)) echo $pageJS;?>
</script>
</body>
</html>
