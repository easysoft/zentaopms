<?php
/**
 * The create view of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->release->create;?></h2>
    </div>
    <form class='load-indicator main-form form-ajax' id='dataform' method='post' enctype='multipart/form-data'>
      <table class='table table-form'> 
        <tbody>
          <tr>
            <th><?php echo $lang->release->name;?></th>
            <td><?php echo html::input('name', '', "class='form-control' autocomplete='off' required");?></td>
            <td class='muted'>
              <div class='checkbox-primary'>
                <input id='marker' name='marker' value='1' type='checkbox' />
                <label for='marker'><?php echo $lang->release->marker;?></label>
              </div>
              <?php if($lastRelease) echo '(' . $lang->release->last . ': ' . $lastRelease->name . ')';?>
            </td>
          </tr> 
          <tr>
            <th><?php echo $lang->release->build;?></th>
            <td><?php echo html::select('build', $builds, '', "class='form-control chosen'");?></td><td></td>
          </tr>  
          <tr>
            <th><?php echo $lang->release->date;?></th>
            <td><?php echo html::input('date', helper::today(), "class='form-control form-date' required");?></td><td></td>
          </tr>  
          <tr>
            <th><?php echo $lang->release->desc;?></th>
            <td colspan='2'><?php echo html::textarea('desc', '', "rows='10' class='form-control kindeditor' hidefocus='true'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->files;?></th>
            <td colspan='2'><?php echo $this->fetch('file', 'buildform');?></td>
          </tr>  
          <tr>
            <td colspan='3' class='text-center form-actions'>
              <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
              <?php echo html::backButton('', '', 'btn btn-wide');?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>  
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
