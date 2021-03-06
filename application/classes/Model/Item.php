<?php

/**
 * Class Model_Item
 */
class Model_Item extends Kohana_Model
{
	const NOTICES_MARKET_LIMIT = 12;

	public function __construct() {
	    DB::query(Database::UPDATE,"SET time_zone = '+10:00'")->execute();
    }

	/**
	 * @param array $params
	 *
	 * @return array
	 */
	public function getItem($params = [])
	{
		$name = Arr::get($params, 'name');
		$page = Arr::get($params, 'page', 1);

		$rowLimit = Arr::get($params, 'limit', 0);
		$startLimit = ($page - 1) * $rowLimit;
		$limit = empty($rowLimit) ? '' : "limit $startLimit, $rowLimit";
		
		$price = Arr::get($params, 'price', 0);
		$priceSql = empty($price) ? '' : ' and `n`.`price` <= :price ';
		$priceCountSql = empty($price) ? '' : ' and `nt`.`price` <= :price ';
		$names =  !empty($name) ? explode(' ', $name) : [];
		$nameSql = '';
		$nameCountSql = '';
		
		foreach ($names as $name) {
			$nameSql .= " and `n`.`name` like '%$name%' ";
			$nameCountSql .= " and `nt`.`name` like '%$name%' ";
		}
		
		$sort = Arr::get($params, 'sort', 'sort');
		$order = Arr::get($params, 'order', 'asc');
		
		$id = Arr::get($params, 'id', 0);
		$categoryId = Arr::get($params, 'category_id', 0);
		
		if (!empty($id)) {
			$sql = "select `n`.*,
            (select `c`.`name` from `category` `c` where `c`.`id` = `n`.`category`) as `category_name`
            from `items__items` `n`
            where `n`.`id` = :id
            and `n`.`status_id` = 1
            LIMIT 0,1";
		} else if (!empty($categoryId)) {
			$sql = "select `n`.*,
			(select ceil(count(`nt`.`id`) / $rowLimit) from `items__items` `nt` where `nt`.`category` = :category_id and `nt`.`status_id` = 1 $priceCountSql $nameCountSql) as `page_count`,
			(select `c`.`name` from `category` `c` where `c`.`id` = `n`.`category`) as `category_name`
			from `items__items` `n`
			where (
			    `n`.`category` = :category_id
			    or `n`.`category` in (select `c`.`id` from `category` `c` where `c`.`parent_id` = :category_id)
            )
			and `n`.`status_id` = 1
			$priceSql
			$nameSql
			order by `$sort` $order
			$limit";
		} else {
			$sql = "select `n`.*,
			(select ceil(count(`nt`.`id`) / $rowLimit) from `items__items` `nt` where `nt`.`status_id` = 1 $priceCountSql $nameCountSql) as `page_count`,
			(select `c`.`name` from `category` `c` where `c`.`id` = `n`.`category`) as `category_name`
			from `items__items` `n`
			where `n`.`status_id` = 1
			$priceSql
			$nameSql
			order by `$sort` $order
			$limit";
		}

		$itemData = [];
		$i = 0;

		$res = DB::query(Database::SELECT, $sql)
			->parameters([
				':id' => $id,
				':category_id' => $categoryId,
				':price' => $price
			])
			->execute()
			->as_array()
		;

		foreach ($res as $row) {
			$itemData[$i] = $row;
			$itemData[$i]['imgs'] = $this->getItemImg($row);
			$itemData[$i]['files'] = $this->getItemFile($row);
			$i++;
		}

		return $itemData;
	}

	public function setItem($params = [])
	{
		$sql = "update `items__items`
        set `name` = :name,
		`price` = :price,
		`description` = :description,
		`short_description` = :short_description,
		`status_id` = 1,
		`sort` = :sort
		where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', Arr::get($params,'redactitem'))
			->param(':name', Arr::get($params,'name',''))
			->param(':price', Arr::get($params,'price',''))
			->param(':description', Arr::get($params,'description',''))
			->param(':short_description', Arr::get($params,'short_description',''))
			->param(':sort', Arr::get($params, 'sort', 1))
			->execute();
	}

	public function setItemParams($params = [])
	{
		$sql = "insert into `items_params`
        (`item_id`, `name`, `value`, `status_id`)
        values (:item_id, :name, :value, 1)";
		DB::query(Database::UPDATE,$sql)
			->param(':item_id', Arr::get($params,'newItemParam'))
			->param(':name', Arr::get($params,'newParamsName',''))
			->param(':value', Arr::get($params,'newParamsValue',''))
			->execute();
	}

	public function getItemParams($params = [])
	{
		$sql = "select * from `items_params` where `item_id` = :id and `status_id` = 1";
		return DB::query(Database::SELECT, $sql)
			->param(':id', Arr::get($params, 'id', 0))
			->execute()
			->as_array();
	}


    public function removeItemParams($params = [])
    {
        $sql = "update `items_params` set `status_id` = 0 where `id` = :id";
        DB::query(Database::UPDATE,$sql)
            ->param(':id', Arr::get($params,'removeProductParam',0))
            ->execute();
    }

    public function getItemImg($params = [])
	{
		$sql = "select * from `items_imgs` where `item_id` = :id and `status_id` = 1";
		return DB::query(Database::SELECT, $sql)
			->param(':id', Arr::get($params, 'id', 0))
			->execute()
			->as_array();
	}

	public function removeItemImg($params = [])
	{
		$sql = "update `items_imgs` set `status_id` = 0 where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', Arr::get($params,'removeimg',0))
			->execute();
	}

	public function deleteItem($params)
	{
		$sql = "update `items__items` set `status_id` = 0 where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', Arr::get($params,'id',0))
			->execute();
	}

	public function getItemFile($params = [])
	{
		$sql = "select * from `items__files` where `item_id` = :id and `status_id` = 1";
		return DB::query(Database::SELECT, $sql)
			->param(':id', Arr::get($params, 'id', 0))
			->execute()
			->as_array();
	}


    public function loadItemFile($filesGlobal, $item_id)
    {
        $filesData = [];

        foreach ($filesGlobal['filename']['name'] as $key => $data) {
            $filesData[$key]['name'] = $filesGlobal['filename']['name'][$key];
            $filesData[$key]['type'] = $filesGlobal['filename']['type'][$key];
            $filesData[$key]['tmp_name'] = $filesGlobal['filename']['tmp_name'][$key];
            $filesData[$key]['error'] = $filesGlobal['filename']['error'][$key];
            $filesData[$key]['size'] = $filesGlobal['filename']['size'][$key];
        }

        foreach ($filesData as $files) {
            $sql = "insert into `items__files` (`item_id`) values (:id)";
            $res = DB::query(Database::INSERT,$sql)
                ->param(':id', $item_id)
                ->execute();

            $new_id = $res[0];
            $imageName = Arr::get($files,'name','');
            //$imageName = preg_replace("/[^0-9a-z.]+/i", "0", Arr::get($files,'name',''));
            $file_name = 'public/files/'.$new_id.'_'.$imageName;
            if (copy($files['tmp_name'], $file_name))	{
                $sql = "update `items__files` set `src` = :src,`status_id` = 1 where `id` = :id";
                DB::query(Database::UPDATE,$sql)
                    ->param(':id', $new_id)
                    ->param(':src', $new_id.'_'.$imageName)
                    ->execute();
            }
        }
    }

	public function removeItemFile($params = [])
	{
		$sql = "update `items__files` set `status_id` = 0 where `id` = :id";
		DB::query(Database::UPDATE,$sql)
			->param(':id', Arr::get($params,'removefile',0))
			->execute();
	}

	public function findLastSeeItems()
	{
		$data = [];

		$views = DB::select()
			->from('items__views')
			->limit(4)
			->order_by('id', 'DESC')
			->group_by('item_id')
			->execute()
			->as_array()
		;

		foreach ($views as $view) {
			$itemData = $this->getItem(['id' =>$view['item_id']]);
			$data[] = Arr::get($itemData, 0, []);
		}

		return $data;
	}
	
	public function setItemView($id)
	{
		$ip = Arr::get($_SERVER, 'REMOTE_ADDR', '');

		$lastView = DB::select()
			->from('items__views')
			->limit(1)
			->order_by('id', 'DESC')
			->execute()
			->current()
		;
		
		$lastViewIp = Arr::get($lastView, 'ip', '');
		$lastViewItemId = Arr::get($lastView, 'item_id', '');

		if ($lastViewIp !== $ip || (int)$lastViewItemId !== (int)$id) {
			DB::insert('items__views', ['item_id', 'ip', 'date'])
				->values([$id, $ip, DB::expr('NOW()')])
				->execute()
			;
		}
	}
}
?>
