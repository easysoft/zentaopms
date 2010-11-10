<?php
/**
 * The html template file of tips method of project module of ZenTaoMS.
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
 * @package     ZenTaoMS
 * @version     $Id: tips.html.php 867 2010-06-17 09:32:58Z jajacn$
 */
include '../../common/view/header.lite.html.php';
?>
<style >body{background:white}</style>
<div class='yui-d0' style='margin-top:100px'>
  <table align='center' class='table-3'> 
    <caption><?php echo $lang->project->tips;?></caption>
    <tr>
      <td>
        <?php
        echo $lang->project->afterInfo;
        echo "<br />";
        echo html::a($this->createLink('project', 'team', "projectID=$projectID"), $lang->project->setTeam);
        echo html::a($this->createLink('project', 'linkstory', "projectID=$projectID"), $lang->project->linkStory);
        echo html::a($this->createLink('task', 'create', "project=$projectID"), $lang->project->createTask);
        echo html::a($this->createLink('project', 'task', "projectID=$projectID"), $lang->project->goback);
        ?>
      </td>
    </tr>  
  </table>
</div>  
</body>
</html>
