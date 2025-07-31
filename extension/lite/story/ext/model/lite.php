<?php
public function getExecutionStoryPairs(int $executionID = 0, int $productID = 0, string|int $branch = 'all', array|string|int $moduleIdList = '', string $type = 'full', string $status = 'all', string $storyType = '', bool $hasParent = true): array
{
    if($this->config->vision == 'lite')
    {
        $execution = $this->loadModel('execution')->getById($executionID);
        if(!empty($execution->project)) $executionID = $execution->project;
    }
    return parent::getExecutionStoryPairs($executionID, $productID, $branch, $moduleIdList, $type, $status, $storyType);
}
