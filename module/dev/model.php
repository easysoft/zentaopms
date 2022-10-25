<?php
class devModel extends model
{
    /**
     * Get All tables.
     *
     * @access public
     * @return array
     */
    public function getTables()
    {
        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        $sql = "SHOW TABLES";
        $tables = array();
        $datatables = $this->dbh->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        foreach($datatables as $table)
        {
            $table = current($table);
            if(empty($this->config->db->prefix) or strpos($table, $this->config->db->prefix) !== false)
            {
                if(strpos($table, $this->config->db->prefix . 'flow_') === 0) continue;

                $subTable = substr($table, strpos($table, '_') + 1);
                $group    = zget($this->config->dev->group, $subTable, 'other');
                $tables[$group][$subTable] = $table;
            }
        }
        return $tables;
    }

    /**
     * Get fields of table.
     *
     * @param  string $table
     * @access public
     * @return void
     */
    public function getFields($table)
    {
        $module      = substr($table, strpos($table, '_') + 1);
        $aliasModule = $subLang = '';
        $this->app->loadLang($module);
        try
        {
            if(isset($this->config->dev->tableMap[$module])) $aliasModule = $this->config->dev->tableMap[$module];
            if(strpos($aliasModule, '-') !== false) list($aliasModule, $subLang) = explode('-', $aliasModule);
            if(!empty($aliasModule) and strpos($module, 'im_') === false) $this->app->loadLang($aliasModule);
        }
        catch(PDOException $e)
        {
            $this->lang->$module = new stdclass();
        }

        try
        {
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            $sql = "DESC $table";
            $rawFields = $this->dbh->query($sql)->fetchAll();
            $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        }
        catch (PDOException $e)
        {
            $this->sqlError($e);
        }

        foreach($rawFields as $rawField)
        {
            $firstPOS = strpos($rawField->type, '(');
            $type     = substr($rawField->type, 0, $firstPOS > 0 ? $firstPOS : strlen($rawField->type));
            $type     = str_replace(array('big', 'small', 'medium', 'tiny'), '', $type);
            $field    = array();
            $field['name'] = (isset($this->lang->$module->{$rawField->field}) and is_string($this->lang->$module->{$rawField->field})) ? sprintf($this->lang->$module->{$rawField->field}, $this->lang->dev->tableList[$module]) : '';
            if((empty($field['name']) or !is_string($field['name'])) and $aliasModule) $field['name'] = isset($this->lang->$aliasModule->{$rawField->field}) ? $this->lang->$aliasModule->{$rawField->field} : '';
            if($subLang) $field['name'] = isset($this->lang->$aliasModule->$subLang->{$rawField->field}) ? $this->lang->$aliasModule->$subLang->{$rawField->field} : $field['name'];
            if(!is_string($field['name'])) $field['name'] = '';
            $field['null'] = $rawField->null;

            if($type == 'enum' or $type == 'set')
            {
                $rangeBegin  = $firstPOS + 2;                       // Remove the first quote.
                $rangeEnd    = strrpos($rawField->type, ')') - 1;   // Remove the last quote.
                $range       = substr($rawField->type, $rangeBegin, $rangeEnd - $rangeBegin);
                $field['type'] = $rawField->type;
                $field['options']['enum']  = str_replace("','", ',', $range);
            }
            elseif($type == 'varchar')
            {
                $begin  = $firstPOS + 1;
                $end    = strpos($rawField->type, ')', $begin);
                $length = substr($rawField->type, $begin, $end - $begin);
                $field['type']   = 'varchar';
                $field['options']['max'] = $length;
                $field['options']['min'] = 0;
            }
            elseif($type == 'char')
            {
                $begin  = $firstPOS + 1;
                $end    = strpos($rawField->type, ')', $begin);
                $length = substr($rawField->type, $begin, $end - $begin);
                $field['type']   = 'char';
                $field['options']['max'] = $length;
                $field['options']['min'] = 0;
            }
            elseif($type == 'int')
            {
                $begin  = $firstPOS + 1;
                $end    = strpos($rawField->type, ')', $begin);
                $length = substr($rawField->type, $begin, $end - $begin);
                $field['type'] = 'int';
                $field['options']['max'] = $length;
                $field['options']['min'] = 0;
            }
            elseif($type == 'float' or $type == 'double')
            {
                $field['type'] = 'float';
            }
            elseif($type == 'date')
            {
                $field['type'] = 'date';
            }
            else
            {
                $field['type'] = $type;
            }
            $fields[$rawField->field] = $field;
        }
        return $fields;
    }

    /**
     * Get APIs of a module.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function getAPIs($module)
    {
        $fileName = $this->app->getModuleRoot() . $module . DS . 'control.php';
        if(!file_exists($fileName))
        {
            $extPaths = $this->getModuleExtPath();
            foreach($extPaths as $extPath)
            {
                $fileName = $extPath . $module . DS . 'control.php';
                if(file_exists($fileName)) break;
            }
        }
        if($module != 'common' and $module != 'dev') include $fileName;

        $classReflect = new ReflectionClass($module);
        $methods = $classReflect->getMethods();
        $apis = array();
        foreach($methods as $method)
        {
            if($method->class == 'baseControl' or $method->class == 'control' or $method->name == '__construct') continue;
            $api = array('name' => $method->name, 'post' => false, 'param' => array());
            $methodReflect = new ReflectionMethod($module, $method->name);
            foreach($methodReflect->getParameters() as $key => $param)
            {
                $paramName = $param->getName();
                $api['param'][$paramName] = array('var' => $paramName, 'type' => '', 'desc' => '');
            }

            $startLine = $methodReflect->getStartLine();
            $endLine   = $methodReflect->getEndLine();
            $comment   = $methodReflect->getDocComment();

            if($startLine > 0)
            {
                $file = file($fileName);
                for($i = $startLine - 1; $i <= $endLine; $i++)
                {
                    if(strpos($file[$i], '$this->post') or strpos($file[$i], 'fixer::input') or strpos($file[$i], '$_POST')) $api['post'] = true;
                }
            }

            if($comment)
            {
                // Strip the opening and closing tags of the docblock.
                $comment = substr($comment, 3, -2);

                // Split into arrays of lines.
                $comment = preg_split('/\r?\n\r?/', $comment);

                // Trim asterisks and whitespace from the beginning and whitespace from the end of lines.
                $comment = array_map(array('devModel', "trimSpace"), $comment);

                // Group the lines together by @tags
                $blocks = array();
                $b = -1;
                foreach($comment as $line)
                {
                    if(isset($line[1]) && $line[0] == '@' && ctype_alpha($line[1])) $b++;
                    if($b == -1) $b = 0;

                    if(!isset($blocks[$b])) $blocks[$b] = array();
                    $blocks[$b][] = $line;
                }

                // Parse the blocks
                foreach($blocks as $block => $body)
                {
                    $body = trim(implode("\n", $body));
                    if($block == 0 && !(isset($body[1]) && $body[0] == '@' && ctype_alpha($body[1])))
                    {
                        // This is the description block
                        $api['desc'] = $body;
                        continue;
                    }

                    // This block is tagged
                    if(preg_match('/^@[a-z0-9_]+/', $body, $matches))
                    {
                        $tag  = substr($matches[0], 1);
                        $body = substr($body, strlen($tag) + 2);
                        if($tag != 'param')
                        {
                            $api[$tag][] = $body;
                            continue;
                        }

                        $parts        = preg_split('/\s+/', trim($body), 3);
                        $parts        = array_pad($parts, 3, null);
                        $property     = array('type', 'var', 'desc');
                        $param        = array_combine($property, $parts);
                        $paramName    = substr($param['var'], 1);
                        $param['var'] = $paramName;
                        if(isset($api['param'][$paramName])) $api['param'][$paramName] = $param;
                    }
                }
            }
            $apis[] = $api;
        }
        return $apis;
    }

    /**
     * Get all modules.
     *
     * @access public
     * @return array
     */
    public function getModules()
    {
        $moduleList = glob($this->app->getModuleRoot() . '*');
        $modules = array();
        foreach($moduleList as $module)
        {
            if(!file_exists($module . DS . 'control.php')) continue;

            $module = basename($module);
            if($module == 'editor' or $module == 'help' or $module == 'setting' or $module == 'common') continue;
            $group  = zget($this->config->dev->group, $module, 'other');
            $modules[$group][$module] = $module;
        }

        $extPaths = $this->getModuleExtPath();
        foreach($extPaths as $extPath)
        {
            if(empty($extPath)) continue;
            foreach(glob($extPath . '*') as $path)
            {
                if(!file_exists($path . DS . 'control.php')) continue;

                $module = basename($path);
                if($module == 'editor' or $module == 'help' or $module == 'setting' or $module == 'common') continue;
                $group  = zget($this->config->dev->group, $module, 'other');
                $modules[$group][$module] = $module;
            }
        }

        return $modules;
    }

    /**
     * Get module ext path.
     *
     * @access public
     * @return array
     */
    public function getModuleExtPath()
    {
        $extPaths = array();
        if($this->config->edition != 'open') $extPaths['common'] = $this->app->getExtensionRoot() . $this->config->edition . DS;
        $extPaths['xuan'] = $this->app->getExtensionRoot() . 'xuan' . DS;

        return $extPaths;
    }

    /**
     * Trim asterisks and whitespace from the beginning and whitespace from the end of lines.
     *
     * @param  string    $line
     * @access public
     * @return string
     */
    public function trimSpace($line)
    {
        return ltrim(rtrim($line), "* \t\n\r\0\x0B");
    }
}
