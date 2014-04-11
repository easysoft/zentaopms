<?php
/**
 * The view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     admin
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon('bolt');?></span>
      <strong><?php echo $lang->admin->clearData;?></strong>
    </div>
  </div>
  <div class='alert mg-0'>
    <i class='icon-exclamation-sign text-danger'></i>
    <div class='content'>
      <?php echo nl2br($lang->admin->clearDataDesc);?>
      <hr>
      <div class='text-center'>
        <form method='post' target='hiddenwin' action='<?php echo inLink('clearData', 'confirm=no')?>'>
          <div class='form-group' style='margin: 0 auto; max-width: 500px'>
            <div class='input-group w-p100' style='margin-bottom:10px'>
              <span class='input-group-addon'><?php echo $this->lang->admin->pleaseInputYes;?></span>
              <?php echo html::input('sure', '', "class='form-control' onkeyup='showClearButton()' autocomplete='off'");?>
            </div>
          </div>
            <?php echo html::submitButton($lang->admin->clearData, "class='hidden btn-block btn btn-danger'");?>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../../common/view/footer.html.php';?>
