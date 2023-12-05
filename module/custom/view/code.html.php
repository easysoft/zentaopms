<?php
/**
 * The code view file of custom module of ZenTaoPMS.
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
        <th class='c-setCode'><?php echo $lang->custom->setCode;?></th>
        <td class='c-code text-left'>
          <?php $checkedKey = isset($config->setCode) ? $config->setCode : 0;?>
          <?php foreach($lang->custom->conceptOptions->URAndSR as $key => $value):?>
          <label class="radio-inline"><input type="radio" name="code" value="<?php echo $key?>"<?php echo $key == $checkedKey ? " checked='checked'" : ''?> id="code<?php echo $key;?>"><?php echo $value;?></label>
          <?php endforeach;?>
        </td>
        <td></td>
      </tr>
      <tr>
        <th></th>
        <td colspan="2" id="readOnlyOfCode">
          <div class="inline-block"><i class="icon-exclamation-sign"></i>&nbsp;</div>
          <div class="inline-block"><?php echo $lang->custom->notice->readOnlyOfCode;?></div>
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
