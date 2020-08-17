<?php
class design extends control
{
    public function revision($repoID)
    {
        $repo    = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('id')->eq($repoID)->fetch();
        $repoURL = $this->createLink('repo', 'revision', "repoID=$repo->repo&revistion=$repo->revision");
        header("location:" . $repoURL);
    }
}
