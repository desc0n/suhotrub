<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Item extends Controller_Base
{
	public function action_show()
	{
		/** @var $contentModel Model_Content */
		$contentModel = Model::factory('Content');

        /** @var $noticeModel Model_Item */
        $noticeModel = Model::factory('Item');

		$id = $this->request->param('id');

		$params = $this->request->query();
		$params['id'] = $id;


		$notice = $noticeModel->getItem($params);
		$noticeModel->setItemView($id);

		$itemData = (!empty($notice) ? $notice[0] : []);

		View::set_global('title', Arr::get($itemData, 'name'));
		View::set_global('rootPage', 'main');

		$template = $contentModel->getBaseTemplate();

		$template->content=View::factory('item')
			->set('itemData', $itemData)
			->set('id', $id)
		;

		$this->response->body($template);
	}
}