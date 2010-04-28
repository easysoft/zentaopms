<?php
/**
 * The html template file of index method of install module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     ZenTaoMS
 * @version     $Id$
 */
?>
<?php include './header.html.php';?>
<div class='yui-d0'>
  <table align='center' class='table-6'>
    <caption><?php echo $lang->install->welcome;?></caption>
    <tr><td><?php echo nl2br(sprintf($lang->install->desc, $config->version));?></td></tr>
    <tr><td>
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
    </td></tr>
  </table>
</div>
<?php include './footer.html.php';?>
