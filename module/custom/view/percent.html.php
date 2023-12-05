<?php
/**
 * The percent view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <form class="load-indicator main-form form-ajax" method='post'>
    <table class='table table-form'>
      <tr>
        <th class='c-setPercent'><?php echo $lang->custom->setPercent;?></th>
        <td class='c-percent text-left'>
          <?php $checkedKey = isset($config->setPercent) ? $config->setPercent : 0;?>
          <?php foreach($lang->custom->conceptOptions->URAndSR as $key => $value):?>
          <label class="radio-inline"><input type="radio" name="percent" value="<?php echo $key?>"<?php echo $key == $checkedKey ? " checked='checked'" : ''?> id="percent<?php echo $key;?>"><?php echo $value;?></label>
          <?php endforeach;?>
        </td>
        <td></td>
      </tr>
      <tr>
        <th></th>
        <td colspan="2" id="readOnlyOfPercent">
          <div class="inline-block"><i class="icon-exclamation-sign"></i>&nbsp;</div>
          <div class="inline-block"><?php echo $lang->custom->notice->readOnlyOfPercent;?></div>
        </td>
      </tr>
      <tr>
        <th></th>
        <td class='form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
