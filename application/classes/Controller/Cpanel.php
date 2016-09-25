<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cpanel extends Controller {


	private function check_role($role_type = 1)
	{
		if($role_type == 1)
			if(!Auth::instance()->logged_in('admin'))
				HTTP::redirect('/');
		else if ($role_type == 2)
			if(!Auth::instance()->logged_in('manager'))
				HTTP::redirect('/');
	}

	public function action_index()
	{
        if (Auth::instance()->logged_in() && isset($_POST['logout'])) {
            Auth::instance()->logout();
            HTTP::redirect('/');
        }

        if (!Auth::instance()->logged_in() && isset($_POST['login'])) {
            Auth::instance()->login($_POST['username'], $_POST['password'],true);
            HTTP::redirect('/cpanel');
        }
        
        $this->response->body( View::factory('admin_template')->set('admin_content', ''));
	}

	public function action_registration()
	{


		$template = View::factory('admin_template');
        $admin_content = '';

		if (Auth::instance()->logged_in('admin')) {
            $admin_content = View::factory('registration')
                ->set('username', Arr::get($_POST,'username',''))
                ->set('email', Arr::get($_POST,'email',''))
                ->set('error', '')
            ;

            if (isset($_POST['reg'])) {
                if (Arr::get($_POST,'username','')=="") {
                    $error = View::factory('error');
                    $error->zag = "Не указан логин!";
                    $error->mess = " Укажите Ваш логин.";
                    $admin_content->error = $error;
                } elseif (Arr::get($_POST,'email','')=="") {
                    $error = View::factory('error');
                    $error->zag = "Не указана почта!";
                    $error->mess = " Укажите Вашу почту.";
                    $admin_content->error = $error;
                } elseif (Arr::get($_POST,'password','')=="") {
                    $error = View::factory('error');
                    $error->zag = "Не указан пароль!";
                    $error->mess = " Укажите Ваш пароль.";
                    $admin_content->error = $error;
                } elseif (Arr::get($_POST,'password','')!=Arr::get($_POST,'password2','')) {
                    $error = View::factory('error');
                    $error->zag = "Пароли не совпадают!";
                    $error->mess = " Проверьте правильность подтверждения пароля.";
                    $admin_content->error = $error;
                } else {
                    $user = ORM::factory('User');
                    $user->values(array(
                        'username' => $_POST['username'],
                        'email' => $_POST['email'],
                        'password' => $_POST['password'],
                        'password_confirm' => $_POST['password2'],
                    ));

                    $some_error = false;

                    try {
                        $user->save();
                        $user->add("roles",ORM::factory("Role",1));
                    }

                    catch (ORM_Validation_Exception $e) {
                        $some_error = $e->errors('models');
                    }

                    if ($some_error) {
                        $error = View::factory('error');
                        $error->zag = "Ошибка регистрационных данных!";
                        $error->mess = " Проверьте правильность ввода данных.";

                        if (isset($some_error['username'])) {
                            if ($some_error['username']=="models/user.username.unique") {
                                $error->zag = "Такое имя уже есть в базе!";
                                $error->mess = " Придумайте новое.";
                            }
                        } elseif (isset($some_error['email'])) {
                            if ($some_error['email']=="email address must be an email address") {
                                $error->zag = "Некорректный формат почты!";
                                $error->mess = " Проверьте правильность написания почты.";
                            }

                            if ($some_error['email']=="models/user.email.unique") {
                                $error->zag = "Такая почта есть в базе!";
                                $error->mess = " Укажите другую почту.";
                            }
                        }

                        $admin_content->error = $error;
                    }
                }
            }
        }

		$this->response->body($template->set('admin_content', $admin_content));
	}

	public function action_add_item()
	{
        /** @var $adminModel Model_Admin */
        $adminModel = Model::factory('Admin');

        /** @var $itemModel Model_Item */
        $itemModel = Model::factory('Item');

		$template = View::factory("admin_template");
		$admin_content = '';

		if (Auth::instance()->logged_in('admin')){
            $admin_content = View::factory('add_item')
                ->set('get', $this->request->query())
            ;

            if (isset($_POST['additem'])) {
                $id = $adminModel->addItem($this->request->post());

                HTTP::redirect('/admin/control_panel/redact_item?id=' . $id);
            }

            if (isset($_POST['removeproduct'])) {
                $itemModel->deleteItem(['id' => $_POST['removeproduct']]);

                HTTP::redirect($this->request->referrer());
            }
        }

		$this->response->body($template->set('admin_content', $admin_content));
	}

	public function action_redact_item()
	{
        /** @var $adminModel Model_Admin */
        $adminModel = Model::factory('Admin');

        /** @var $itemModel Model_Item */
        $itemModel = Model::factory('Item');

		$template = View::factory("admin_template");
		$admin_content = '';
        
		if (Auth::instance()->logged_in('admin')){
            $item_id = Arr::get($_GET, 'id', 0);
            $removeimg = isset($_POST['removeimg']) ? $_POST['removeimg'] : 0;
            $filename = Arr::get($_FILES, 'imgname', []);
            $files = Arr::get($_FILES, 'filename', []);

            if ($item_id != '' && !empty($filename)) {
                $adminModel->loadItemImg($_FILES, $item_id);

                HTTP::redirect($this->request->referrer());
            }

            if ($item_id != '' && !empty($files)) {
                $itemModel->loadItemFile($_FILES, $item_id);

                HTTP::redirect($this->request->referrer());
            }

            if ($removeimg != 0) {
                $itemModel->removeItemImg($this->request->post());

                HTTP::redirect($this->request->referrer());
            }

            $removeFile = Arr::get($_POST, 'removefile');

            if (!empty($removeFile)) {
                $itemModel->removeItemFile($this->request->post());

                HTTP::redirect($this->request->referrer());
            }

            if (isset($_POST['redactitem'])) {
                $itemModel->setItem($_POST);

                HTTP::redirect($this->request->referrer());
            }

            if (isset($_POST['newItemParam'])) {
                $itemModel->setItemParams($this->request->post());

                HTTP::redirect($this->request->referrer());
            }

            if (isset($_POST['removeProductParam'])) {
                $itemModel->removeItemParams($this->request->post());

                HTTP::redirect($this->request->referrer());
            }

            $item = $itemModel->getItem($this->request->query());
            $categoryItems = (!empty($item) ? $itemModel->getItem(['category_id' => $item[0]['category']]) : []);
            $sortData = [];

            foreach ($categoryItems as $itemsData) {
                $sortData[$itemsData['sort']] = $itemsData['sort'];
            }

            $admin_content = View::factory('admin_redact_item')
                ->set('item_info', (!empty($item) ? $item[0] : []))
                ->set('sortData', $sortData)
                ->set('itemParams', $itemModel->getItemParams($this->request->query()))
                ->set('get', $this->request->query())
                ->set('item_id', $item_id)
            ;
		}
        
		$this->response->body($template->set('admin_content', $admin_content));
	}

	public function action_reviews_moderation()
	{
        /** @var $adminModel Model_Admin */
        $adminModel = Model::factory('Admin');

		$template = View::factory("admin_template");
		$admin_content = '';

		if (Auth::instance()->logged_in('admin')){
            if (isset($_POST['unshowreview'])) {
                $adminModel->setReview([
                    'id' => $_POST['unshowreview'],
                    'status' => 0
                ]);

                HTTP::redirect($this->request->referrer());
            }

            if (isset($_POST['showreview'])) {
                $adminModel->setReview([
                    'id' => $_POST['showreview'],
                    'status' => 1
                ]);

                HTTP::redirect($this->request->referrer());
            }

            $admin_content = View::factory('reviews_moderation')
                ->set('showedReviewsData', $adminModel->findReviews(['status' => 1]))
                ->set('unshowedReviewsData', $adminModel->findReviews(['status' => 0]))
                ->set('get', $this->request->query());
		}

		$this->response->body($template->set('admin_content', $admin_content));
	}

	public function action_redact_main_page()
	{
        /** @var $adminModel Model_Admin */
        $adminModel = Model::factory('Admin');

		/** @var $contentModel Model_Content */
		$contentModel = Model::factory('Content');

		$template = View::factory("admin_template");
		$admin_content = '';

		if (Auth::instance()->logged_in('admin')){
            if (isset($_POST['addhit'])) {
                $adminModel->addHit($this->request->post());

                HTTP::redirect($this->request->referrer());
            }

            if (isset($_POST['removehit'])) {
                $adminModel->removeHit($this->request->post());

                HTTP::redirect($this->request->referrer());
            }

            if (isset($_POST['redactpage'])) {
                $adminModel->setPage($this->request->post());

                HTTP::redirect($this->request->referrer());
            }

            $admin_content = View::factory('admin_redact_main_page')
                ->set('pageData', $contentModel->getPage(['id' => 3]))
                ->set('get', $this->request->query())
            ;
		}

		$this->response->body($template->set('admin_content', $admin_content));
	}

	public function action_redact_catalogs()
	{
        /** @var $adminModel Model_Admin */
        $adminModel = Model::factory('Admin');

		$template = View::factory("admin_template");
		$admin_content = '';

		if (Auth::instance()->logged_in('admin')){
            $filename=Arr::get($_FILES, 'filename', '');

            if (!empty($filename)) {
                $adminModel->loadCatalogs($_FILES);

                HTTP::redirect($this->request->referrer());
            }

            if (isset($_POST['removefile'])) {
                $adminModel->removeCatalogs($this->request->post());

                HTTP::redirect($this->request->referrer());
            }

            $admin_content = View::factory('admin_redact_catalogs')
                ->set('catalogsData', $adminModel->getCatalogsData())
                ->set('get', $this->request->query())
            ;
		}

		$this->response->body($template->set('admin_content', $admin_content));
	}

	public function action_add_category()
	{
        /** @var $adminModel Model_Admin */
        $adminModel = Model::factory('Admin');

		$template = View::factory("admin_template");
		$admin_content = '';

		if (Auth::instance()->logged_in('admin')){
            if (isset($_POST['addgroup'])) {
                $adminModel->addCategory($this->request->post());

                HTTP::redirect($this->request->referrer());
            }

            if (isset($_POST['removegroup'])) {
                $adminModel->removeCategory($this->request->post());
                HTTP::redirect($this->request->referrer());
            }

            $admin_content = View::factory('admin_add_category')
                ->set('get', $this->request->query());
		}

		$this->response->body($template->set('admin_content', $admin_content));
	}

	public function action_redact_page()
	{
        /** @var $adminModel Model_Admin */
        $adminModel = Model::factory('Admin');

		/** @var $contentModel Model_Content */
		$contentModel = Model::factory('Content');

		$template = View::factory("admin_template");
		$admin_content = '';
        
		if (Auth::instance()->logged_in('admin')){
            if (isset($_POST['redactpage'])) {
                $adminModel->setPage($this->request->post());

                HTTP::redirect($this->request->referrer());
            }

            $admin_content = View::factory('admin_redact_page')
                ->set('pages', $contentModel->getPages())
                ->set('pageData', $contentModel->getPage($this->request->query()))
                ->set('get', $this->request->query())
            ;
		}

		$this->response->body($template->set('admin_content', $admin_content));
	}

	public function action_redact_contacts()
	{
        /** @var $adminModel Model_Admin */
        $adminModel = Model::factory('Admin');

		/** @var $contentModel Model_Content */
		$contentModel = Model::factory('Content');

		$template = View::factory("admin_template");
		$admin_content = '';

		if (Auth::instance()->logged_in('admin')){
            if (isset($_POST['redactcontacts'])) {
                $adminModel->setContacts($this->request->post());

                HTTP::redirect($this->request->referrer());
            }

            $admin_content = View::factory('admin_redact_contacts')
                ->set('contacts', $contentModel->findAllContacts())
            ;
		}

		$this->response->body($template->set('admin_content', $admin_content));
	}
}