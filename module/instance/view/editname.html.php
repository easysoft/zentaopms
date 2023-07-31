<?php
/**
 * The edit view file of instance module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QurCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   instance
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->instance->editName;?></h2>
  </div>
  <form method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'>
      <tr>
        <th class='w-100px'><?php echo $lang->instance->name;?></th>
        <td><?php echo html::input('name', $instance->name, "class='form-control' maxlength='50'");?></td>
      </tr>
    </table>
    <div class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></div>
  </form>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
