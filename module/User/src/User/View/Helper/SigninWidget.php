<?php
/**
 * User\View\Helper\SigninWidget
 *
 * @category   	Shop99 library
 * @copyright  	http://shop99.vn
 * @license    	http://shop99.vn/license
 */

namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use User\Form\Signin as SigninForm;

class SigninWidget extends AbstractHelper {

    /**
     * @var SigninForm
     */
    protected $singinForm;

    /**
     * $var string template used for view
     */
    protected $viewTemplate;

    /**
     * @param array $options array of options
     * @return string
     */
    public function __invoke($options = array()) {
        if (array_key_exists('render', $options)) {
            $render = $options['render'];
        } else {
            $render = true;
        }
        if (array_key_exists('redirect', $options)) {
            $redirect = $options['redirect'];
        } else {
            $redirect = false;
        }

        $vm = new ViewModel(array(
            'signinForm' => $this->getSigninForm(),
            'redirect'  => $redirect,
        ));
        $vm->setTemplate($this->viewTemplate);
        if ($render) {
            return $this->getView()->render($vm);
        } else {
            return $vm;
        }
    }

    /**
     * @return SigninForm
     */
    public function getSigninForm() {
        return $this->singinForm;
    }

    /**
     * @param SigninForm $signinForm
     * @return SigninWidget
     */
    public function setSigninForm(SigninForm $signinForm) {
        $this->signinForm = $signinForm;
        return $this;
    }

    /**
     * @param string $viewTemplate
     * @return SigninWidget
     */
    public function setViewTemplate($viewTemplate) {
        $this->viewTemplate = $viewTemplate;
        return $this;
    }
}