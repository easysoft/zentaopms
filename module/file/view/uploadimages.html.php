<?php
/**
 * The uploadImages view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<form class='form-condensed' enctype='multipart/form-data' method='post' target='hiddenwin' style='padding: 20px 5% 50px'>
  <table class='table table-form'>
    <tr>
      <td class='w-p70'><input type='file' name='file' class='form-control'/></td>
      <td><?php echo html::submitButton();?></td>
    </tr>
    <tr><td colspan='2'><div class='alert'><?php echo $lang->file->uploadImagesExplain?></div></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
