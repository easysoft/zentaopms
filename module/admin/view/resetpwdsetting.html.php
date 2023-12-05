<?php
/**
 * The resetpwdsetting view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     admin
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
<?php if(!common::checkNotCN()):?>
.mailBox {width: 45px !important;}
<?php else:?>
.mailBox {width: 109px !important;}
<?php endif;?>
.heading {padding-left: 15px;}
</style>
<div class="main-row">
  <div class='side-col' id='sidebar'>
    <div class='cell'>
      <div class='list-group'>
        <?php
        if(common::hasPriv('admin', 'safe'))            echo html::a($this->createLink('admin', 'safe'), $lang->admin->safe->set);
        if(common::hasPriv('admin', 'checkWeak'))       echo html::a($this->createLink('admin', 'checkWeak'), $lang->admin->safe->checkWeak);
        if(common::hasPriv('admin', 'resetPWDSetting')) echo html::a($this->createLink('admin', 'resetPWDSetting'), $lang->admin->resetPWDSetting, '', "class='active'");
        ?>
      </div>
    </div>
  </div>
  <div id='mainContent' class='main-col main-content'>
    <div class='main-header'>
      <h2><?php echo $lang->admin->resetPWDSetting;?></h2>
    </div>
    <div class='center-block'>
      <form class="load-indicator main-form form-ajax" method='post'>
        <table class='table table-form'>
          <tr>
            <th class='mailBox'><?php echo $lang->admin->resetPWDByMail;?></th>
            <td class='w-300px text-left'>
              <?php $checkedKey = isset($config->resetPWDByMail) ? $config->resetPWDByMail : 0;?>
              <?php foreach($lang->admin->safe->resetPWDList as $key => $value):?>
              <label class="radio-inline"><input type="radio" name="resetPWDByMail" value="<?php echo $key?>"<?php echo $key == $checkedKey ? " checked='checked'" : ''?> id="resetPWDByMail<?php echo $key;?>"><?php echo $value;?></label>
              <?php endforeach;?>
            </td>
          </tr>
          <tr>
            <th></th>
            <td class='form-actions'><?php echo html::submitButton();?></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
