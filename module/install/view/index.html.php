<?php
/**
 * The html template file of index method of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<table align='center' class='table-6'>
  <caption><?php echo $lang->install->welcome;?></caption>
  <tr><td><?php echo nl2br(sprintf($lang->install->desc, $config->version));?></td></tr>
  <tr>
    <td>
    <?php if(!isset($latestRelease)):?>
    <h3 class='a-center'><?php echo html::a($this->createLink('install', 'step1'), $lang->install->start);?></h3>
    <?php else:?>
    <?php vprintf($lang->install->newReleased, $latestRelease);?>
    <h3 class='a-center'>
      <?php 
      echo $lang->install->choice;
      echo html::a($latestRelease->url, $lang->install->seeLatestRelease, '_blank');
      echo html::a($this->createLink('install', 'step1'), $lang->install->keepInstalling);
      ?>
    </h3>
    <?php endif;?>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.lite.html.php';?>
