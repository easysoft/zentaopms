<?php
/**
 * The close file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: cancel.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span title='<?php echo $lang->zanode->automation;?>'><?php echo $lang->zanode->automation;?></span>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->zanode->common;?></th>
          <td class='required'><?php echo html::select('node', $nodeList, !empty($automation->node) ? $automation->node : '', "class='form-control picker-select'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->zanode->scriptPath;?></th>
          <td class='required'><?php echo html::input('scriptPath', !empty($automation->scriptPath) ? $automation->scriptPath : '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->zanode->shell;?></th>
          <td><?php echo html::textarea('shell', !empty($automation->shell) ? $automation->shell : '', "rows='6' class='form-control'");?></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center'>
            <?php echo html::hidden('product', $productID);?>
            <?php if($automation) echo html::hidden('id', $automation->id);?>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
