<?php
/**
 * Home\Controller
 *
 * @category       Shop99 library
 * @copyright      http://shop99.vn
 * @license        http://shop99.vn/license
 */

namespace Home\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Home\Model\Contact;
use Zend\View\Model\JsonModel;

class ContactController extends AbstractActionController
{

    public function indexAction()
    {
        $data = array_merge_recursive(
            $this->getRequest()->getPost()->toArray(),
            $this->getRequest()->getFiles()->toArray());
        $validator = new \Zend\Validator\EmailAddress();
        $validatorf = new \Zend\Validator\File\Size(250000);
        $validatorf = new \Zend\Validator\File\Extension(array('psd','rar','zip'));
        if($this->getRequest()->isPost()){
            $contact = new Contact();
            $contactMapper = $this->getServiceLocator()->get('Home\Model\ContactMapper');
            if(!$validator->isValid(strip_tags($data['email']))){
                return new JsonModel(['code' => 0,'ms' => 'Bạn đang để trống email hoặc email chưa hợp lệ']);
            }else{
                $contact->setEmail($data['email']);
            }
            if(!is_numeric($data['phone']) || strlen($data['phone']) != 10 && strlen($data['phone']) != 11){
                return new JsonModel(['code' => 0,'ms' => 'Bạn đang để trống số điện thoại hoặc số điện thoại chưa hợp lệ']);
            }else{
                $contact->setPhone($data['phone']);
            }
            if($data['url'] == '' xor $data['file'] != 'undefined'){
                return new JsonModel(['code' => 0,'ms' => 'Bạn không được để trống 1 trong 2 trường "Đường dẫn" hoặc "File"']);
            }
            if($data['url']){
                $contact->setUrl($data['url']);
            }
            if($data['file'] != 'undefined'){
                if(!$validatorf->isValid($data['file'])){
                    return new JsonModel(['code' => 0,'ms' => 'Chúng tôi chưa hộ trợ upload File này']);
                }else{
                    $contact->setFile($data['file']['name']);
                    $targetFolder = \Base\Model\Uri::getSavePath($contact);

                    if (! file_exists($targetFolder)) {
                        $oldmask = umask(0);
                        mkdir($targetFolder, 0777, true);
                        umask($oldmask);
                    }
                    rename($data['file']['tmp_name'], $targetFolder . '/' . $data['file']['name']);
                }
            }
//            $contactMapper->save($contact);
            return new JsonModel(['code' => 1,'ms' => 'Cảm ơn bạn đã lựa chọn chúng tôi. Chúng tôi sẽ nỗ lực để có một sản phẩm tốt']);
        }
        return null;
    }

    public function contactAction()
    {
        $error = [];
        if($this->getRequest()->isPost()){
            $validator = new \Zend\Validator\EmailAddress();
            $a = $this->getRequest()->getPost()->toArray();
            $fullName  = $a['fullName'];
            $email = $a['email'];
            $phone = $a['phone'];
            $content = $a['content'];

            $contact = new \Home\Model\Contact();
            $contactMapper = $this->getServiceLocator()->get('Home\Model\ContactMapper');

            $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();
            $contact->setStoreId($storeId);

            if(strpos($fullName, 'script')){
                $error['fullName'] = 'Chúng tôi chưa hỗ trợ định dạng này';
            }else{
                $contact->setName($fullName);
            }
            if(!$validator->isValid($email) || $email == ''){
                $error['email'] = 'Email không hợp lệ hoặc đang để trống';
            }else{
                $contact->setEmail($email);
            }
            if($phone == '' || !is_numeric($phone) || strlen($phone) != 11 && strlen($phone) != 10 ){
                $error['phone'] = 'Số điện thoại phải là ở dạng số';
            }else{
                $contact->setPhone($phone);
            }
            if(strpos($content, 'script')){
                $error['content'] = 'Chúng tôi chưa hỗ trợ định dạng này';
            }else{
                $contact->setContent($content);
            }
            if($error){
                return new ViewModel(array(
                    'error' => $error,
                ));
            }else{
                $contactMapper->save($contact);
                return new ViewModel(array(
                    'error' => 0,
                    'mes' => 'Cảm ơn <strong>'.$contact->getName().'</strong> đã gửi thông điệp cho chúng tôi, chúng tôi sẽ trả lời bạn trong thời gian sớm nhất'
                ));
            }
        }
    }

}