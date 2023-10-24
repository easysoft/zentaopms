<?php
/**
 * The displaysetting view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     doc
 * @version     $Id: displaysetting.html.php 4769 2023-04-05 14:24:21Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class="modal-header">
  <h4 class="modal-title"><i class="icon-cog"></i> <?php echo $lang->doc->displaySetting;?></h4>
</div>
<div class="modal-body" style="max-height: 564px; overflow: visible;">
  <form class="form-condensed no-stash" method="post" target="hiddenwin">
    <table class="table table-form">
      <tbody>
        <tr>
          <td class='titleBox'><?php echo $lang->doc->showDoc;?></td>
          <td><?php echo html::radio('showDoc', $lang->doc->showDocList, $showDoc);?></td>
        </tr>
        <tr>
          <td colspan="2" class="text-center"><?php echo html::submitButton();?></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
