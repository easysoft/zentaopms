<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'aiagententry' . DS . 'v1.php';

class detailHeader extends wg
{
    protected static array $defineProps = array(
        'back?: string="APP"',
        'backUrl?: string',
        'showAIEntry?: bool',
        'module?: string',
        'method?: string',
        'objectID?: int',
        'objectVarName?: string',
        'showAgent?: bool=true',
        'showTeammate?: bool=true'
    );

    protected static array $defineBlocks = array(
        'prefix' => array(),
        'title'  => array(),
        'suffix' => array()
    );

    private function backBtn(): node
    {
        global $lang;
        return backBtn
        (
            set::icon('back'),
            set::type('primary-outline'),
            set::back($this->prop('back')),
            set::url($this->prop('backUrl')),
            $lang->goback
        );
    }

    private function resolveAIObject(string $module): array
    {
        $objectID      = $this->prop('objectID');
        $objectVarName = $this->prop('objectVarName');

        if($objectID)
        {
            return array((int)$objectID, $objectVarName ?: $module);
        }

        $candidateVars = array_values(array_unique(array_filter(array(
            $objectVarName,
            $module,
            zget(array('productplan' => 'plan'), $module, null),
            preg_replace('/s$/', '', $module),
        ))));

        foreach($candidateVars as $varName)
        {
            $object = data($varName);
            if(is_object($object) && isset($object->id)) return array((int)$object->id, $objectVarName ?: $module);

            $id = data($varName . 'ID');
            if($id) return array((int)$id, $objectVarName ?: $module);
        }

        return array(0, $objectVarName ?: $module);
    }

    private function buildAIEntry(): ?node
    {
        global $app;

        $showAIEntry = $this->prop('showAIEntry');
        if($showAIEntry === false) return null;

        $module        = $this->prop('module') ?: $app->rawModule;
        $method        = $this->prop('method') ?: $app->rawMethod;
        if(empty($module) || empty($method) || $method !== 'view' || isAjaxRequest('modal')) return null;

        list($objectID, $objectVarName) = $this->resolveAIObject($module);
        if(empty($objectID)) return null;

        return aiAgentEntry
        (
            set::module($module),
            set::method($method),
            set::type('detail'),
            set::objectID((int)$objectID),
            set::objectVarName($objectVarName),
            set::showAgent((bool)$this->prop('showAgent')),
            set::showTeammate((bool)$this->prop('showTeammate'))
        );
    }

    protected function build()
    {
        $prefix = $this->block('prefix');
        $title  = $this->block('title');
        $suffix = $this->block('suffix');
        $aiEntry = $this->buildAIEntry();

        if(empty($prefix) && !isAjaxRequest('modal')) $prefix = $this->backBtn();

        return div
        (
            setClass('detail-header flex justify-between mb-3 min-w-0 flex-nowrap items-center', $this->prop('class')),
            div
            (
                setClass('flex flex-auto min-w-0 items-center gap-x-4 flex-nowrap pr-5'),
                $prefix,
                $title
            ),
            $this->children(),
            $aiEntry,
            $suffix
        );
    }
}
