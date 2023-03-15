<?php
/**
 * The prjprojecttitle view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: prjprojecttitle.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class="modal-header">
  <h4 class="modal-title"><i class="icon-cog"></i><?php echo $lang->project->moduleSetting;?></h4>
</div>
<div class="modal-body" style="max-height: 564px; overflow: visible;">
  <form class="form-condensed no-stash" method="post" target="hiddenwin">
    <table class="table table-form">
      <tbody>
        <tr>
          <td class="w-180px"><?php echo $lang->project->moduleOpen;?></td>
          <td><?php echo html::radio('programTitle', $lang->project->programTitle, $status);?></td>
        </tr>
        <tr>
          <td colspan="2" class="text-center"><?php echo html::submitButton();?></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
