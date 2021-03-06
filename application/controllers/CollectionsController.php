<?php
/**
 * @copyright Roy Rosenzweig Center for History and New Media, 2007-2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Omeka
 * @access private
 */

/**
 * CRUD controller for Collections
 *
 * @internal This implements Omeka internals and is not part of the public API.
 * @access private
 * @package Omeka
 * @subpackage Controllers
 * @author CHNM
 * @copyright Roy Rosenzweig Center for History and New Media, 2007-2010
 */
class CollectionsController extends Omeka_Controller_AbstractActionController
{
    public $contexts = array('show' => array('omeka-xml', 'omeka-json'));
    
    protected $_browseRecordsPerPage = 10;
        
    public function init()
    {
        $this->_helper->db->setDefaultModelName('Collection');     
    }
    
    public function showAction()
    {
        parent::showAction();
        $this->view->items = $this->_helper->db->getTable('Item')->findBy(
            array('collection' => $this->view->collection->id), is_admin_theme() ? 10 : 5);
    }
    
    protected function _getAddSuccessMessage($collection)
    {
        return __('The collection "%s" was successfully added!', $collection->name);        
    }
    
    protected function _getEditSuccessMessage($collection)
    {
        return __('The collection "%s" was successfully changed!', $collection->name);        
    }
    
    protected function _getDeleteSuccessMessage($collection)
    {
        return __('The collection "%s" was successfully deleted!', $collection->name);        
    }

    protected function _getDeleteConfirmMessage($collection)
    {
        return __('This will delete the collection and its associated metadata. '
             . 'This will not delete any items in this collection, but will '
             . 'delete the reference to this collection in each item.');
    }
}
