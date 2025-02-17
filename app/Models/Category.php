<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'parent_id', 'image', 'is_use', 'is_rec', 'sort'];

    public function getTree(){
        $data = $this -> orderBy('sort', 'DESC') -> get();
        return $this -> _resort($data);
    }

    private function _resort($data, $parent_id=0, $level=0, $isClear=true){
        static $categoryTree = array();

        if($isClear){
            $categoryTree = array();
        }

        foreach($data as $v){
            if($v->parent_id == $parent_id){
                $v->level = $level;
                $categoryTree[] = $v;

                $this -> _resort($data, $v->id, $level+1, false);
            }
        }

        return $categoryTree;
    }

    public function getChildren($id){
        $data = $this -> get();
        return $this -> _children($data, $id);
    }

    private function _children($data, $parent_id=0, $isClear=true){
        static $children = array();

        if($isClear){
            $children = array();
        }

        foreach($data as $v){
            if($v->parent_id == $parent_id){
                $children[] = $v->id;
                $this -> _children($data, $v['id'], false);
            }
        }

        return $children;
    }

    public function getMainCategory(){
        $_data = $this -> select(['id', 'name', 'parent_id', 'is_rec', 'image']) -> where('is_use', 1) -> orderBy('sort', 'DESC') -> orderBy('id', 'ASC') -> get() -> toArray();
        $data = array();

        foreach($_data as $v){
            if($v['parent_id'] == 0){

                $brandData = Brand::where('category_id', $v['id']) -> select(['id', 'name', 'logo']) -> where('is_use', 1) -> orderBy('sort', 'DESC') -> orderBy('id', 'ASC') -> limit(16) -> get() -> toArray();
                $v['brand'] = $brandData;

                foreach($_data as $v1){
                    if($v1['parent_id'] == $v['id']){

                        if($v1['rec'] == 1){
                            $v['recommend'][] = $v1;
                        }

                        foreach($_data as $v2){
                            if($v2['parent_id'] == $v1['id']){

                                if($v2['rec'] == 1){
                                    $v['recommend'][] = $v2;
                                }

                                $v1['children'][] = $v2;
                            }
                        }
                        $v['children'][] = $v1;
                    }
                }
                $data[] = $v;
            }
        }

        return $data;
    }

    public function getParent($id){
        static $parent = array();

        $currentCat = $this -> select(['id', 'name', 'parent_id']) -> where('id', $id) -> first() -> toArray();
        array_unshift($parent, $currentCat);

        if($currentCat['parent_id']){
            $this -> getParent($currentCat['parent_id']);
        }

        return $parent;
    }
}
