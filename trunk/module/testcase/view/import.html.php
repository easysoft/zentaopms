<?php
/**
 * The import view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' enctype='multipart/form-data' class='a-center'>
  <input type='file' name='file' class='text-5' /></td>
  <?php echo html::select('fileType', $lang->importEncodeList,'gbk');?>
  <?php echo html::submitButton('确定');?>
</form>
<form>
  <table>
  <?php foreach($result as $items):?>
  <tr>  
    <?php foreach($items as $item):?>  
    <td>  
    <?php echo $item = ($fileType == 'gbk' or $fileType == 'big5') ? iconv(strtoupper($fileType), "UTF-8", $item) : $item;?>
    </td>  
    <?php endforeach;?>
  </tr>
  <?php endforeach;?>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
