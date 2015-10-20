<?php
/**
 * The manage view file of branch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     branch
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class="container mw-700px">
  <div id="titlebar">
    <div class="heading"><strong><?php echo $lang->branch->manage?></strong></div>
  </div>
  <form method='post' target='hiddenwin'>
  <table class="table table-form">
    <?php foreach($branches as $branchID => $branch):?>
    <tr>
      <td class='w-50px'></td>
      <td class="w-200px"><?php echo html::input("branch[$branchID]", $branch, "class='form-control'")?> </td>
      <td></td>
    </tr>
    <?php endforeach;?>
    <?php for($i = 0; $i < 5; $i++):?>
    <tr>
      <td class='w-50px'></td>
      <td class="w-200px"><?php echo html::input("newbranch[$i]", '', "class='form-control'")?> </td>
      <td></td>
    </tr>
    <?php endfor;?>
    <tr>
      <td class='w-50px'></td>
      <td class="w-200px"><?php echo html::submitButton()?> </td><td></td>
    </tr>
  </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>

