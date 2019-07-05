<?php
/**
 * The addLang view file of translate module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     translate
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <div class='heading'><h4><?php echo $lang->translate->addLang;?></h4></div>
    </div>
    <?php if($cmd):?>
    <div class='article-content text-danger'><?php printf($lang->translate->notice->failDirPriv, $cmd);?></div>
    <hr>
    <?php echo html::commonButton($lang->translate->refreshPage, 'onclick=location.href=location.href', 'btn btn-primary');?>
    <?php else:?>
    <form class='main-form form-ajax' method='post' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-150px'><?php echo $lang->translate->name;?></th>
          <td class='w-p30 required'><?php echo html::input('name', '', "class='form-control'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->translate->code;?></th>
          <td class='required'><?php echo html::input('code', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->translate->reference;?></th>
          <td><?php echo html::select('reference', $referenceList, 'zh-cn', "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center'><?php echo html::submitButton() . ' ' . html::backButton();?></td>
        </tr>
      </table>
    </form>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
