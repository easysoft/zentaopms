<?php
class projectBuild extends control
{
    public function browse($projectID = 0, $type = 'all', $param = 0)
    {
        $this->loadModel('project');
        $project = $this->loadModel('program')->getPRJByID($projectID);

        /* Get products' list. */
        $products = $this->project->getProducts($projectID, false);
        $products = array('' => '') + $products;

        /* Build the search form. */
        $type      = strtolower($type);
        $queryID   = ($type == 'bysearch') ? (int)$param : 0; 
        $actionURL = $this->createLink('projectbuild', 'browse', "projectID=$projectID&type=bysearch&queryID=myQueryID");
        $this->project->buildProjectBuildSearchForm($products, $queryID, $actionURL);

        if($type == 'bysearch')
        {
            $builds = $this->loadModel('build')->getProjectBuildsBySearch((int)$projectID, $param);
        }
        else
        {
            $builds = $this->loadModel('build')->getProjectBuilds((int)$projectID, $type, $param);
        }

        /* Set project builds. */
        $projectBuilds = array();
        if(!empty($builds))
        {    
            foreach($builds as $build) $projectBuilds[$build->product][] = $build;
        } 

        /* Header and position. */
        $this->view->title      = $project->name . $this->lang->colon . $this->lang->project->build;
        $this->view->position[] = html::a(inlink('browse', "projectID=$projectID"), $project->name);
        $this->view->position[] = $this->lang->project->build;

        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->buildsTotal   = count($builds);
        $this->view->projectBuilds = $projectBuilds;
        $this->view->projectID     = $projectID;
        $this->view->product       = $type == 'product' ? $param : 'all';
        $this->view->project       = $project;
        $this->view->products      = $products;
        $this->view->type          = $type;

        $this->display();
    }
}
