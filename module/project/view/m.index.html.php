<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/m.header.html.php';?>
</div>
<ul data-role='listview'>
  <?php foreach($projectStats as $project):?>
  <li><?php echo html::a($this->createLink('project', 'task', "projectID=$project->id"), $project->name)?></li>
  <?php endforeach;?>
</ul>
<?php include '../../common/view/m.footer.html.php';?>
