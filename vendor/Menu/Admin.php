<?php 
namespace Menu;

use \Zend\Navigation\Navigation;

class Admin extends Navigation
{
	public function __construct()
	{
		$this->addPages(array(
			array(
                'icon'  => '<i class="fa fa-shopping-cart"></i>',
				'label'	=> 'Sản phẩm',
				'class' => 'fa fa-dot-circle-o',
				'uri'	=> '/admin/product',
				'resource' => 'admin:product',
				'privilege' => 'index',
                'module' => 'product',

				'pages'	=> array(
                    array(
                        'label'	=> 'Sản phẩm',
                    	'label2'=> 'San pham',
                        'uri'	=> '/admin/product',
                        'resource' => 'admin:product',
                        'privilege' => 'index',
                    	'class'=>'fa fa-list-ul',
                    ),
//                    array(
//                        'label'	=> 'Thêm sản phẩm',
//                        'label2'=> 'them san pham',
//                        'uri'	=> '/admin/product/add',
//                        'resource' => 'admin:product',
//                        'privilege' => 'add',
//                        'class'=>'fa fa-plus-square',
//                    ),
					array(
                        'label'	=> 'Danh mục',
                    	'label2'=> 'Danh muc',
                        'uri'	=> '/admin/product/category',
                        'resource' => 'admin:product',
                        'privilege' => 'index',
						'class'=>'fa fa-list-ul',
                    ),
//                    array(
//                        'label'	=> 'Thương hiệu',
//                        'label2'=> 'thuong hieu',
//                        'uri'	=> '/admin/product/brand',
//                        'resource' => 'admin:product',
//                        'privilege' => 'index',
//                        'class'=>'fa fa-list-ul',
//                    ),
//					array(
//						'label'	=> 'Thêm danh mục',
//						'label2'=> 'them danh muc san pham',
//						'uri'	=> '/admin/product/addcategory',
//						'resource' => 'admin:product',
//						'privilege' => 'add',
//						'class'=>'fa fa-plus-square',
//                    ),
//                    array(
//                        'label'	=> 'Thuộc tính',
//                        'label2'=> 'thuoc tinh',
//                        'uri'	=> '/admin/product/attr',
//                        'resource' => 'admin:product',
//                        'privilege' => 'attr',
//                        'class'=>'fa fa-list-ul',
//                    ),
//                    array(
//                        'label'	=> 'Thêm thuộc tính',
//                        'label2'=> 'them thuoc tinh',
//                        'uri'	=> '/admin/product/addattr',
//                        'resource' => 'admin:product',
//                        'privilege' => 'addattr',
//                        'class'=>'fa fa-plus-square',
//                    ),
//                    array(
//                        'label'	=> 'Đơn hàng',
//                        'label2'=> 'don hang',
//                        'uri'	=> '/admin/product/order',
//                        'resource' => 'admin:product',
//                        'privilege' => 'order',
//                        'class'=>'fa fa-plus-square',
//                    ),
				)
			),
			array(
                'icon'  => '<i class="fa fa-newspaper-o"></i>',
				'label'	=> 'Bài viết',
				'uri'	=> '/admin/article',
				'resource' => 'admin:article',
				'privilege' => 'index',
				'class' => 'fa fa-dot-circle-o',
                'module' => 'article',
                'pages'	=> array(
					array(
						'label'	=> 'Bài viết',
						'label2'=> 'bai viet',
						'uri'	=> '/admin/article',
						'resource' => 'admin:article',
						'privilege' => 'index',
						'class'=>'fa fa-list-ul',
                    ),
//                    array(
//                        'label'	=> 'Thêm bài viết',
//                        'label2'=> 'them bai viet',
//                        'uri'	=> '/admin/article/add',
//                        'resource' => 'admin:article',
//                        'privilege' => 'add',
//                        'class'=>'fa fa-plus-square',
//                    ),
					array(
						'label'	=> 'Danh mục',
						'label2'=> 'Danh muc',
						'uri'	=> '/admin/article/category',
						'resource' => 'admin:articlec',
						'privilege' => 'index',
						'class'=>'fa fa-list-ul'
					),
//					array(
//						'label'	=> 'Thêm danh mục bài viết',
//						'label2'=> 'them danh muc bai viet',
//						'uri'	=> '/admin/article/addcategory',
//						'resource' => 'admin:articlec',
//                        'privilege' => 'add',
//                        'class'=>'fa fa-plus-square'
//					),
				)
			),
//            array(
//                'icon'  => '<i class="fa fa-file-o"></i>',
//                'label'	=> 'Trang',
//                'uri'	=> '/admin/page',
//                'resource' => 'admin:article',
//                'privilege' => 'index',
//                'class' => 'fa fa-dot-circle-o',
//                'module' => 'page',
//                'pages'	=> array(
//                    array(
//                        'label'	=> 'Trang',
//                        'label2'=> 'trang',
//                        'uri'	=> '/admin/page',
//                        'resource' => 'admin:page',
//                        'privilege' => 'index',
//                        'class'=>'fa fa-list-ul',
//                    ),
//                )
//            ),
//            array(
//                'icon'  => '<i class="fa fa-file-photo-o" aria-hidden="true"></i>',
//                'label'	=> 'Banner',
//                'class' => 'fa fa-dot-circle-o',
//                'uri'	=> '/admin/media/banner',
//                'resource' => 'admin:media',
//                'privilege' => 'index',
//                'module' => 'media',
//                'pages'	=> array(
//                    array(
//                        'label'	=> 'Banner',
//                        'label2'=> 'danh sach san pham',
//                        'uri'	=> '/admin/media/banner',
//                        'resource' => 'admin:media',
//                        'privilege' => 'index',
//                        'class'=>'fa fa-list-ul'
//                    ),
//                )
//            ),
//            array(
//                'icon'  => '<i class="fa fa-cogs" aria-hidden="true"></i>',
//                'label'	=> 'Cài đặt',
//                'class' => 'fa fa-dot-circle-o',
//                'uri'	=> '/admin/setup',
//                'resource' => 'admin:media',
//                'privilege' => 'index',
//                'module' => 'setup',
//                'pages'	=> array(
//                    array(
//                        'label'	=> 'Menu',
//                        'label2'=> 'Menu',
//                        'uri'	=> '/admin/setup/menu',
//                        'resource' => 'admin:media',
//                        'privilege' => 'index',
//                        'class'=>'fa fa-list-ul'
//                    ),
//                    array(
//                        'label'	=> 'Tùy chỉnh',
//                        'label2'=> 'tuy chinh website',
//                        'uri'	=> '/admin/setup',
//                        'resource' => 'admin:media',
//                        'privilege' => 'index',
//                        'class'=>'fa fa-list-ul'
//                    ),
//                )
//            ),
//			array(
//                'icon'  => '<i class="icon-bookmark-empty"></i>',
//				'label'	=> 'Doanh nghiệp',
//				'uri'	=> '/admin/store',
//				'resource' => 'admin:store',
//				'privilege' => 'index',
//				'class' => 'fa fa-dot-circle-o',
//                'module' => 'store',
//                'pages'	=> array(
//					array(
//						'label'	=> 'Danh sách doanh nghiệp',
//						'label2'=> 'danh sach doanh nghiep',
//						'uri'	=> '/admin/store',
//						'resource' => 'admin:store',
//						'privilege' => 'index',
//						'class'=>'fa fa-list-ul'
//					),
//					array(
//						'label'	=> 'Thêm doanh nghiệp',
//						'label2'=> 'them doanh nghiep',
//						'uri'	=> '/admin/store/add',
//						'resource' => 'admin:store',
//						'privilege' => 'add',
//						'class'=>'fa fa-plus-square'
//					),
//				)
//			),
//			array(
//                'icon'  => '<i class="icon-bookmark-empty"></i>',
//				'label'	=> 'Người dùng',
//				'uri'	=> '/admin/user',
//				'resource' => 'user:user',
//				'privilege' => 'index',
//				'class' => 'fa fa-dot-circle-o',
//                'module' => 'user',
//                'pages'	=> array(
//					array(
//						'label'	=> 'Danh sách người dùng',
//						'label2'=> 'danh sach nguoi dung',
//						'uri'	=> '/user',
//						'resource' => 'user:user',
//						'privilege' => 'index',
//						'class'=>'fa fa-list-ul'
//					),
//					array(
//						'label'	=> 'Thêm người dùng',
//						'label2'=> 'them nguoi dung',
//						'uri'	=> '/user/user/add',
//						'resource' => 'user:user',
//						'privilege' => 'add',
//						'class'=>'fa fa-plus-square'
//					),
//				)
//			),

		));
	}
}
