<?php
namespace Codelib\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;

class CiautoController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Codelib';
        parent::__construct();

        $this->link = $this->_connectDb();
        //echo 'init ciauto';
    }

    public function index()
    {
        $templates['controller'] = file_get_contents($this->modulePath . '/data/template_controller.txt');
        $templates['view'] = file_get_contents($this->modulePath . '/data/template_view.txt');
        $templates['model'] = file_get_contents($this->modulePath . '/data/template_model.txt');
        
        //$databases = array('workshop_new_luxury', 'workshop_new_pay', 'workshop_new_passport');
        //$databases = array('workspace_ilc');
        $databases = array('workbench_website', 'workbench_pay', 'workbench_passport');
        foreach ($databases as $database) {
            $tables = $this->getTables($database);
            if (is_array($tables) && !empty($tables)) {
                foreach ($tables as $table => $comment) {
                    $this->createFiles($database, $table, $templates);
                }
            }
        }
    }

    protected function getTables($database)
    {
        $tables = array();
        $sql = "SELECT * FROM `information_schema`.`tables` WHERE `TABLE_SCHEMA` = '{$database}'";
        $result = mysql_query($sql);
        while ($row = mysql_fetch_array($result)) {
            $tables[$row['TABLE_NAME']] = $row['TABLE_COMMENT'];
        }
        //print_r($tables);exit();
        return $tables;
    }
    
    protected function createFiles($database, $table, $templates)
    {
        $tableBase = substr($table, strpos($table, '_') + 1);
        $tableFirst = ucfirst($table);
        foreach ($templates as $template => $contentBase) {
            
            $fieldInfos = $this->getFieldInfos($database, $table);
            $targetStrs = array("\r", '#FIELDINFOS#', '#CLASS#', '#TABLE#');
            $replaceStrs = array('', $fieldInfos, ucfirst($tableBase), $tableBase);
            $content = str_replace($targetStrs, $replaceStrs, $contentBase);
        
            $filePath = $this->modulePath . '/data/ci/' . $database . '_' . $template;
            if (!is_dir($filePath)) {
                mkdir($filePath);
            }
            $fileName = $filePath . '/' . ucfirst($tableBase) . 'model.php';
            $fileName = $template == 'model' ? $fileName : str_replace('model', '', $fileName);
            
            echo $fileName . '<br />';
        
            $strlen = file_put_contents($fileName, $content);
        }
    }
    
    protected function getFieldInfos($database, $table)
    {
        $sql = "SELECT * FROM `information_schema`.`columns` WHERE `TABLE_SCHEMA` = '{$database}' AND `TABLE_NAME` = '{$table}'";
        $result = mysql_query($sql);
        $fieldInfos = array();
        $string = "        \$fieldInfos['fields'] = array(\n";
        $fieldChange = "        \$fieldInfos['change'] = array(";
        while ($row = mysql_fetch_array($result)) {
            $field = $row['COLUMN_NAME'];
            $comment = empty($row['COLUMN_COMMENT']) ? $field : $row['COLUMN_COMMENT'];
            //$string .=  "            '{$field}' => array('name' => '{$comment}'),\n";
            $string .=  "            '{$field}' => '{$comment}',\n";
            $fieldInfos['fields'][$row['COLUMN_NAME']] = $row['COLUMN_COMMENT'];
            $fieldChange .= $field == 'id' ? '' : "'{$field}', ";
        }
        $fieldChange = rtrim(rtrim($fieldChange), ',') . ');';
        $string .= "        );\n$fieldChange";
    
        return $string;
    }
}
