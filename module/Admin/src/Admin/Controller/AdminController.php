<?php 
namespace Admin\Controller; 

use Zend\Mvc\Controller\AbstractActionController; 
use Zend\View\Model\ViewModel; 

class AdminController extends AbstractActionController
{ 
    public function indexAction(){
    	$this->layout('layout/admin');
    }
}