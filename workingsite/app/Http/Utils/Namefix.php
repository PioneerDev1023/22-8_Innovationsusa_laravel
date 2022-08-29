<?php
namespace App\Http\Utils;


class Namefix{

	public function productTitle($t1){

  	$t1 = strtoupper($t1);
    //print_r($t1); die();

		if($t1=='TEXTILE WALLCOVERING'){
			$t1 .='S';
		}else if($t1=='WALLCOVERINGS'){
			$t1 ='ALL WALLCOVERINGS';
		}else if($t1=='SHEERS/DRAPERY'){
      $t1 ='SHEERS & DRAPERY';
    }else if($t1=='TEXTURE'){
      $t1 ='TEXTURE & FINISH';
    }
		
	  return $t1;
	}

	public function displayName($t1){

		$t1 = strtolower($t1);

		if($t1=='alchemy+'){
			$t1 = 'Alchemy+';
		}else if($t1=='eco-alchemy'){
			$t1 = 'Eco-Alchemy';
		}
		else{
  		$t1 = str_replace('-', ' ', $t1); // replace - with ' '
  		$t1 = ucwords(strtolower($t1));// title case
		}
	
		if($t1=='yesterdays news'){
			$t1 ='Yesterday&rsquo;s News';
		}else if($t1=='ombre'){
			 $t1 = "Ombr&#233;";
		}
		
	  return $t1;
	}

	public function dbName($t1){
    //print_r($t1); die();
    //$t1 = 'all-wallcovering' ? 'wallcovering' : $t1;
    $t1 = strtolower($t1);

		if($t1=='all-wallcovering'){
      $t1 = 'all-wallcovering'; 
    }
    else if($t1=='alchemy+'){}
    else if($t1=='eco-alchemy'){
      $t1='eco-alchemy';
    }
    else if($t1=='helio-3.0'){
      $t1='ALCHEMY HELIO 3.0';
    }
    /*else if($t1=='sheers-drapery'){
      $t1='sheers/drapery';
    }
    else if($t1=='sheers-drapery'){
      $t1='sheers/drapery';
    }*/
		else{
  		$t1 = str_replace('-', ' ', $t1); // replace - with ' '
  		$t1 = str_replace('_', ' ', $t1); // replace _ with /
		}

    /*else if($t1=='whats-new'){
      //$t1='ALCHEMY HELIO 3.0';
    }*/

    /*if (strpos(strtolower($t1), 'sheers ') !== false) {
      $t1 = 'sheers/drapery';
    }*/

  	$t1 = strtolower($t1);

		/*if($t1=='yesterdays+news'){
			$t1 ='yesterdays news';
		}*/
		
	  return $t1;
	}

	public function thumbImageName($name, $suffix) {
		if ($name == "" || $name == null || $name == 'NULL')
			return $name;
		else {
			$extension = pathinfo($name, PATHINFO_EXTENSION);
			$filename = rtrim($name, $extension);
			$filename = substr($filename, 0, strlen($filename)-1);
			return $filename.'-'.$suffix.'.'.$extension;
		}
	}

	public function jpgName($t1){

		$t1 = strtolower($t1);
    $t1 = str_replace(' ', '-', $t1);
//print_r(' ' . $t1 . ' '); 
		if($t1=='alchemy+'){ $t1 = 'Alchemy+'; }
		else if($t1=='eco-alchemy'){ $t1 = 'Eco-Alchemy'; }
    else if($t1=='alchemy helio 3.0'){ $t1 = 'Helio-3.0'; }
    else if($t1=='miroir c'||$t1=='miroir d'||$t1=='miroir e'){
      $t1 = 'Miroir';
    }
		else{
			//$t1 = ucwords(strtolower($t1));// title case
  		$t1 = str_replace(' ', '-', $t1); // replace ' ' with -
		}

  	//

		/*if($t1=='yesterdays+news'){
			$t1 ='yesterdays news';
		}*/
		
	  return $t1;
	}

	public function urlName($t1){

		if($t1=='alchemy+'){}
    /*else if(strtolower($t1)=='alchemy helio 3.0'){
      $t1 = 'helio-3.0';
    }*/
		else{
  		$t1 = str_replace(' ', '-', $t1); // replace ' ' with -
		}

  	$t1 = strtolower($t1);

		/*if($t1=='yesterdays+news'){
			$t1 ='yesterdays news';
		}*/
		
	  return $t1;
	}

	public function image_resize($file_name, $width, $height, $crop=FALSE) {
		list($wid, $ht) = getimagesize($file_name);
		$urli = getimagesize($file_name);
		$r = $wid / $ht;
		if ($crop) {
		   if ($wid > $ht) {
			  $wid = ceil($wid-($width*abs($r-$width/$height)));
		   } else {
			  $ht = ceil($ht-($ht*abs($r-$w/$h)));
		   }
		   $new_width = $width;
		   $new_height = $height;
		} else {
		   if ($width/$height > $r) {
			  $new_width = $height*$r;
			  $new_height = $height;
		   } else {
			  $new_height = $width/$r;
			  $new_width = $width;
		   }
		}
		//$source = imagecreatefromjpeg($file_name);
		if ($urli["mime"] == "image/jpg") {
			$source = imagecreatefromjpg($file_name);
		} elseif ($urli["mime"] == "image/png") {
			$source = imagecreatefrompng($file_name);
		} elseif ($urli["mime"] == "image/gif") {
			$source = imagecreatefromgif($file_name);
		} elseif ($urli["mime"] == "image/jpeg") {
			$source = imagecreatefromjpeg($file_name);
		}
		$dst = imagecreatetruecolor($new_width, $new_height);
		image_copy_resampled($dst, $source, 0, 0, 0, 0, $new_width, $new_height, $wid, $ht);		
		return $dst;
	 }

}