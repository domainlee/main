<?php
/**
 * User\Controller
 *
 * @category   	Restaurant library
 * @copyright  	http://restaurant.vn
 * @license    	http://restaurant.vn/license
 */

namespace User\Controller;

use Zend\Mvc\Controller\Plugin\Redirect;

use Zend\Mvc\View\Console\ViewManager;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
include_once '_PhpLibs/google/Google_Client.php';
include_once '_PhpLibs/google/contrib/Google_Oauth2Service.php';

class SigninController extends AbstractActionController {

	/**
	 * index
	 */
	public function indexAction()
	{
		$redirect = trim($this->getRequest()->getQuery()->get('redirect'));
		if($this->user()->hasIdentity()) {
			if(!$redirect) {
				return $this->redirect()->toUrl('/pos/order/add');
				//return $this->redirect()->toRoute('home');
			}
			return $this->redirect()->toUrl($redirect);
		}
		/* @var $sl \Zend\ServiceManager\ServiceLocatorInterface */
		$sl = $this->getServiceLocator();

		/* @var $form \User\Form\Signin */
		$form = $this->getServiceLocator()->get('User\Form\Signin');
		$form->setInputFilter($this->getServiceLocator()->get('User\Form\SigninFilter'));

		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			$form->setData($this->getRequest()->getPost());
			if($form->isValid()) {
				$username = $form->getInputFilter()->getValue('username');
				$password = $form->getInputFilter()->getValue('password');
				/* @var $serviceUser \User\Service\User */
				$serviceUser = $this->getServiceLocator()->get('User\Service\User');
				// @todo show captcha after signing 3 times failed
				if(!$serviceUser->authenticate($username, $password)) {
					$form->showInvalidMessage();
				} else {
					/* @var $user \User\Model\User */
					$user = $serviceUser->getUser();
					if(!$user->getLocked() && $user->getActive()) {
						if(!$redirect) {
							return $this->redirect()->toUrl('/pos/order/add');
							//return $this->redirect()->toRoute('home');
						} else {
							return $this->redirect()->toUrl($redirect);
						}
					}
					if($user->getLocked()) {
						$form->showInvalidMessage(\User\Form\Signin::ERROR_LOCKED);
					}
					if(!$user->getActive()) {
						$form->showInvalidMessage(\User\Form\Signin::ERROR_INACTIVE);
					}
				}
			}
		}

		// Google authentication
		/*@var $googleLogin \User\Service\GoogleLogin */
// 		$googleLogin = $this->getServiceLocator()->get('User\Service\GoogleLogin');
// 		$authUrl = $googleLogin->getAuthenticationUrl();
// 		$client = new \Google_Client();
// 		$oauth2 = new \Google_Oauth2Service($client);

// 		$authUrl = $client->createAuthUrl();
		$viewModel = new ViewModel(array(
			'form' => $form,
// 			'googlelink' => $authUrl
		));
		return $viewModel;
    }

    public function facebookAction() {
		$data = null;
		return new ViewModel($data);
    }

    public function googleAction() {
    	/*@var $googleLogin \User\Service\GoogleLogin */
    	$googleLogin = $this->getServiceLocator()->get('User\Service\GoogleLogin');
    	$authUrl = $googleLogin->getAuthenticationUrl();
    	$client = $googleLogin->getGoogleClient();
    }
}