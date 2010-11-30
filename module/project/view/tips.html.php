<div class='yui-d0' style='margin-top:30px'>
  <table align='center' class='table-3'> 
    <caption><?php echo $lang->project->tips;?></caption>
    <tr>
      <td><?php echo $lang->project->afterInfo;?>
      <div class='f-14px pt-10px'><?php echo $lang->arrow. html::a($this->createLink('project', 'team', "projectID=$projectID"), $lang->project->setTeam);?></div>
      <div class='f-14px pt-10px'><?php echo $lang->arrow. html::a($this->createLink('project', 'linkstory', "projectID=$projectID"), $lang->project->linkStory);?></div>
      <div class='f-14px pt-10px'><?php echo $lang->arrow. html::a($this->createLink('task', 'create', "project=$projectID"), $lang->project->createTask);?></div>
      <div class='f-14px pt-10px'><?php echo $lang->arrow. html::a($this->createLink('project', 'task', "projectID=$projectID"), $lang->project->goback);?></div>
      </td>
    </tr>  
  </table>
</div>
