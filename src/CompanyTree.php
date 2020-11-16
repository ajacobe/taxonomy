<?php
namespace clio\taxonomy;

class CompanyTree
{
    public $root = [];
    function __construct($employees)
    {
        foreach($employees as $employee) {
            $node = new CompanyNode($employee);
            if(count($this->root) == 0){
                $this->root[] = $node;
            }
            else{
                $this->addNodeRecursively($node, $this->root);
            }
        }
        return $this->root;
    }

    private function addNodeRecursively($node, &$subroot)
    {
        foreach($subroot as $each) {
            if($each->id == $node->parentId){
                $each->children[] = $node;
            }else if(count($each->children) > 0){
                $this->addNodeRecursively($node, $each->children);
            }
        }
    }
}