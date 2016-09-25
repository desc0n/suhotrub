<?php

/**
 * Class Model_Content
 */
class Model_Content extends Kohana_Model
{
    public function getBaseTemplate()
    {
        return View::factory('template')
            ->set('menu', $this->getMenu())
        ;
    }
    
    /**
     * @param null|int $parentId
     * @param null|int $id
     * 
     * @return array
     */
    public function getMenu($parentId = null, $id = null)
    {
        if ($parentId !== null && $id === null) {
            return DB::select(
                    'm.*' , 
                    ['p.title', 'name'],
                    'p.slug'
                )
                ->from(['pages__menu', 'm'])
                ->join(['pages__pages', 'p'])
                ->on('p.id', '=', 'm.page_id')
                ->where('m.parent_id', '=', $parentId)
                ->and_where('m.status_id', '=', 1)
                ->execute()
                ->as_array()
            ;
        } elseif ($id !== null) {
            return DB::select(
                    'm.*' ,
                    ['p.title', 'name'],
                    'p.slug'
                )
                ->from(['pages__menu', 'm'])
                ->join(['pages__pages', 'p'])
                ->on('p.id', '=', 'm.page_id')
                ->where('m.id', '=', $id)
                ->execute()
                ->as_array()
            ;
        } else {
            return DB::select(
                    'm.*' ,
                    ['p.title', 'name'],
                    'p.slug'
                )
                ->from(['pages__menu', 'm'])
                ->join(['pages__pages', 'p'])
                ->on('p.id', '=', 'm.page_id')
                ->where('m.parent_id', 'IS', null)
                ->and_where('m.status_id', '=', 1)
                ->execute()
                ->as_array()
            ;
        }
    }

    /**
     * @param null|int $cid
     * @param null|int $id
     * 
     * @return array
     */
    public function getCategory($cid = null, $id = null)
    {
        if ($cid !== null && $id === null) {
            return DB::select()
                ->from('category')
                ->where('parent_id', '=', $cid)
                ->execute()
                ->as_array()
            ;
        } elseif ($id !== null) {
            return DB::select()
                ->from('category')
                ->where('id', '=', $id)
                ->limit(1)
                ->execute()
                ->current()
            ;
        } else {
            return DB::select()
                ->from('category')
                ->where('parent_id', 'IS', NULL)
                ->execute()
                ->as_array()
            ;
        }
    }

    /**
     * @return array
     */
    public function getPages()
    {
        return DB::select('p.*')
            ->from(['pages__pages', 'p'])
            ->join(['pages__menu', 'm'])
            ->on('m.page_id', '=', 'p.id')
            ->where('m.status_id', '=', 1)
            ->execute()
            ->as_array()
        ;
    }

    public function getPage($params = [])
    {
        $id = Arr::get($params, 'id', 0);

        return DB::select()
            ->from('pages__pages')
            ->where('id', '=', $id)
            ->limit(1)
            ->execute()
            ->current()
        ;
    }

    /**
     * @param string $slug
     * 
     * @return false|array
     */
    public function findPageBySlug($slug = '')
    {
        return DB::select()
            ->from('pages__pages')
            ->where('slug', '=', $slug)
            ->limit(1)
            ->execute()
            ->current()
        ;
    }

    /**
     * @param string $slug
     *
     * @return false|array
     */
    public function findMenuByPageSlug($slug = '')
    {
        return DB::select('p.*')
            ->from(['pages__pages', 'p'])
            ->join(['pages__menu', 'm'])
            ->on('p.id', '=', 'm.page_id')
            ->where('p.slug', '=', $slug)
            ->limit(1)
            ->execute()
            ->current()
        ;
    }
}