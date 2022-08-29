<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\ProductMaster;
use TeamTNT\TNTSearch\TNTSearch;

class Ajax extends Model
{
    private $hiddenArr = array(
      array('custom_item', '<>', 1),
      array('discontinue_code', '<>', 1),
      // array('product_type', 'NOT LIKE', '%Faux Leather%'),
      // array('product_category', 'NOT LIKE', '%Faux Leather%')
      //array('custom_item', 'NOT LIKE', '%1%')
    );

    private function like_match($pattern, $subject){
        $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
        return (bool) preg_match("/^{$pattern}$/i", $subject);
    }

    public function quickResult($searchStr){ 

      // sku search stuff
      $sku = null;
      $skuKeywords  = array('BPAB', 'AER', 'ALC', 'R789', 'ALN', 'ALI', 'ALL', 'AML', '\AND', 'ANG', 'AWP', 'ARP', 'ARR', 'ASR', 'AUR', 'AUR', 'BZN', 'U1387', 'BRT', 'BAU', 'BEL150', 'BN', 'BIR', 'BLO', 'BOA', 'BDI', 'BRD', 'BOT', 'BUC', 'BOU', 'BCA', 'BUR', 'BUS', 'CAB', 'CAI', 'CIO', 'CAM', 'C584', 'CMP', 'CVN', 'CPR', 'CHY', 'CAV', 'CHL', 'CRC', 'CHI', 'CHS', 'CIN', 'COA', 'COB', 'CON', 'CTS', 'CRD', 'CRK', 'CUB', 'CRT', 'DAI', 'DNB', 'DEL', 'DEN', 'SKO#', 'DST', 'DOJ', 'DUP', 'ECO', 'ECR', 'EDO001', 'ELP', 'ELE', 'EPR', 'ESC', 'EVO', 'FAC', 'Fac', 'FEL', 'FIK', 'FLR', 'FLO', 'FLU', 'FMA', 'FOU', 'FRQ', 'FUS', 'GAN', 'GED', 'GCO', 'GCO', 'GCO', 'GLI', 'GLY', 'GOA', 'GOB', 'GRD', 'GRI', 'GUI', 'HAR', 'HU01', 'HU101N', 'HUN', 'ILL', 'ILS', 'INT', 'ITS', 'ISA', 'JPR', 'JKT', 'KRT', 'KER', 'KOM', 'LAB', 'LBK', 'LAC', 'LAE', 'LIL', 'LON', 'LUC', 'LUN', '240', 'MCR', 'MAD', 'U13', 'J30', 'MNE', 'MGA', 'MGE', 'MTR', 'MCO', 'MKS', 'MFR', 'MAZ', 'MAE', 'MER', 'MIN', 'MST', 'MIS', 'MIZ', 'MON', 'MTD', 'MRY', 'J604', 'J602', 'NEB', 'NW', 'OAS', 'ODN', 'SAN', 'ORI0', 'PAL', 'PSP', 'BPPA', 'PAS', 'PRL17', 'PEB', 'PET', 'PHR', 'PIN', 'PIX', 'PLA', 'PTS', 'PTL', 'POV', 'PRE', 'PRO', 'PLS', 'PUR', 'QUA', 'RAC', 'RAN', 'RS84', 'REE', 'RPT', 'RIC', 'RIF', 'RIP', 'RO600', 'ROP', 'ROP', 'RUB', 'SAB', 'SAI', 'SAC', 'SAT', 'SEA', 'SER', 'SHL', 'SRA', 'SIL', 'SOM', 'SOA', 'SOD', 'SON', 'STE', 'SPA', 'SPI', 'SPL', 'SRI', 'STA', 'SUM', 'SYN', 'THI', 'TEL', 'TIB', 'TID', 'TRA', 'TUB', 'TUC', 'TLM', 'TUS', 'VAN', 'VAR', 'VEC', 'VRV', 'VIE151', 'VLT', 'VOL', 'WLD', 'WTR', 'U30', 'WIL', 'WDG', 'WLE', 'WWH', 'YAK', 'YN', 'YOR', 'YOS', 'YUM', 'ZIO');
      $skuKey = explode('-', $searchStr);
      if(in_array(strtoupper($skuKey[0]), $skuKeywords)) {
        $sku = $searchStr;
      }

      // sku search stuff
      
      $tnt = new TNTSearch;      
      $searchStr = str_replace(" ", ") or (", $searchStr);
      $searchStr = str_replace("/", " ", $searchStr);
      // $searchStr = str_replace("-", " ", $searchStr);
      $searchStr = preg_replace("/^spe[cialty]*\s*/i", "Specialty or (Inspired Material)", $searchStr); 
      $searchStr = "(".$searchStr.")";

      $tnt->loadConfig(config("scout.tntsearch"));
      $tnt->selectIndex('all-wallcovering.index');
      $tnt->fuzziness = true;
      $tnt->fuzzy_prefix_length = 10;
      $tnt->fuzzy_distance = 10;
      $res = $tnt->searchBoolean($searchStr, 1000);

      if($sku) {        
        $items = ProductMaster::whereIn('id_pdf', $res['ids'])->where($this->hiddenArr)->where('item_number', 'LIKE', '%'.$sku.'%')->limit(20)->get();        
      } else {
        $items = ProductMaster::whereIn('id_pdf', $res['ids'])->where($this->hiddenArr)->groupBy('item_name')->limit(20)->get();
      }

      return $items;
    }

    public function pageResult($searchStr){

      // sku search stuff
      $sku = null;
      $skuKeywords  = array('BPAB', 'AER', 'ALC', 'R789', 'ALN', 'ALI', 'ALL', 'AML', '\AND', 'ANG', 'AWP', 'ARP', 'ARR', 'ASR', 'AUR', 'AUR', 'BZN', 'U1387', 'BRT', 'BAU', 'BEL150', 'BN', 'BIR', 'BLO', 'BOA', 'BDI', 'BRD', 'BOT', 'BUC', 'BOU', 'BCA', 'BUR', 'BUS', 'CAB', 'CAI', 'CIO', 'CAM', 'C584', 'CMP', 'CVN', 'CPR', 'CHY', 'CAV', 'CHL', 'CRC', 'CHI', 'CHS', 'CIN', 'COA', 'COB', 'CON', 'CTS', 'CRD', 'CRK', 'CUB', 'CRT', 'DAI', 'DNB', 'DEL', 'DEN', 'SKO#', 'DST', 'DOJ', 'DUP', 'ECO', 'ECR', 'EDO001', 'ELP', 'ELE', 'EPR', 'ESC', 'EVO', 'FAC', 'Fac', 'FEL', 'FIK', 'FLR', 'FLO', 'FLU', 'FMA', 'FOU', 'FRQ', 'FUS', 'GAN', 'GED', 'GCO', 'GCO', 'GCO', 'GLI', 'GLY', 'GOA', 'GOB', 'GRD', 'GRI', 'GUI', 'HAR', 'HU01', 'HU101N', 'HUN', 'ILL', 'ILS', 'INT', 'ITS', 'ISA', 'JPR', 'JKT', 'KRT', 'KER', 'KOM', 'LAB', 'LBK', 'LAC', 'LAE', 'LIL', 'LON', 'LUC', 'LUN', '240', 'MCR', 'MAD', 'U13', 'J30', 'MNE', 'MGA', 'MGE', 'MTR', 'MCO', 'MKS', 'MFR', 'MAZ', 'MAE', 'MER', 'MIN', 'MST', 'MIS', 'MIZ', 'MON', 'MTD', 'MRY', 'J604', 'J602', 'NEB', 'NW', 'OAS', 'ODN', 'SAN', 'ORI0', 'PAL', 'PSP', 'BPPA', 'PAS', 'PRL17', 'PEB', 'PET', 'PHR', 'PIN', 'PIX', 'PLA', 'PTS', 'PTL', 'POV', 'PRE', 'PRO', 'PLS', 'PUR', 'QUA', 'RAC', 'RAN', 'RS84', 'REE', 'RPT', 'RIC', 'RIF', 'RIP', 'RO600', 'ROP', 'ROP', 'RUB', 'SAB', 'SAI', 'SAC', 'SAT', 'SEA', 'SER', 'SHL', 'SRA', 'SIL', 'SOM', 'SOA', 'SOD', 'SON', 'STE', 'SPA', 'SPI', 'SPL', 'SRI', 'STA', 'SUM', 'SYN', 'THI', 'TEL', 'TIB', 'TID', 'TRA', 'TUB', 'TUC', 'TLM', 'TUS', 'VAN', 'VAR', 'VEC', 'VRV', 'VIE151', 'VLT', 'VOL', 'WLD', 'WTR', 'U30', 'WIL', 'WDG', 'WLE', 'WWH', 'YAK', 'YN', 'YOR', 'YOS', 'YUM', 'ZIO');
      
      $skuKey = explode('-', $searchStr);
      if(in_array(strtoupper($skuKey[0]), $skuKeywords)) {
        $sku = $searchStr;
      }
      
      // sku search stuff

      $tnt = new TNTSearch;           
      $searchStr = str_replace(" ", "*) or (", $searchStr);
      $searchStr = str_replace("/", " ", $searchStr);
      // $searchStr = str_replace("-", " ", $searchStr);
      $searchStr = preg_replace("/^spe[cialty]*\s*/i", "Specialty or (Inspired Material)", $searchStr); 
      $searchStr = "(".$searchStr.")";

      $tnt->loadConfig(config("scout.tntsearch"));
      $tnt->selectIndex('all-wallcovering.index');
      $tnt->fuzziness = true;
      $tnt->fuzzy_prefix_length = 10;
      $tnt->fuzzy_distance = 10;      

      $res = $tnt->searchBoolean($searchStr, 1000);

      if($sku) {        
        $items = ProductMaster::whereIn('id_pdf', $res['ids'])->where($this->hiddenArr)->where('item_number', 'LIKE', '%'.$sku.'%')->limit(20)->get();        
      } else {
        $items = ProductMaster::whereIn('id_pdf', $res['ids'])->where($this->hiddenArr)->groupBy('item_name')->limit(20)->get();
      }

      return $items;
 
    }

    public function insertSignup($v1){
      $id = DB::table('newsletter')->insertGetId( [ 'email' => $v1 ] );

      return $id;
    }

}
