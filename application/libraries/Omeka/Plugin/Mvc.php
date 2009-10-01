<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2009
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Omeka
 **/

/**
 * 
 *
 * @package Omeka
 * @copyright Center for History and New Media, 2009
 **/
class Omeka_Plugin_Mvc
{
    /**
     * @var string Path to the root plugins directory.
     */
    protected $_basePath;
    
    // View script directories that have been added by plugins
    protected $_pluginViewDirs = array();
    
    public function __construct($basePath)
    {
        $this->_basePath = $basePath;
    }
    
    /**
     * used by the add_theme_pages() helper to create a list of directories that can store static pages that integrate into the themes
     *
     * @param string $pluginDirName
     * @param string $path
     * @param string $themeType
     * @param string $moduleName
     * @return void
     **/
    protected function addThemeDir($pluginDirName, $path, $themeType, $moduleName)
    {
        if (!in_array($themeType, array('public','admin','shared'))) {
            return false;
        }
        
        //Path must begin from within the plugin's directory
        
        $path = $pluginDirName . DIRECTORY_SEPARATOR . $path;
                
        switch ($themeType) {
            case 'public':
                $this->_pluginViewDirs[$moduleName]['public'][] = $path;
                break;
            case 'admin':
                $this->_pluginViewDirs[$moduleName]['admin'][] = $path;
                break;
            case 'shared':
                $this->_pluginViewDirs[$moduleName]['public'][] = $path;
                $this->_pluginViewDirs[$moduleName]['admin'][] = $path;
                break;
            default:
                break;
        }
    }
    
    public function getModuleViewScriptDirs($moduleName=null)
    {
        if ($moduleName) {
            return $this->_pluginViewDirs[$moduleName];
        }
        return $this->_pluginViewDirs;
    }
    
    /**
     * This will make an entire directory of controllers available to the front controller.
     * 
     * This has to use addControllerDirectory() instead of addModuleDirectory() because module names
     * are case-sensitive and module directories need to be lowercased to conform to Zend's weird naming conventions.
     *
     * @param string $pluginDirName
     * @param string $moduleName 
     * @return void
     **/
    public function addControllerDir($pluginDirName, $moduleName)
    {                
        $contrDir = PLUGIN_DIR . DIRECTORY_SEPARATOR . $pluginDirName . DIRECTORY_SEPARATOR . 'controllers';
        Zend_Controller_Front::getInstance()->addControllerDirectory($contrDir, $moduleName);
    }
    
    /**
     * Set up the following directory structure for plugins:
     * 
     *      controllers/
     *      models/
     *      libraries/
     *      views/
     *          admin/
     *          public/
     *          shared/
     * 
     *  This also adds these folders to the correct include paths.
     *  
     * @param string $pluginDirName
     * @return void
     **/
    public function addApplicationDirs($pluginDirName)
    {        
        $baseDir = $this->_basePath . DIRECTORY_SEPARATOR . $pluginDirName;
        
        $modelDir      = $baseDir . DIRECTORY_SEPARATOR  . 'models';
        $controllerDir = $baseDir . DIRECTORY_SEPARATOR  . 'controllers';
        $librariesDir  = $baseDir . DIRECTORY_SEPARATOR  . 'libraries';
        $viewsDir      = $baseDir . DIRECTORY_SEPARATOR  . 'views';
        $adminDir      = $viewsDir . DIRECTORY_SEPARATOR . 'admin';
        $publicDir     = $viewsDir . DIRECTORY_SEPARATOR . 'public';
        $sharedDir     = $viewsDir . DIRECTORY_SEPARATOR . 'shared';
        
        //Add 'models' and 'libraries' directories to the include path
        if (file_exists($modelDir)) {
            set_include_path(get_include_path() . PATH_SEPARATOR . $modelDir );
        }
        
        if (file_exists($librariesDir)) {
            set_include_path(get_include_path() . PATH_SEPARATOR . $librariesDir);
        }
        
        $moduleName = $this->_getModuleName($pluginDirName);

        //If the controller directory exists, add that 
        if (file_exists($controllerDir)) {
            $this->addControllerDir($pluginDirName, $moduleName);   
        }
        
        if (file_exists($sharedDir)) {
            $this->addThemeDir($pluginDirName, 'views' . DIRECTORY_SEPARATOR . 'shared', 'shared', $moduleName);
        }
        
        if (file_exists($adminDir)) {
            $this->addThemeDir($pluginDirName, 'views' . DIRECTORY_SEPARATOR . 'admin', 'admin', $moduleName);
        }

        if (file_exists($publicDir)) {
            $this->addThemeDir($pluginDirName, 'views' . DIRECTORY_SEPARATOR . 'public', 'public', $moduleName);
        }

    }
    
    /**
     * Retrieve the module name for the plugin (based on the directory name
     * of the plugin).
     * 
     * @param string $pluginDirName
     * @return string
     **/
    protected function _getModuleName($pluginDirName)
    {
        // Module name needs to be lowercased (plugin directories are not, 
        // typically).  Module name needs to go from camelCased to dashed 
        // (ElementSets --> element-sets).
        $inflector = new Zend_Filter_Word_CamelCaseToDash();
        $moduleName = strtolower($inflector->filter($pluginDirName));
        return $moduleName;
    }
    
}