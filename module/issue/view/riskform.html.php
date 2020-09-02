<?php
/**
 * The create risk view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<tr>
  <th><?php echo $lang->issue->resolution;?></th>
  <td>
    <?php echo html::select('resolution', $lang->issue->resolveMethods, $resolution, 'class="form-control chosen" onchange="getSolutions()"');?>
  </td>
</tr>
<tr>
  <th><?php echo $lang->risk->name;?></th>
  <td class="required">
    <?php echo html::input('name', $issue->title, "class='form-control'");?>
  </td>
</tr>
<tr>
  <th><?php echo $lang->risk->source;?></th>
  <td>
    <?php echo html::select('source', $lang->risk->sourceList, '', "class='form-control chosen'");?>
  </td>
</tr>
<tr>
  <th><?php echo $lang->risk->category;?></th>
  <td>
    <?php echo html::select('category', $lang->risk->categoryList, '', "class='form-control chosen'");?>
  </td>
</tr>
<tr>
  <th><?php echo $lang->risk->strategy;?></th>
  <td>
    <?php echo html::select('strategy', $lang->risk->strategyList, '', "class='form-control chosen'");?>
  </td>
</tr>
<tr>
  <th><?php echo $lang->issue->resolvedBy;?></th>
  <td>
    <?php echo html::select('resolvedBy', $users, $this->app->user->account, "class='form-control chosen'");?>
  </td>
</tr>
<tr>
  <th><?php echo $lang->issue->resolvedDate;?></th>
  <td>
     <div class='input-group has-icon-right'>
       <?php echo html::input('resolvedDate', date('Y-m-d'), "class='form-control form-date'");?>
       <label for="date" class="input-control-icon-right"><i class="icon icon-delay"></i></label>
     </div>
  </td>
</tr>
<tr>
  <td></td>
  <td>
    <div class='form-action'><?php echo html::submitButton();?></div>
  </td>
</tr>
