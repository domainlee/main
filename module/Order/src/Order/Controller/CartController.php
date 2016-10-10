<?php
namespace Order\Controller;

use Admin\Model\Order;
use Product\Model\WishList;
use \stdClass;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Home\Model\DateBase;

class CartController extends AbstractActionController{

    public function indexAction(){

        /* @var $cartService \Order\Service\Cart */
        $cartService = $this->getServiceLocator()->get('Order\Service\Cart');
        $products = $cartService->getProducts();

        $request = $this->getRequest();
        $viewModel = new ViewModel();

//        print_r($products);die;
        if ($request->getPost('template')) {
            $viewModel->setTemplate($request->getPost('template'));
            $viewModel->setTerminal($request->getPost('terminal', false));
        }

//        return new ViewModel(array(
//            'products'    => $products,
////            'cities'      => $cities,
//            'cartService' => $cartService
//        ));

        $viewModel->setVariable('products', $products);
        $viewModel->setVariable('cartService', $cartService);
        return $viewModel;

    }

    public function addAction(){
        $request = $this->getRequest();
        $products = $request->getPost('products');

        if (!is_array($products) || !(count($products))) {
            return new JsonModel(['code' => 0, 'message' => 'Error']);
        }

        $prods = [];
        foreach($products as $product){
            $orderProduct = new \Order\Model\Product();
            $orderProduct->setProductId((int)$product['id']);
            $orderProduct->setQuantity((int)$product['quantity']);
            if(isset($product['data-size'])){
                $orderProduct->addOption('attrSize',(int)$product['data-size']);
            }
            if(isset($product['data-color'])){
                $orderProduct->addOption('attrColor',(int)$product['data-color']);
            }
            $product = new \Product\Model\Product();
            $product->setId($orderProduct->getProductId());
//            $orderProduct->setProductParentId($product->getId());
            $prods[] = $orderProduct;
        }
        $cartService = $this->getServiceLocator()->get('Order\Service\Cart');
        $totalProduct = $cartService->add($prods, (int)$request->getPost('mode'));
        $data = new stdClass();
//        $data->totalQuantities = $cartService->getTotalQuantities();
        $data->totalProducts = $totalProduct;
//        $data->totalMoney = $cartService->getTotalMoney();
        $data->products = $prods;

        return new JsonModel([
            'status' => 1,
            'data'   => $data
        ]);

    }

    public function removeAction(){
        $request = $this->getRequest();
        $dataId = $request->getPost('dataId');
        $dataColor = $request->getPost('dataColor');
        $dataSize = $request->getPost('dataSize');

        if(!is_numeric($dataId)){
            return new JsonModel(array(
                'code' => '0',
                'message' => 'Không tìm thấy sản phẩm này',
            ));
        }
        $wishlistMapper = $this->getServiceLocator()->get('Product\Model\WishlistMapper');
        $cartService = $this->getServiceLocator()->get('Order\Service\Cart');

        $wishList = new WishList();
        $wishList->setProductStoreId($dataId);
        if($dataColor){
            $wishList->setProductColor($dataColor);
        }
        if($dataSize){
            $wishList->setProductSize($dataSize);
        }
        if($this->user()->hasIdentity()){
            $wishList->setUserId($this->user()->getUser()->getId());
        }else{
            $wishList->setUserCookie($cartService->getCookie());
        }
        if(!$wishlistMapper->get($wishList)){
            return new JsonModel(array(
                'code' => '0',
                'message' => 'Không tìm thấy sản phẩm này',
            ));
        }
        $cartService = $this->getServiceLocator()->get('Order\Service\Cart');
        if ($wishList->getProductStoreId()) {
            $cartService->remove(['productId' => $wishList->getProductStoreId(), 'dataColor' => ($dataColor ? $dataColor:null), 'dataSize' => ($dataSize ? $dataSize:null)]);
        } else {
            $cartService->remove();
        }
        return new JsonModel(array(
           'code' => '1',
           'message' => 'Da xoa',
        ));
    }

    public function changeAction()
    {
        $cartService = $this->getServiceLocator()->get('Order\Service\Cart');
        $request = $this->getRequest();
        $dataId = $request->getPost('dataId');
        $dataColor = $request->getPost('dataColor');
        $dataSize = $request->getPost('dataSize');
        $dataQuantity = $request->getPost('dataQuantity');

        $wishlistMapper = $this->getServiceLocator()->get('Product\Model\WishlistMapper');
        $wishList = new WishList();
        $wishList->setProductStoreId($dataId);
        $wishList->setQuantity($dataQuantity);
        if($dataColor){
            $wishList->setProductColor($dataColor);
        }
        if($dataSize){
            $wishList->setProductSize($dataSize);
        }
        if($this->user()->hasIdentity()){
            $wishList->setUserId($this->user()->getUser()->getId());
        }else{
            $wishList->setUserCookie($cartService->getCookie());
        }
        if(!$wishlistMapper->get($wishList)){
            return new JsonModel(array(
                'code' => '0',
                'message' => 'Sản phẩm này không tồn tại',
            ));
        }
        $wishList->setQuantity($dataQuantity);
        $wishList->addOption('updateQuantity',true);
        $wishlistMapper->save($wishList);

        return new JsonModel(array(
            'code' => '1',
            'message' => 'Đã thay đổi',
        ));
    }

    public function checkoutAction()
    {
        $cartService = $this->getServiceLocator()->get('Order\Service\Cart');
        if(count($cartService->getProducts()) == 0){
            $this->redirect()->toUrl('/');
        }
        $error = [];
        if($this->getRequest()->isPost()){
            $validator = new \Zend\Validator\EmailAddress();

            $a = $this->getRequest()->getPost()->toArray();
            $fullName  = $a['fullName'];
            $email = $a['email'];
            $phone = $a['phone'];
            $content = $a['content'];

            $order = new \Order\Model\Order();
            $orderMapper = $this->getServiceLocator()->get('Order\Model\OrderMapper');
            $productMapper = $this->getServiceLocator()->get('Order\Model\ProductMapper');

            $storeId = $this->getServiceLocator()->get('Store\Service\Store')->getStoreId();

            if(strpos($fullName, 'script')){
                $error['fullName'] = 'Chúng tôi chưa hỗ trợ định dạng này';
            }else{
                $order->setCustomerName($fullName);
            }
            if(!$validator->isValid($email) || $email == ''){
                $error['email'] = 'Email không hợp lệ hoặc đang để trống';
            }else{
                $order->setCustomerEmail($email);
            }
            if($phone == '' || !is_numeric($phone) || strlen($phone) != 11 && strlen($phone) != 10 ){
                $error['phone'] = 'Số điện thoại phải là ở dạng số';
            }else{
                $order->setCustomerMobile($phone);
            }
            if(strpos($content, 'script')){
                $error['content'] = 'Chúng tôi chưa hỗ trợ định dạng này';
            }else{
                $order->setDescription($content);
                $order->setCustomerAddress($content);
            }
            if($error){
                return new ViewModel(array(
                    'error' => $error,
                ));
            }else{
                $order->setShippingType(\Order\Model\Order::STATUS_INACTIVE);
                $order->setCreatedDateTime(DateBase::getCurrentDateTime());
                $order->setCreatedbyId($this->user()->getUser() ? $this->user()->getUser()->getId():null);
                $order->setStoreId($storeId);
                $orderMapper->save($order);
                $products = $cartService->getProducts();
                foreach($products as $p){
                    $product = new \Order\Model\Product();
                    $product->setOrderId($order->getId());
                    $product->setProductId($p->getId());
                    $product->setStoreId($storeId);
                    $product->setProductPrice($p->getPriceOld() ? $p->getPriceOld() : $p->getPrice());
                    $product->setProductColor(isset($p->getOptions()['dataColor']) ? $p->getOptions()['dataColor']->getId():null);
                    $product->setProductSize(isset($p->getOptions()['dataSize']) ? $p->getOptions()['dataSize']->getId():null);
                    $product->setQuantity($p->getQuantity());
                    $productMapper->save($product);
                    $cartService->remove(['productId' => $p->getId(), 'dataColor' => isset($p->getOptions()['dataColor']) ? $p->getOptions()['dataColor']->getId():null, 'dataSize' => isset($p->getOptions()['dataSize']) ? $p->getOptions()['dataSize']->getId():null]);
                }
//                $this->redirect()->toUrl('/cart/success/'.$order->getId());
                $this->redirect()->toUrl('/cart/success');
            }
        }
    }

    public function successAction()
    {
//        $id = $this->getEvent()->getRouteMatch()->getParam('id');
//        echo $id;die;
//        $cartService = $this->getServiceLocator()->get('Order\Service\Cart');
//        if(count($cartService->getProducts()) == 0){
//            $this->redirect()->toUrl('/');
//        }
    }
}
