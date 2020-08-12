<?php


namespace App\Filters;


use Illuminate\Http\Request;

abstract class Filters
{
    protected $request;
    protected $filters=[];
    protected $builder;

    /**
     * Filters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $builder
     */
    public function apply($builder){
        $this->builder = $builder;
        foreach ($this->getFilters() as $filter =>$value){
            if(method_exists($this , $filter)){
                $this->$filter($value);
            }
        }
        return $this->builder;
    }

    /**
     * @return mixed
     */
    public function getFilters(){
       $only= array_intersect(array_keys($this->request->all()) ,$this->filters );
       return $this->request->only($only);
    }
}
