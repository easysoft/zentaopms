<?php
/**
 * The burn view file of project module of ZenTaoMS.
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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<script language='javascript'>
function selectProject(projectID)
{
    link = createLink('project', 'browse', 'projectID=' + projectID);
    location.href=link;
}
</script>
<div class="yui-d0 yui-t3">                 
  <div class="yui-b"><?php include './project.html.php';?></div>
  <div class="yui-main">
    <div class="yui-b">
      <div id='tabbar' class='yui-d0'><?php include './tabbar.html.php'; ?></div>
      <div class='a-center'>
        <?php echo $charts; ?>
      </div>
    </div>
  </div>
</div>  
<?php include '../../common/footer.html.php';?>
