<?php
/**
 * @category    Shop99 library
 * @copyright   http://shop99.vn
 * @license     http://shop99.vn/license
 */

namespace Order\Service;

use Product\Model\WishList;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Cart implements ServiceLocatorAwareInterface
{

    const CART_NAME = 'cart_name';

    const MODE_ADD = 1;
    const MODE_UPDATE = 2;

    const CUSTOMER_INFO = 'CIF';
    const VERIFY_INFO = 'VIF';

    /**
     * @var array
     */
    protected $products;

    /**
     * @var string
     */
    protected $cookie;

    /**
     * @var \User\Model\User
     */
    protected $user;

    /**
     * @var int
     */
    protected $domainId;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param string $cookie
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * @return string
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * @param int $domainId
     */
    public function setDomainId($domainId)
    {
        $this->domainId = $domainId;
    }

    /**
     * @return int
     */
    public function getDomainId()
    {
        return $this->domainId;
    }

    /**
     * @param array $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param \User\Model\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \User\Model\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function __construct($serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
//
        /* @var $templateService \Website\Service\Template */
//        $templateService = $serviceLocator->get('Website\Service\Template');
//        $this->setDomainId($templateService->getDomain()->getId());

        /* @var \Product\Model\WishlistMapper $wishlistMapper */
        $wishlistMapper = $serviceLocator->get('Product\Model\WishlistMapper');
        $wishlist = new WishList();
        $wishlist->setType($wishlist::TYPE_CART);
        $wishlist->setDomainId($this->getDomainId());
        /* @var \User\Service\User $userService */
        $userService = $serviceLocator->get('User\Service\User');
        if ($userService->hasIdentity()) {
            $this->setUser($userService->getUser());
            $wishlist->setUserId($this->getUser()->getId());
            if (isset($_COOKIE[self::CART_NAME]) && $_COOKIE[self::CART_NAME]) {
                $wishlist->setUserCookie($_COOKIE[self::CART_NAME]);
                $wishlistMapper->synchronizeCart($wishlist);
                $this->clearCookie();
            }
        } else {
            if (!isset($_COOKIE[self::CART_NAME]) || !$_COOKIE[self::CART_NAME]) {
                $cookie = md5(date("Y-m-d H:i:s") . rand(1000, 9999));
                setcookie(self::CART_NAME, $cookie, time() + 86400, "/"); /* expire in 1 day */
                $this->setCookie($cookie);
            } else {
                $this->setCookie($_COOKIE[self::CART_NAME]);
            }
        }
        $wishlist->setUserCookie($this->getCookie());
        $productMapper = $serviceLocator->get('Product\Model\ProductMapper');
        $attrMapper = $serviceLocator->get('Home\Model\ProductAttrMapper');
        $products = [];
        $wishlists = $wishlistMapper->search($wishlist);
//        print_r($wishlists);die;

        if (count($wishlists)) {
            foreach ($wishlists as $w) {
                $product = new \Product\Model\Product();
                $product->setId($w->getProductStoreId());
                $results = $productMapper->get($product);
                if($w->getProductSize() || $w->getProductSize() != 0){
                    $attr = new \Home\Model\ProductAttr();
                    $attr->setId($w->getProductSize());
                    if($attrMapper->get($attr)){
                        $product->addOption('dataSize', $attr);
                    }
                }
                if($w->getProductColor() || $w->getProductColor() != 0){
                    $attr = new \Home\Model\ProductAttr();
                    $attr->setId($w->getProductColor());
                    if($attrMapper->get($attr)){
                        $product->addOption('dataColor', $attr);
                    }
                }
                if($product->getQuantity()){
                    $product->addOption('quantity', $product->getQuantity());
                }
                $product->setQuantity($w->getQuantity());
                if ($results) {
                    $products[$w->getId()] = $product;
                }
            }
        }
        $this->setProducts($products);
    }

    /**
     * @param array $products
     * @param int $mode
     * @return int
     */
    public function add($products, $mode = self::MODE_ADD)
    {
        $totalItems = count($this->getProducts());
        if (count($products) > 0) {
            /* @var \Product\Model\WishlistMapper $wishlistMapper */
            $wishlistMapper = $this->getServiceLocator()->get('Product\Model\WishlistMapper');
            foreach ($products as $p) {
                /* @var \Order\Model\Product $p */
                $wistlist = new WishList();
                $wistlist->setType($wistlist::TYPE_CART);
                $wistlist->setDomainId(1);
                $wistlist->setUserId(1);
                $wistlist->setProductStoreId($p->getProductId());

                if(isset($p->getOptions()['attrSize'])){
                    $wistlist->setProductSize($p->getOptions()['attrSize']);
                }
                if(isset($p->getOptions()['attrColor'])){
                    $wistlist->setProductColor($p->getOptions()['attrColor']);
                }
//                $wistlist->addOption('mode', $mode);
                if ($this->getUser()) {
                    $wistlist->setUserId($this->getUser()->getId());
                } else {
                    $wistlist->setUserCookie($this->getCookie());
                }
                if ($p->getQuantity() > 0) {
                    $wistlist->setQuantity($p->getQuantity());
                } else {
                    $wistlist->setQuantity(1);
                }
                $totalItems += $wishlistMapper->save($wistlist);
            }
        }
        return $totalItems;

    }

    /**
     * @param null|int $productId
     * @return int
     */
    public function remove($productId = null)
    {
        /* @var \Product\Model\WishlistMapper $wishlistMapper */
        $wishlistMapper = $this->getServiceLocator()->get('Product\Model\WishlistMapper');
        $wishlist = new WishList();
        $wishlist->setType($wishlist::TYPE_CART);
        $wishlist->setDomainId($this->getDomainId());
        if ($this->getUser()) {
            $wishlist->setUserId($this->getUser()->getId());
        } else {
            $wishlist->setUserCookie($this->getCookie());
        }
        if (isset($productId['productId'])) {
            $wishlist->setProductStoreId($productId['productId']);
            if(isset($productId['dataSize'])){
                $wishlist->setProductSize($productId['dataSize']);
            }
            if(isset($productId['dataColor'])){
                $wishlist->setProductColor($productId['dataColor']);
            }
        } else {
            setcookie(self::CUSTOMER_INFO, '', time() - 3600, '/');
        }

        return $wishlistMapper->remove($wishlist);
    }

    public function clearCookie()
    {
        setcookie(self::CART_NAME, '', time() - 3600, '/');
        $this->setCookie(null);
    }

    /**
     * @return int
     */
    public function getTotalProducts()
    {
        return count($this->getProducts());
    }

    /**
     * @return int
     */
    public function getTotalQuantities()
    {
        $qtt = 0;
        foreach ($this->getProducts() as $p) {
            /* @var $p \Order\Model\Product */
            $qtt += $p->getQuantity();
        }
        return $qtt;
    }

    /**
     * type = 2 return money discount
     * @param int $type
     * @return int
     */
    public function getTotalMoney($type = 1)
    {
        $totalMoney = 0;
        $moneyDiscount = 0;
        foreach ($this->getProducts() as $product) {
            /* @var $product \Product\Model\Store */
//            $moneyDiscount += $product->getQuantity() * $product->getMoneyDiscount();
            $totalMoney += $product->getQuantity() * $product->getPrice();
        }
        if ($type == 2) {
            return $moneyDiscount;
        } elseif ($type == 3) {
            return $totalMoney;
        }
        return $totalMoney - $moneyDiscount;
    }

    /**
     * @return int
     */
//    public function getWeightProduct()
//    {
//        $weight = 0;
//        foreach ($this->getProducts() as $product) {
//            /* @var $product \Product\Model\Store */
//            $weight += (int)$product->getQuantity() * (int)$product->getShippingWeight();
//        }
//        return $weight;
//    }

    /**
     * @return array
     */
//    public function productToShipFee()
//    {
//        $productShipFee = [];
//        foreach ($this->getProducts() as $product) {
//            /* @var $product \Product\Model\Store */
//            $productShipFee[$product->getStoreId()]['products'][$product->getId()] = $product->getQuantity();
//        }
//        return $productShipFee;
//    }

}