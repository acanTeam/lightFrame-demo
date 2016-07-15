<?php
namespace Codelib\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;

class YiiController extends ControllerAbstract
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
        $templates['controllers'] = file_get_contents($this->modulePath . '/data/controller.txt');
        $templates['models'] = file_get_contents($this->modulePath . '/data/model.txt');
        $templates['models_searchs'] = file_get_contents($this->modulePath . '/data/model_search.txt');
        $templates['view'] = $this->modulePath . '/data/views';
        
        //$databases = array('workbench_website', 'workbench_paytrade', 'workbench_passport');
        //$databases = array('workspace_gallerycms', 'workspace_spider');
        $databases = array('workspace_shoot');
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
		$tableFirst = str_replace(' ', '', ucwords(str_replace('_', ' ', $tableBase)));
		$databaseBase = str_replace('workspace_', '', $database);

		$views = $templates['view'];
		$viewsTarget = dirname($views) . '/yii2/' . $databaseBase . '/views/' . str_replace('_', '-', $tableBase);
		if (!is_dir($viewsTarget)) {
		    mkdir($viewsTarget, 0777, true);
		}
		foreach (array('_form.php', 'add.php', 'listinfo.php', 'update.php', 'view.php') as $viewFile) {
			copy($views . '/' . $viewFile, $viewsTarget . '/' . $viewFile);
		}
		unset($templates['view']);

        foreach ($templates as $template => $contentBase) {
            
            $fieldInfos = $this->getFieldInfos($database, $table);
            $targetStrs = array("\r", '#FIELDINFOS#', '#MODULE#', '#F_MODULE#', '#CLASS#', '#TABLE#');
            $replaceStrs = array('', $fieldInfos, $databaseBase, ucfirst($databaseBase), $tableFirst, $tableBase);
            $content = str_replace($targetStrs, $replaceStrs, $contentBase);
        
            $filePath = $this->modulePath . '/data/yii2/' . $databaseBase . '/' . str_replace('_', '/', $template);
            if (!is_dir($filePath)) {
                mkdir($filePath, 0777, true);
            }
            $fileName = $filePath . '/' . $tableFirst . 'Controller.php';
            $fileName = $template == 'controllers' ? $fileName : str_replace('Controller', '', $fileName);
            
            echo $fileName . '<br />';
        
            $strlen = file_put_contents($fileName, $content);
        }
    }
    
    protected function getFieldInfos($database, $table)
    {
        $sql = "SELECT * FROM `information_schema`.`columns` WHERE `TABLE_SCHEMA` = '{$database}' AND `TABLE_NAME` = '{$table}'";
        $result = mysql_query($sql);
        $fieldInfos = array();
        $string = '';
        while ($row = mysql_fetch_array($result)) {
            $field = $row['COLUMN_NAME'];
            $comment = empty($row['COLUMN_COMMENT']) ? $field : $row['COLUMN_COMMENT'];
            //$string .=  "            '{$field}' => array('name' => '{$comment}'),\n";
            $string .=  "            '{$field}' => '{$comment}',\n";
            $fieldInfos['fields'][$row['COLUMN_NAME']] = $row['COLUMN_COMMENT'];
        }
    
        return $string . '        ';
    }
}
