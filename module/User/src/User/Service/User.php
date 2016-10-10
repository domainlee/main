<?php
/**
 * @category   	Restaurant library
 * @copyright  	http://restaurant.vn
 * @license    	http://restaurant.vn/license
 */

namespace User\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapterDb;
//use Zend\Mail\Message;
//use Zend\Mime\Message as MimeMessage;
//use Zend\Mime\Part as MimePart;
//use Zend\Mail\Transport\Smtp as Smtp;
//use Zend\Mail\Transport\SmtpOptions;
//use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail;
use Zend\Mime;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;

class User implements ServiceLocatorAwareInterface {

	/**
	 * @var ServiceLocatorInterface
	 */
	protected $serviceLocator;

	/**
	 * @var AuthenticationService
	 */
	protected $authService;

	/**
	 * @var boolean
	 */
	protected $loadedUser = false;

	/**
	 * @var \User\Model\User
	 */
	protected $user;

	/**
	 * @return \Zend\ServiceManager\ServiceLocatorInterface
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}

	/**
	 * @return \Zend\Authentication\AuthenticationService
	 */
	public function getAuthService() {
		if (null === $this->authService) {
			$this->authService = $this->getServiceLocator()->get('User\Auth\Service');
		}
		return $this->authService;
	}

	/**
	 * @param \Zend\Authentication\AuthenticationService $authService
	 */
	public function setAuthService($authService) {
		$this->authService = $authService;
		return $this;
	}

    /**
	 * @return the $loadedUser
	 */
	public function getLoadedUser() {
		return $this->loadedUser;
	}

	/**
	 * @param boolean $loadedUser
	 */
	public function setLoadedUser($loadedUser) {
		$this->loadedUser = $loadedUser;
		return $this;
	}

	/**
     * @param string $username
     * @param string $password
     * @return boolean
     */
    public function authenticate($username, $password)
    {
    	$authAdapter = new AuthAdapterDb(
			$this->getServiceLocator()->get('dbAdapter'),
			'users',
			'username',
			'password',
    		'MD5(CONCAT(salt,?))'
    	);
    	$authAdapter->setIdentity($username);
    	$authAdapter->setCredential($password);

    	/* @var $result \Zend\Authentication\Result */
//        print_r($authAdapter);die;
    	$result = $this->getAuthService()->authenticate($authAdapter);
//        print_r($result);die;
    	if($result->getCode() == \Zend\Authentication\Result::SUCCESS) {
    		/* @var $userMapper \User\Model\UserMapper */
    		$userMapper = $this->getServiceLocator()->get('User\Model\UserMapper');
    		/* @var $user \User\Model\User */
    		$user = $userMapper->get(null, $username);
			$this->getAuthService()->getStorage()->write($user->getId());

    		return true;
    	}
    	return false;
    }

    public function validateChangeInfo(\User\Model\User $user)
    {
		if (!$user->getId() && !$user->getUsername() && !$user->getEmail()) {return false;}
		$sl = $this->getServiceLocator();
		/*@var $userMapper \User\Model\UserMapper */
		$userMapper = $this->getServiceLocator()->get('User\Model\UserMapper');
		/*@var $adapter \Zend\Db\Adapter\Adapter */
		$adapter = $this->getServiceLocator()->get('dbAdapter');
		/*@var $sql \Zend\Db\Sql\Sql */
		$sql = $this->getServiceLocator()->get('dbSql');
		$select = $sql->select()->from('users')->columns(array('id'));
		if($user->getId()) {
		$select->where(array('id' => $user->getId()));
		}
		if($user->getUsername()) {
			$select->where(array('username' => $user->getUsername()));
		}
		if($user->getEmail()) {
			$select->where(array('email' => $user->getEmail()));
		}
		$select->where(array('password' => new \Zend\Db\Sql\Expression('MD5(CONCAT(salt,"'.$user->getPassword().'"))')));
		$query = $sql->getSqlStringForSqlObject($select);
		$result = $adapter->query($query,$adapter::QUERY_MODE_EXECUTE);
		if($result->count()) {return true;} else {return false;}


    }

    /**
     * @param \User\Model\User $user
     */
    public function signup(\User\Model\User $user) {
		$user->setSalt(substr(md5(rand(2000, 5000) . time() . rand(2000, 5000)), 0, 20));
		$user->setPassword(md5($user->getSalt() . $user->getPassword()));
		$user->setRegisteredDate(date('Y-m-d'));
		$user->setActiveKey((md5($user->getUsername() . $user->getPassword() . time())));
		$user->setActiveLink('http://'.$_SERVER['HTTP_HOST'].'/user/active?u='.$user->getUsername() .'&c='.$user->getActiveKey());
		$translator = $this->getServiceLocator()->get('translator');
		/* @var $mapper \User\Model\UserMapper */
		$mapper = $this->getServiceLocator()->get('User\Model\Usermapper');

		$mapper->save($user);

 		$body  = sprintf($translator->translate('<strong>Xin chào '.$user->getUsername().' !</strong>'));
 		$body .= '<br/><br/>';
 		$body .= $translator->translate('Để hoàn thành quá trình đăng kí, xin nhấp vào đường dẫn bên dưới để kích hoạt tài khoản của bạn');
 		$body .= '<br/><br/>';
 		$body .= '<a href='.$user->getActiveLink().'>'.$user->getActiveLink().'</a>';
 		$body .= '<br/><br/>';
 		$body .= $translator->translate('Xin cảm ơn!');
 		$body .= '<br><br/>';

        $title = 'Email kích hoạt tài khoản';
        $subject = 'DWEB.VN - Email kích hoạt tài khoản';
        $to = $user->getEmail();

        $options   = new SmtpOptions(array(
            'host'              => 'smtp.gmail.com',
            'connection_class'  => 'login',
            'connection_config' => array(
                'ssl'       => 'tls',
                'username' => 'differencewebsite@gmail.com',
                'password' => 'domainlee1790'
            ),
            'port' => 587,
        ));

        $html = new Mime\Part($body);
        $html->type = Mime\Mime::TYPE_HTML;
        $html->charset = "UTF-8";

        $body = new Mime\Message();
        $body->setParts([$html]);

        $message = new Mail\Message();
        $message->setTo($to)
            ->setFrom('no-reply@nhanh.vn', $title)
            ->setSubject($subject)
            ->setBody($body)
            ->setEncoding("UTF-8");
        $transport = new Mail\Transport\Smtp();
        $transport->setOptions($options);
        $transport->send($message);

        return true;

    }
		/**
		 *
		 * @param \User\Model\User $user
		 */
	 public function sendActiveLink(\User\Model\User $user)
    {
		$sl = $this->getServiceLocator();
		/*@var $userMapper \User\Model\UserMapper */
		$userMapper = $this->getServiceLocator()->get('User\Model\UserMapper');
		$translator = $this->getServiceLocator()->get('translator');
		$us = $userMapper->get($user->getId(), $user->getUsername(), $user->getEmail());
		$us->setActiveLink('http://'.$_SERVER['HTTP_HOST'].'/user/active?u='.$user->getUsername() .'&c='.$us->getActiveKey());
		$message = new Message();
		$message->addTo($us->getEmail());
		$message->addFrom('noreply.shop99.vn@gmail.com');
		$message->setSubject('Welcome to ' . $_SERVER['HTTP_HOST']);
		$body  = sprintf($translator->translate("Xin chào %s"), $us->getFullName());
		$body .= "<br><br>";
		$body .= $translator->translate('Để hoàn thành quá trình đăng kí, xin nhấp vào đường dẫn bên dưới để kích hoạt tài khoản của bạn');
		$body .= "<br><br>";
		$body .= "<a href='{$us->getActiveLink()}'>{$us->getActiveLink()}</a>";
		$body .= "<br><br>";
		$body .= $translator->translate('Xin cảm ơn!');
		$body .= "<br>";
		$html = new MimePart($body);
		$html->type = 'text/html';
		$content = new MimeMessage();
		$content->setParts(array($html));
		$message->setBody($content);
		$smtp = new Smtp();
		$smtpCfgs = $this->getServiceLocator()->get('Config');
		$smtp->setOptions(new SmtpOptions($smtpCfgs['smtpOptions']));
		$smtp->send($message);
    }

    /**
     * @param \User\Model\User $user
     */
    public function resetPassword(\User\Model\User $user)
    {
		$newPassword = substr(md5(rand(2000, 5000) . time() . rand(2000, 5000)), 0, 8);
		$sl = $this->getServiceLocator();
		/*@var $userMapper \User\Model\UserMapper */
		$userMapper = $this->getServiceLocator()->get('User\Model\UserMapper');
		$translator = $this->getServiceLocator()->get('translator');
		$us = $userMapper->get($user->getId(), $user->getUsername(), $user->getEmail());
		$us->setSalt(substr(md5(rand(2000, 5000) . time() . rand(2000, 5000)), 0, 20));
		$us->setPassword(md5($us->getSalt() . $newPassword));
		$userMapper->updateUser($us);

		$message = new Message();
		$message->addTo($us->getEmail());
		$message->addFrom('noreply.shop99.vn@gmail.com');
		$message->setSubject('Welcome to ' . $_SERVER['HTTP_HOST']);
		$body  = sprintf($translator->translate("Xin chào %s"), $us->getFullName());
		$body .= "<br><br>";
		$body .= $translator->translate('Mật khẩu tài khoản của bạn đã được reset là, mật khẩu mới là :');
		$body .= '<br/><strong>'.$newPassword.'</strong>';
		$body .= "<br/><br/>";
		$body .= $translator->translate('Bạn vui lòng đăng nhập lại website và đổi lại mật khẩu.');
		$body .= $translator->translate('Xin cảm ơn!');
		$body .= "<br>";
		$html = new MimePart($body);
		$html->type = 'text/html';
		$content = new MimeMessage();
		$content->setParts(array($html));
		$message->setBody($content);
		$smtp = new Smtp();
		$smtpCfgs = $this->getServiceLocator()->get('Config');
		$smtp->setOptions(new SmtpOptions($smtpCfgs['smtpOptions']));
		$smtp->send($message);

    }

    /**
     *
     * @param \User\Model\User $user
     * @return boolean
     */
	public function updateUser(\User\Model\User $user)
	{
		if(!$user->getId() && !$user->getEmail() &&  !$user->getUsername()) {return false;}
		/*@var $userMapper \User\Model\UserMapper */
		$userMapper = $this->getServiceLocator()->get('User\Model\UserMapper');
		if($user->getPassword()) {
			$user->setSalt(substr(md5(rand(2000, 5000) . time() . rand(2000, 5000)), 0, 20));
			$user->setPassword(md5($user->getSalt() . $user->getPassword()));
		}
		return $userMapper->updateUser($user);
	}

    /**
     * clear identity
     */
    public function signout() {
		$this->getAuthService()->clearIdentity();
    }

    /**
     * @return bool
     */
    public function hasIdentity() {
        return $this->getAuthService()->hasIdentity();
    }

    /**
     * @return mixed
     */
    public function getIdentity() {
        return $this->getAuthService()->getIdentity();
    }

    /**
     * @return \User\Model\User
     */
    public function getUser() {
		if(!$this->getLoadedUser()) {
			$userMapper = $this->getServiceLocator()->get('User\Model\UserMapper');
			$this->user = $userMapper->get($this->getIdentity());
		}
		return $this->user;
    }

    /**
     * @return string
     */
	public function getRoleName() {
		if($this->hasIdentity()) {
			return $this->getUser()->getSystemRoleName();
		}
		return 'Guest';
	}

    /**
     * @return int
     */
    public function getRole(){
        if ($this->hasIdentity()){
            return $this->getUser()->getRole();
        }
        return 0;
    }

    /**
     * @return bool
     */
    public function canViewInventoryAlert(){
        if ($this->hasIdentity()){
            $roles = array(
                \User\Model\User::ROLE_SUPERADMIN,
                \User\Model\User::ROLE_ADMIN);
            return in_array($this->getRole(), $roles);
        }
    }

    /**
     * @return string
     */
	public function getId() {
		if($this->hasIdentity()) {
			return $this->getUser()->getId();
		}
		return null;
	}

    public function getStoreId() {
        if($this->hasIdentity()) {
            return $this->getUser()->getStoreId();
        }
        return null;
    }

	/**
	 * @return string
	 */
	public function getUsername() {
		if($this->hasIdentity()) {
			return $this->getUser()->getUsername();
		}
		return '';
	}

	/**
	 * get manageable accounts;
	 * if option['toSelectBoxArray'] is available, convert the result to array(id => name)
	 *
	 * @return array
	 */
	public function getManageableAccounts($options = null) {
		$accounts = $this->getUser()->getManageableAccounts();
		if(isset($options['toSelectBoxArray']) && $options['toSelectBoxArray'] == true) {
			$account = new \Account\Model\Account();
			return $account->toSelectBoxArray($accounts);
		}
		return $accounts;
	}

	/**
	 * get manageable restaurants;
	 * if option['toSelectBoxArray'] is available, convert the result to array(id => name)
	 *
	 * @return array
	 */
	public function getManageableRestaurants($options = null) {
		$ress = $this->getUser()->getManageableRestaurants();
		if(isset($options['toSelectBoxArray']) && $options['toSelectBoxArray'] == true) {
			$res = new \Restaurant\Model\Restaurant();
			return $res->toSelectBoxArray($ress);
		}
		return $ress;
	}

	/**
	 * get manageable hotels;
	 * if option['toSelectBoxArray'] is available, convert the result to array(id => name)
	 *
	 * @return array
	 */
	public function getManageableHotels($options = null) {
		$items = $this->getUser()->getManageableHotels();
		if(isset($options['toSelectBoxArray']) && $options['toSelectBoxArray'] == true) {
			$item = new \Hotel\Model\Hotel();
			return $item->toSelectBoxArray($items);
		}
		return $items;
	}

	public function getManageableRoles() {
		return $this->getUser()->getManageableRoles();
	}
	public function getManageableHotelRoles() {
		return $this->getUser()->getManageableHotelRoles();
	}

	public function isAdmin() {
		return $this->getUser()->isAdmin();
	}

    public function isSuperAdmin()
    {
        return $this->getUser()->isSuperAdmin();
    }
}