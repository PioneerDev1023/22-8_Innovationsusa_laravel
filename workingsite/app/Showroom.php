<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \DB;

class Showroom extends Model
{
  public function getShowroomList($type, $searchArr){

    $searchQuery = $searchArr[0][0];
    $fullIO =  $searchArr[1][0];

    // print_r($searchQuery);
    // print_r($fullIO);die();

    $prodObj = DB::table('rep');
    //$query0 = "SELECT * FROM `rep` ";

    $searchArr = $searchArr[1];
    foreach($searchArr as $key => $obj){

      if($searchQuery=='corporate'){
        $qq1 = 'state_abr';
      }else if($searchQuery=='partner'){
        $qq1 = 'state_abr';
        //$qq1 = 'state_full';
      }
      else if($searchQuery=='international'){
        $qq1 = 'state_abr';
      }
      /*else if($searchQuery=='city'){
        $qq1 = 'city_2';
      }*/

      //$prodObj = $prodObj->where('state_full', '!=', '');

      if($searchQuery=='partner' && $fullIO=='FULL'){
        //$query0 .= "WHERE `$qq1` != '' ";
        $prodObj = $prodObj->where($qq1, '!=', '')->where($qq1, '!=', NULL);

      }else if($searchQuery=='international' && $fullIO=='FULL'){
        //$query0 .= "WHERE `$qq1` = '' ";
        $prodObj = $prodObj->where($qq1, '=', '')->orWhere($qq1, '=', NULL);
      }
      else{
        if($key==0){
          //$query0 .= "WHERE `$qq1` LIKE '%{$searchArr[$key]}%' ";
          $prodObj = $prodObj->where($qq1, 'LIKE', '%'.$searchArr[$key].'%');
        }
        else{
          // $query0 .= "OR `$qq1` LIKE '%{$searchArr[$key]}%' ";
          $prodObj = $prodObj->orwhere($qq1, 'LIKE', '%'.$searchArr[$key].'%');
        }
      }

      //$query0 .= "AND `$qq1` NOT LIKE 'State Abbrev.' ";
      //$prodObj = $prodObj->where($qq1, 'NOT LIKE', 'State Abbrev.');
    }

    //if($searchQuery=='state' && $fullIO=='FULL'){
      // $query0 .= "ORDER BY `city` ";
      $prodObj = $prodObj ->orderBy('city', 'asc');
      //echo $query0; die();
    /*}
    else{
        if($searchQuery=='international')
      $query0 .= "ORDER BY `city` ";
      else
        $query0 .= "ORDER BY `city` ";
    }*/

    $prodObj = $prodObj->get();

    //dd(DB::getQueryLog());
    return  $prodObj;
  }

  public function getRepSearch($country, $state){

    $searchQuery = 'country';
    $qq1 = 'country';
    $str = $country;

    if($state !=''){
      $searchQuery = 'state';
      $qq1 = 'state_abr';
      $str = $state;
    }
    //$searchQuery = $searchArr[0][0];

    $query = DB::table('rep');
    $query = $query->where($qq1, 'LIKE', '%'. $str .'%');

    /*if($searchQuery=='country'){
      // $query0 .= "AND `state_abr` = '' ";
      $query = $query->where($qq1, '=', '');
    }*/

    //dd($query->toSql());

    $query = $query->get();

    return  $query;

  }
}
