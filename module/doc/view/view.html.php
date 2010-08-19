<?php
/**
 * The view of doc module of ZenTaoMS.
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
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: view.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentaoms.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../file/view/download.html.php';?>
</style>
<div class='yui-d0'>
  <table class='table-1'>
  <caption><?php echo $doc->title . $lang->colon . $lang->doc->view;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->doc->title;?></th>
      <td <?php if($doc->deleted) echo "class='deleted'";?>><?php echo $doc->title;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->doc->lib;?></th>
      <td><?php echo $doc->libName;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->doc->product;?></th>
      <td><?php echo $doc->productName;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->doc->project;?></th>
      <td><?php echo $doc->projectName;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->doc->module;?></th>
      <td><?php echo $doc->moduleName;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->files;?></th>
      <td>
      <?php 
      foreach($doc->files as $file)
      {
          echo html::a($this->createLink('file', 'download', "fileID=$file->id"), $file->title, '_blank', "onclick='return downloadFile($file->id)'");
          echo html::commonButton('x', "onclick=deleteFile($file->id)");
      }
      ?>
      </td>
    </tr>
  </table>
  <div class='a-center f-16px strong'>
    <?php
    $browseLink = $this->session->docList ? $this->session->docList : inlink('browse');
    if(!$doc->deleted)
    {
        common::printLink('doc', 'edit',   "docID=$doc->id", $lang->edit);
        common::printLink('doc', 'delete', "docID=$doc->id", $lang->delete, 'hiddenwin');
    }
    echo html::a($browseLink, $lang->goback);
    ?>
  </div>
<?php include '../../common/view/action.html.php';?>
<?php include './footer.html.php';?>
