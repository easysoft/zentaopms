<?php
/**
 * The html template file of check config method of convert module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='yui-d0'>
  <form method='post' action='<?php echo inlink('execute');?>'>
  <table align='center' class='table-5 f-14px'>
    <caption><?php echo $lang->convert->checkConfig . $lang->colon . strtoupper($source);?></caption>
    <?php echo $checkResult;?>
  </table>
  <?php 
  echo html::hidden('dbHost',     $this->post->dbHost);
  echo html::hidden('dbPort',     $this->post->dbPort);
  echo html::hidden('dbUser',     $this->post->dbUser);
  echo html::hidden('dbPassword', $this->post->dbPassword);
  echo html::hidden('dbName',     $this->post->dbName);
  echo html::hidden('dbCharset',  $this->post->dbCharset);
  echo html::hidden('dbPrefix',   $this->post->dbPrefix);
  echo html::hidden('installPath',$this->post->installPath);
  ?>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
