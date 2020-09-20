<?php
/**
 * The prjprogramtitle view file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @version     $Id: prjprogramtitle.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class="modal-header">
  <h4 class="modal-title"><i class="icon-cog"></i><?php echo $lang->program->PRJModuleSetting;?></h4>
</div>
<div class="modal-body" style="max-height: 564px; overflow: visible;">
  <form class="form-condensed" method="post" target="hiddenwin">
    <table class="table table-form">
      <tbody>
        <tr>
          <td class="w-150px"><?php echo $lang->program->PRJModuleOpen;?></td>
          <td><?php echo html::radio('PRJProgramTitle', $lang->program->PRJProgramTitle, $status);?></td>
        </tr>
        <tr>
          <td colspan="2" class="text-center"><?php echo html::submitButton();?></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
