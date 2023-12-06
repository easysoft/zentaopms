<?php
declare(strict_types=1);
class pivotTao extends pivotModel
{
    /**
     * 设置默认的过滤器
     * Set default filter.
     *
     * @param  array  $filters
     * @access public
     * @return void
     */
    protected function setFilterDefault(array &$filters): void
    {
        foreach($filters as &$filter)
        {
            if(empty($filter['default'])) continue;
            if(is_string($filter['default'])) $filter['default']= $this->processDateVar($filter['default']);
        }
    }

    /**
     * 从透视表对象中获取字段
     * Get fields from pivot object.
     *
     * @param  object    $pivot
     * @param  string    $key
     * @param  mixed     $default
     * @param  bool      $jsonDecode
     * @param  bool      $needArray
     * @access protected
     * @return mixed
     */
    protected function getFieldsFromPivot(object $pivot, string $key, mixed $default, bool $jsonDecode = false, bool $needArray = false)
    {
        return isset($pivot->{$key}) && !empty($pivot->{$key}) ? ($jsonDecode ? json_decode($pivot->{$key}, $needArray) : $pivot->{$key}) : $default;
    }

    /**
     * 重建透视表filedSettings字段
     * Rebuild fieldSettings field of pivot.
     *
     * @param  object $pivot
     * @param  array  $fieldPairs
     * @param  object $columns
     * @param  array  $relatedObject
     * @param  object $fieldSettings
     * @access public
     * @return void
     */
    public function rebuildFieldSetting(object $pivot, array $fieldPairs, object $columns, array $relatedObject, object $fieldSettings): void
    {
        $fieldSettingsNew = new stdclass();

        foreach($fieldPairs as $index => $field)
        {
            $defaultType   = $columns->$index;
            $defaultObject = $relatedObject[$index];

            if(isset($objectFields[$defaultObject][$index])) $defaultType = $objectFields[$defaultObject][$index]['type'] == 'object' ? 'string' : $objectFields[$defaultObject][$index]['type'];

            if(!isset($fieldSettings->$index))
            {
                /* 如果字段设置中没有该字段，则使用默认值 */
                /* If the field is not set in the field settings, use the default value. */
                $fieldItem = new stdclass();
                $fieldItem->name   = $field;
                $fieldItem->object = $defaultObject;
                $fieldItem->field  = $index;
                $fieldItem->type   = $defaultType;

                $fieldSettingsNew->$index = $fieldItem;
            }
            else
            {
                /* 兼容旧版本的字段设置，当为空或者为布尔值时，使用默认值 */
                /* Compatible with old version of field settings, use default value when empty or boolean. */
                if(!isset($fieldSettings->$index->object) || is_bool($fieldSettings->$index->object) || strlen($fieldSettings->$index->object) == 0) $fieldSettings->$index->object = $defaultObject;

                /* 当字段设置中没有字段名时，使用默认的字段名配置。 */
                /* When there is no field name in the field settings, use the default field name configuration. */
                if(!isset($fieldSettings->$index->field) || strlen($fieldSettings->$index->field) == 0)
                {
                    $fieldSettings->$index->field  = $index;
                    $fieldSettings->$index->object = $defaultObject;
                    $fieldSettings->$index->type   = 'string';
                }

                $object = $fieldSettings->$index->object;
                $type   = $fieldSettings->$index->type;
                if($object == $defaultObject && $type != $defaultType) $fieldSettings->$index->type = $defaultType;

                $fieldSettingsNew->$index = $fieldSettings->$index;
            }
        }

        $pivot->fieldSettings = $fieldSettingsNew;
    }
}
