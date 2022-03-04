<?php
/**
 * The select lib type view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.zentao.net)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     doc
 * @version     $Id: selectlibtype.html.php 958 2021-09-3 17:09:42Z liyuchun $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo '<span>' . $lang->doc->selectLibType . '</span>';?></h2>
    </div>
  </div>
  <form method='post' class='form-ajax'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->doc->libType;?></th>
        <td class='w-p85'><?php echo html::radio('objectType', $lang->doc->libTypeList, key($lang->doc->libTypeList));?></td>
      </tr>
      <tr>
        <td colspan='3' class='text-center form-actions'><?php echo html::submitButton($lang->confirm);?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
