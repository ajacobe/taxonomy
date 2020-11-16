<?php
namespace clio\taxonomy;

class CompanyNode{
    public $id;
    public $parentId;
    public $name;
    public $attributes = [];
    public $children = [];

    public function __construct($data) {
        $this->id = $data->Id;
        $this->parentId = $data->ParentId;
        $this->name = $data->Name;
        foreach($data as $key => $val){
            if(!in_array($key, ["Id","Name","ParentId"])) {
                $this->attributes[$key] = $val;
            }
        }
        $this->children = [];
        return $this;
    }
}