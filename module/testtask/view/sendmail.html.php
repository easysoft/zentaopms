<?php
/**
 * The mail file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: sendmail.html.php 3717 2012-12-10 00:37:07Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<table width='98%' align='center'>
  <tr class='header'>
    <td>
      TESTTASK #<?php echo $testtask->id . "=>$testtask->owner " . html::a(common::getSysURL() . $this->createLink('testtask', 'view', "testtaskID=$testtask->id"), $testtask->name);?>
    </td>
  </tr>
  <tr>
    <td>
    <fieldset>
      <legend><?php echo $lang->testtask->desc;?></legend>
      <div class='content'>
      <?php 
      if(strpos($testtask->desc, 'src="data/upload'))
      {
        $testtask->desc = str_replace('<img src="', '<img src="http://' . $this->server->http_host . $this->config->webRoot, $testtask->desc);
        $testtask->desc = str_replace('<img alt="" src="', '<img src="http://' . $this->server->http_host . $this->config->webRoot, $testtask->desc);
      }
      echo $testtask->desc;
      ?>
      </div>
    </fieldset>
    </td>
  </tr>
  <tr>
    <td><?php include '../../common/view/mail.html.php';?></td>
  </tr>
</table>
