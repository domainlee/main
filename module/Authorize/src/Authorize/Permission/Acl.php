<?php
/**
* @category   	Restaurant library
* @copyright  	http://restaurant.vn
* @license    	http://restaurant.vn/license
*/

namespace Authorize\Permission;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class Acl extends ZendAcl
{
	public function __construct()
	{
		$this->addRole(new Role('Guest'));
		$this->addRole(new Role('Member'), 'Guest');
       
		$this->addRole(new Role('Admin'), 'Member');
		$this->addRole(new Role('Super Admin'), 'Admin');

		$this->addResource('home:index');
        $this->addResource('home:contact');
        $this->addResource('home:search');
        $this->addResource('home:page');
        $this->addResource('home:layout');

		$this->addResource('user:user');
// 		$this->addResource('user:profile');
// 		$this->addResource('user:signin');
// 		$this->addResource('user:signout');

		$this->addResource('order:order');
        $this->addResource('order:cart');

        $this->addResource('article:article');
        $this->addResource('news:news');

        $this->addResource('product:product');
 		$this->addResource('pro:pro');

		$this->addResource('admin:admin');
		$this->addResource('admin:article');
		$this->addResource('admin:articlec');
		$this->addResource('admin:product');
		$this->addResource('admin:productc');
		$this->addResource('admin:banner');
		$this->addResource('admin:position');
		$this->addResource('admin:store');
		$this->addResource('admin:order');
        $this->addResource('admin:media');
        $this->addResource('admin:setup');
        $this->addResource('admin:page');


        $this->addResource('payment:baokim');

		$this->allow('Guest', 'home:index',array('index','search','like', 'loadimages'));
        $this->allow('Guest', 'home:contact',array('index', 'contact'));
        $this->allow('Guest', 'home:search',array('index'));
        $this->allow('Guest', 'home:page',array('index'));

        $this->allow('Guest', 'home:layout', array('index', 'suggestion'));
		//$this->allow('Guest', 'admin:admin',array('index'));
		$this->allow('Guest', 'user:user', array('signin', 'signout', 'signup', 'profile', 'getactivecode', 'getpassword','active', 'changepassword'));
		$this->allow('Guest', 'payment:baokim', array('bpn'));
		$this->allow('Guest', 'pro:pro', array('index'));
		$this->allow('Guest', 'product:product', array('index','view','category','child'));
		$this->allow('Guest', 'article:article', array('index','view','category'));
        $this->allow('Guest', 'news:news', array('index','view','category','tag','blog', 'blogview', 'about','profilo'));

//        $this->allow('Guest', 'order:cart', array('index','add','remove'));
        $this->allow('Guest', 'order:cart', ['index', 'add', 'remove', 'checkout', 'cartsignin', 'quickAdd', 'addwaiting', 'change', 'success']);
        $this->allow('Guest', 'order:order', ['index', 'add', 'remove', 'checkout', 'cartsignin', 'quickAdd', 'addwaiting']);

        //member
		$this->allow('Admin', 'admin:product', array('index','add','edit','delete', 'attr', 'addattr', 'loadattr', 'category', 'addcategory', 'editcategory', 'deletecategory', 'change', 'changec', 'order', 'brand', 'addbrand', 'editbrand', 'deletebrand', 'changeBrand', 'importexcel'));
		$this->allow('Admin', 'admin:productc', array('index','add','edit'));
		$this->allow('Admin', 'admin:article', array('index','add','edit','category','addcategory','changetype', 'scan', 'change','changec', 'delete', 'editcategory'));
		$this->allow('Admin', 'admin:articlec', array('index','add','edit'));
        $this->allow('Admin', 'admin:media', array('index','load','upload','banner', 'add', 'editbanner', 'delete', 'change'));
        $this->allow('Admin', 'admin:setup', array('index', 'menu', 'addmenu', 'editmenu', 'deletemenu', 'changeStatus'));
        $this->allow('Admin', 'admin:page', array('index','add','edit','change',));

//		$this->allow('Member', 'admin:store', array('index','add','edit'));
		$this->allow('Admin', 'admin:order', array('index'));

        $this->allow('Admin', 'admin:admin', array('index'));


        //admin
		$this->allow('Admin', 'admin:admin', array('index'));
		$this->allow('Admin', 'admin:article', array('delete'));
		$this->allow('Admin', 'admin:articlec', array('delete'));
		$this->allow('Admin', 'admin:product', array('delete'));
		$this->allow('Admin', 'admin:productc', array('delete'));
		$this->allow('Admin', 'admin:banner', array('delete'));
		$this->allow('Admin', 'admin:position', array('delete'));
		$this->allow('Admin', 'admin:order', array('edit,delete,changeStatus'));

		

		$this->allow('Super Admin', null);
	}
}













