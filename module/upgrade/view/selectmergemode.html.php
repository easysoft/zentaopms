<?php
/**
 * The mergeProgram view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='panel'>
      <div class='panel-heading text-center'>
        <h2><?php echo $lang->upgrade->selectMergeMode;?></h2>
      </div>
      <form method='post'>
        <div class='panel-body'>
          <table>
            <tbody>
              <tr>
                <th class='w-100px' valign='top'><?php echo $lang->upgrade->mergeMode;?></th>
                <td>
                  <?php foreach($lang->upgrade->mergeModes as $mode => $label):?>
                  <div class='radio'>
                    <label>
                      <input type='radio' name='projectType' value='<?php echo $mode;?>' <?php if($mode == 'project') echo "checked='checked'";?>>
                      <?php echo $label;?>
                      <?php $tipLang = 'merge' . ucfirst($mode) . 'Tip';?>
                      <div class='tips text-gray'><?php echo $lang->upgrade->{$tipLang};?></div>
                      <?php if($systemMode == 'ALM' && $mode != 'manually'):?>
                      <div class='tips text-gray'><?php echo $lang->upgrade->createProgramTip;?></div>
                      <?php endif;?>
                    </label>
                  </div>
                  <?php endforeach;?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class='panel-footer text-center'>
          <?php echo html::a($this->createLink('upgrade', 'to18guide', "fromVersion=$fromVersion"), $lang->upgrade->back, '', "class='btn btn-wide btn-secondary'");?>
          <?php echo html::submitButton($lang->upgrade->next);?>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
