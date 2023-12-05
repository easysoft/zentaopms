<?php
/**
 * The html template file of select version method of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: selectversion.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<div class='container'>
  <form method='post' action='<?php echo inlink('confirm');?>'>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <strong><?php echo $lang->upgrade->selectVersion;?></strong>
      </div>
      <div class='modal-body'>
        <table class='table table-form'>
          <tr>
            <th class='w-100px'><?php echo $lang->upgrade->fromVersion;?></th>
            <td><?php echo html::select('fromVersion', $lang->upgrade->fromVersions, $version, "class='form-control chosen'");?></td>
            <td class='text-danger'><?php echo $lang->upgrade->noteVersion;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->upgrade->toVersion;?></th>
            <td><?php echo ucfirst($config->version);?></td>
          </tr>
        </table>
      </div>
      <div class='modal-footer'>
        <?php echo html::submitButton($lang->upgrade->common);?>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
