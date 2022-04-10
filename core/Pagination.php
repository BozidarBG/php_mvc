<?php

namespace App\core;
use App\Core\Request;

class Pagination
{
    public $per_page=10;
    const MAX_PER_PAGE=100;
    public $previous_page;
    public $current_page;
    public $next_page;
    public $last_page; //also total number of pages
    public $queryArray;
    public $total_records;
    public $offset=0;
    public $links;
    const PREVIOUS_PAGE="Prev Page";
    const NEXT_PAGE="Next Page";
    const FIRST_PAGE="First Page";
    const LAST_PAGE="Last Page";


    public function __construct($total_records, $per_page){
        $this->total_records=$total_records;
        $this->queryArray=Request::getParams();
        $this->per_page=array_key_exists('per_page', $this->queryArray) && $this->queryArray['per_page'] < self::MAX_PER_PAGE ? (int) $this->queryArray['per_page'] : $per_page;
        $this->last_page=ceil($this->total_records / $this->per_page);

        if(isset($this->queryArray['page'])){
            if($this->queryArray['page'] > $this->last_page){
                $this->current_page=$this->last_page;
            }
            elseif($this->queryArray['page'] < 1){
                $this->current_page=1;
            }else{
                $this->current_page=$this->queryArray['page'];
            }
        }else{
            $this->current_page=1;
        }
        $this->next_page=$this->current_page+1 <= $this->last_page ? $this->current_page+1 : $this->last_page;
        $this->previous_page=$this->current_page <= 2 ? 1 : $this->current_page-1;

        $this->offset=($this->current_page-1)*$this->per_page;
        $this->generateAllLinks('a', 'paginate', 'active_paginate');
        return $this;

    }


    //
    public function showPaginationHtml(string $tag, string $classList, string $link, string $value){

        return "<{$tag} class=\"{$classList}\" href=\"{$link}\">{$value}</{$tag}>";
    }

    public function shouldBeActive($key, $value,$classList, $activeName, $notActiveKeys){
        if(!in_array($key, $notActiveKeys)){
            return $this->isActive($classList, $activeName, $value);
        }else{
            return $classList;
        }
    }

    public function isActive($classList, $activeName, $value){
        if(isset($_GET['page'])){
            $page=(int) $_GET['page'];
        }else{
            $page=1;
        }
        $classList=$page==$value ? $classList.' '.$activeName : $classList;
        return $classList;
    }

    //take query string. change page number for required link. return query
    public function generateLink(int|string $page){
        $root_string=$_SERVER['REDIRECT_URL'].'?';
        $query_arr=Request::getParams();
        $query_arr['page']=$page;
        $new_query_string="";
        foreach($query_arr as $key=>$value){
            $new_query_string .="{$key}={$value}&";
        }
        return $root_string.rtrim($new_query_string, '&');
    }

    public function generateSomeLinks(array $arrayOfLinks){

    }

    public function generateAllLinks(string $tag, string $classList, string $activeName){
        $notActiveKeys=[self::FIRST_PAGE, self::LAST_PAGE, self::NEXT_PAGE, self::PREVIOUS_PAGE];
        $link_names=[self::FIRST_PAGE=>1, self::PREVIOUS_PAGE=>$this->previous_page, [$this->last_page], self::NEXT_PAGE=>$this->next_page, self::LAST_PAGE=>$this->last_page];
        $flatten_link_names = [];
        foreach ($link_names as $key => $value) {
            if(is_array($value)){
                for($i=1; $i<=$value[0];$i++){
                    $flatten_link_names[$i]=$i;
                }
            }else{
                $flatten_link_names[$key]=$value;
            }
        }
        $links=new \stdClass();
        foreach($flatten_link_names as $key=>$value){
            $links->{$key}=$this->showPaginationHtml($tag, $this->shouldBeActive($key, $value, $classList, $activeName, $notActiveKeys),$this->generateLink($value), $key);
        }
        $this->links=$links;
    }

    //http://mojframework4.test/pagination?products=shoes&per_page=15&page=4&sort=asc

}