<?php
/**
 * The save query view file of search module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     search
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<form target='hiddenwin' method='post' class='no-stash' style='padding: 15px 70px 15px 15px'>
  <div class='input-group'>
    <input name='title' id='title' class="form-control" autocomplete="off" type="text">
    <span class='input-group-addon'>
      <div class="checkbox-primary">
        <input type="checkbox" name="common" value="1" id="common" />
        <label for="common"><?php echo $lang->search->setCommon;?></label>
      </div>
    </span>
    <?php if($onMenuBar == 'yes'):?>
    <span class='input-group-addon'>
      <div class="checkbox-primary">
        <input type="checkbox" name="onMenuBar" value="1" id="onMenuBar" />
        <label for="onMenuBar"><?php echo $lang->search->onMenuBar?></label>
      </div>
    </span>
    <?php endif;?>
    <span class='input-group-btn'><?php echo html::submitButton('', '', 'btn btn-primary') . html::hidden('module', $module)?></span>
  </div>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
