<?php

class MoreUserRolesPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array('define_acl','uninstall');


    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];


        // AUTHORS inherit the rights of Contributors...
		$acl->addRole(new Zend_Acl_Role('author'), 'contributor');		
		// ... but are able to publish their own items
		$acl->allow('author','Items',array('makePublic','makeFeatured'));

        
        // EDITORS inherit the rights of Authors...
		$acl->addRole(new Zend_Acl_Role('editor'), 'author');		
		// ... but are able to edit and delete Items created by other users
		$acl->allow('editor','Items',array('edit','delete'));
		$acl->allow('editor','Files',array('edit','delete'));
		
    }
    
        
    public function hookUninstall(){
	    // Upon uninstalling the plugin, revert the roles of Authors and Editors back to Contributor
        $db = $this->_db;
        $sql = "UPDATE `omeka_users` SET `role`='contributor' WHERE `role`= 'author' or `role`='editor' ";
        $db->query($sql);
    }

}