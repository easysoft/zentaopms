<?php
/**
 * The mail file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<table width='98%' align='center'>
  <tr class='header'>
    <td>
      STORY #<?php echo $story->id . "=>$story->assignedTo " . html::a(common::getSysURL() . $this->createLink('story', 'view', "storyID=$story->id"), $story->title);?>
    </td>
  </tr>
  <tr>
    <td>
    <fieldset>
      <legend><?php echo $lang->story->legendSpec;?></legend>
      <div class='content'>
      <?php 
      if(strpos($story->spec, '<img src="data/upload'))
      {
        $story->spec = str_replace('<img src="', '<img src="http://' . $this->server->http_host . $this->config->webRoot, $story->spec);
      }
      echo $story->spec;
      ?>
      </div>
    </fieldset>
    </td>
  </tr>
  <tr>
    <td><?php include '../../common/view/mail.html.php';?></td>
  </tr>
</table>
