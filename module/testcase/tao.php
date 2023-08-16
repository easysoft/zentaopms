<?php
declare(strict_types=1);
class testcaseTao extends testcaseModel
{
    /**
     * Fetch scene name.
     *
     * @param  int       $sceneID
     * @access protected
     * @return void
     */
    protected function fetchSceneName(int $sceneID): string|null
    {
        return $this->dao->findByID((int)$sceneID - CHANGEVALUE)->from(TABLE_SCENE)->fetch('title');
    }
}
