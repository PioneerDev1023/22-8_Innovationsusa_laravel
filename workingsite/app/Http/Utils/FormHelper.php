<?php
namespace App\Http\Utils;

class FormHelper{

  public function countryArr(){
    $countryList = array(
      'USA' => 'U.S.A.',
      'Australia' => 'Australia',
      'Canada' => 'Canada',
      'CHINA' => 'China',
      'FRANCE' => 'France',
      'INDIA' => 'India',
      'JAPAN' => 'Japan',
      'MEXICO' => 'Mexico',
      'NEW ZEALAND' => 'New Zealand',
      'PHILIPPINES' => 'Philippines',
      'RUSSIA' => 'Russia',
      'SINGAPORE' => 'Singapore',
      'South Korea' => 'South Korea',
      'TAIWAN' => 'Taiwan',
      'THAILAND' => 'Thailand',
      'TURKEY' => 'Turkey',
      'United Arab Emirates' => 'United Arab Emirates',
      'United Kingdom' => 'United Kingdom',
      'VIETNAM' => 'Vietnam'
    );
    return $countryList;
  }
  public function statesArr(){
    $stateList = array( 'AL'=>'Alabama',
                        'AK'=>'Alaska',
                        'AZ'=>'Arizona',
                        'AR'=>'Arkansas',
                        'CA'=>'California',
                        'CO'=>'Colorado',
                        'CT'=>'Connecticut',
                        'DE'=>'Delaware',
                        'DC'=>'District of Columbia',
                        'FL'=>'Florida',
                        'GA'=>'Georgia',
                        'HI'=>'Hawaii',
                        'ID'=>'Idaho',
                        'IL'=>'Illinois',
                        'IN'=>'Indiana',
                        'IA'=>'Iowa',
                        'KS'=>'Kansas',
                        'KY'=>'Kentucky',
                        'LA'=>'Louisiana',
                        'ME'=>'Maine',
                        'MD'=>'Maryland',
                        'MA'=>'Massachusetts',
                        'MI'=>'Michigan',
                        'MN'=>'Minnesota',
                        'MS'=>'Mississippi',
                        'MO'=>'Missouri',
                        'MT'=>'Montana',
                        'NE'=>'Nebraska',
                        'NV'=>'Nevada',
                        'NH'=>'New Hampshire',
                        'NJ'=>'New Jersey',
                        'NM'=>'New Mexico',
                        'NY'=>'New York',
                        'NC'=>'North Carolina',
                        'ND'=>'North Dakota',
                        'OH'=>'Ohio',
                        'OK'=>'Oklahoma',
                        'OR'=>'Oregon',
                        'PA'=>'Pennsylvania',
                        'RI'=>'Rhode Island',
                        'SC'=>'South Carolina',
                        'SD'=>'South Dakota',
                        'TN'=>'Tennessee',
                        'TX'=>'Texas',
                        'UT'=>'Utah',
                        'VT'=>'Vermont',
                        'VA'=>'Virginia',
                        'WA'=>'Washington',
                        'WV'=>'West Virginia',
                        'WI'=>'Wisconsin',
                        'WY'=>'Wyoming');
    return $stateList;
  }
}
?>