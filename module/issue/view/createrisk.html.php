<?php
/**
 * The createrisk view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<tr class="riskTR">
  <th><?php echo $lang->risk->name;?></th>
  <td>
    <?php echo html::input('name', $issue->title, "class='form-control'");?>
  </td>
</tr>
<tr class="riskTR">
  <th><?php echo $lang->risk->source;?></th>
  <td>
    <?php echo html::select('source', $lang->risk->sourceList, '', "class='form-control chosen'");?>
  </td>
</tr>
<tr class="riskTR">
  <th><?php echo $lang->risk->category;?></th>
  <td>
    <?php echo html::select('category', $lang->risk->categoryList, '', "class='form-control chosen'");?>
  </td>
</tr>
<tr class="riskTR">
  <th><?php echo $lang->risk->strategy;?></th>
  <td>
    <?php echo html::select('strategy', $lang->risk->strategyList, '', "class='form-control chosen'");?>
  </td>
</tr>
