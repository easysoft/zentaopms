<?php
/**
 * The edit of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     risk
 * @version     $Id: edit.html.php 4903 2020-09-04 09:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->risk->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
         <tr>
           <th><?php echo $lang->risk->name;?></th>
           <td><?php echo html::input('name', $risk->name, "class='form-control'");?></td>
           <td></td>
           <td></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->source;?></th>
           <td><?php echo html::select('source', $lang->risk->sourceList, $risk->source, "class='form-control chosen'");?></td>
         </tr>          
         <tr>
           <th><?php echo $lang->risk->category;?></th>
           <td><?php echo html::select('category', $lang->risk->categoryList, $risk->category, "class='form-control chosen'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->strategy;?></th>
           <td><?php echo html::select('strategy', $lang->risk->strategyList, $risk->strategy, "class='form-control chosen'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->status;?></th>
           <td><?php echo html::select('status', $lang->risk->statusList, $risk->status, "class='form-control chosen'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->impact;?></th>
           <td><?php echo html::select('impact', $lang->risk->impactList, $risk->impact, "class='form-control chosen'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->probability;?></th>
           <td><?php echo html::select('probability', $lang->risk->probabilityList, $risk->probability, "class='form-control chosen'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->rate;?></th>
           <td><?php echo html::input('rate', $risk->rate, "class='form-control'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->pri;?></th>
           <td id='priValue'><?php echo html::select('pri', $lang->risk->priList, $risk->pri, "class='form-control chosen'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->identifiedDate;?></th>
           <td><?php echo html::input('identifiedDate', $risk->identifiedDate == '0000-00-00' ? '' : $risk->identifiedDate, "class='form-control form-date'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->plannedClosedDate;?></th>
           <td><?php echo html::input('plannedClosedDate', $risk->plannedClosedDate == '0000-00-00' ? '' : $risk->plannedClosedDate, "class='form-control form-date'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->actualClosedDate;?></th>
           <td><?php echo html::input('actualClosedDate', $risk->actualClosedDate == '0000-00-00' ? '' : $risk->actualClosedDate, "class='form-control form-date'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->resolvedBy;?></th>
           <td><?php echo html::select('resolvedBy', $users, empty($risk->resolvedBy) ? $this->app->user->account : $risk->resolvedBy, "class='form-control chosen'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->assignedTo;?></th>
           <td><?php echo html::select('assignedTo', $users, empty($risk->assignedTo) ? $this->app->user->account : $risk->assignedTo, "class='form-control chosen'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->prevention;?></th>
           <td colspan='2'><?php echo html::textarea('prevention', $risk->prevention, "class='form-control'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->remedy;?></th>
           <td colspan='2'><?php echo html::textarea('remedy', $risk->remedy, "class='form-control'");?></td>
         </tr>
         <tr>
           <th><?php echo $lang->risk->resolution;?></th>
           <td colspan='2'><?php echo html::textarea('resolution', $risk->resolution, "class='form-control'");?></td>
         </tr>
         <tr>
           <td colspan='3' class='form-actions text-center'>
             <?php echo html::submitButton() . html::backButton();?>
           </td>
         </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
