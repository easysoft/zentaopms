<?php
/**
 * The fixFirst view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     execution
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
<div class='main-header'>
  <h2><?php echo $lang->execution->fixFirst;?></h2>
</div>
  <form target='hiddenwin' method='post' class='no-stash'>
    <table class='table table-form'>
      <tr>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $execution->begin?></span>
            <?php echo html::input('estimate', !empty($firstBurn->estimate) ? $firstBurn->estimate : (!empty($firstBurn->left) ? $firstBurn->left : ''), "class='form-control' placeholder='{$lang->execution->placeholder->totalLeft}'")?>
            <span class='input-group-addon fix-border'>
              <div class='checkbox-primary'>
                <input id='withLeft' type='checkbox' checked name='withLeft' value='1' />
                <label for='withLeft'><?php echo $lang->execution->fixFirstWithLeft?></label>
              </div>
            </span>
            <span class='input-group-btn'><?php echo html::submitButton($lang->save, '', "btn btn-primary");?></span>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class='alert alert-primary no-margin'><?php echo $lang->execution->totalEstimate;?> : <code class='strong text-primary'><?php echo $execution->totalEstimate;?></code> <?php echo $lang->execution->workHour;?></div>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
