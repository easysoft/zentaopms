<?php
/**
 * The view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: view.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php echo css::internal($keTableCSS);?>
<?php $browseLink = $this->session->docList ? $this->session->docList : inlink('browse');?>
<style>body{padding:0px;}</style>
<div class='xuanxuan-card'>
  <div class='panel'>
    <div class='panel-heading strong'>
      <span class="label label-id"><?php echo $doc->id;?></span> <span class="text" title='<?php echo $doc->title;?>'><?php echo $doc->title;?></span>
    </div>
    <div class="panel-body">
      <table class="table table-data">
        <tbody>
          <tr>
            <th class='w-80px'><?php echo $lang->doc->content;?></th>
            <td>
              <div class="detail-content article-content no-margin no-padding">
                <?php echo $doc->content;?>

                <?php foreach($doc->files as $file):?>
                <?php if(in_array($file->extension, $config->file->imageExtensions)):?>
                <div class='file-image'>
                  <img onload="setImageSize(this, 0)" src="<?php echo $this->createLink('file', 'read', "fileID={$file->id}");?>" alt="<?php echo $file->title?>">
                </div>
                <?php unset($doc->files[$file->id]);?>
                <?php endif;?>
                <?php endforeach;?>
              </div>
              <?php echo $this->fetch('file', 'printFiles', array('files' => $doc->files, 'fieldset' => 'true'));?>
            </td>
          </tr>
          <?php if($doc->productName):?>
          <tr>
            <th><?php echo $lang->doc->product;?></th>
            <td><?php echo $doc->productName;?></td>
          </tr>
          <?php endif;?>
          <?php if($doc->projectName):?>
          <tr>
            <th><?php echo $lang->doc->project;?></th>
            <td><?php echo $doc->projectName;?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->doc->lib;?></th>
            <td><?php echo $lib->name;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->module;?></th>
            <td><?php echo $doc->moduleName ? $doc->moduleName : '/';?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->addedDate;?></th>
            <td><?php echo $doc->addedDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->editedBy;?></th>
            <td><?php echo zget($users, $doc->editedBy);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->editedDate;?></th>
            <td><?php echo $doc->editedDate;?></td>
          </tr>
        </tbody>
      </table>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
</div>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/footer.lite.html.php';?>
