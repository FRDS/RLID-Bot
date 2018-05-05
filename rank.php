<?php

class Rank
{
    public $error = false;
    public $error_msg = '';
    public $error_code;
    
    public $query = '';
    public $platform = 'pc';
    
    public $default_platform;
    
    //response from api
    public $response = false;
    
    //response Speech
    public $speech;
    
    //response displayText
    public $displayText;
    
    //set default platform
    public function __construct()
    {
        
        $this->default_platform = array(
            'pc' => array(
                'pc',
                'steam'
            ),
            'ps4' => array(
                'ps4',
                'ps'
            )
        );
    }

    function setQuery($query)
    {
        if( is_int( strpos($query,'!rank') ) == FALSE ){
          exit();
        }

        if (strlen($query) <= 6 ) {
            $this->error_code = 0;
            return $this;
        } else {
                //remove !price from string
            $query = str_replace('!rank ', '', $query);
            
                //remove platform from string
            foreach ($this->default_platform as $var) {
                foreach ($var as $varr) {
                    if( is_int( strpos($query,$varr) ) == TRUE ){
                        $this->platform = $varr;
                        $varr .= " ";
                        $query = str_replace($varr, '', $query);
                    }   
                }
            }
            
            $this->query = $query;
            return $this;
        }
    }
 
    function setPlatform($platform)
    {
        switch ($platform):
            
            case '':
                $this->platform = 'pc';
                break;
            
            case 'pc':
                $this->platform = 'pc';
                break;
            
            case 'ps4':
                $this->platform = 'ps4';
                break;
            
            case 'ps':
                $this->platform = 'ps4';
                break;
            
            default:
                $this->error_code = 1;
                break;
                
        endswitch;
        
        return $this;
    }
    
    // function error($code, $external_msg = '')
    // {
    //     switch ($code):
            
    //         case 0:
    //             $msg = "\xE2\x9A\xA0 Nama itemnya diisi dulu gan\n\xE2\x9E\xA1 Full pricelist cek di https://rl.insider.gg";
    //             break;
            
    //         case 1:
    //             $msg = "\xE2\x9A\xA0 Platform yang tersedia hanya PC dan PS4\n\xE2\x9E\xA1 Full pricelist cek di https://rl.insider.gg";
    //             break;
            
    //         case 2:
    //             $msg = "\xE2\x9A\xA0 Item ini tidak ada di platform " . strtoupper($this->platform) . "\n\xE2\x9E\xA1 Full pricelist cek di https://rl.insider.gg";
    //             break;
            
    //         case 3:
    //             $msg = "\xE2\x9A\xA0 " . $this->response->ItemName . " tidak ada dalam warna " . $this->response->PaintName . "\n\xE2\x9E\xA1 Full pricelist cek di https://rl.insider.gg";
    //             break;
            
    //         case 4:
    //             $msg = "\xE2\x9A\xA0 Belum ada harga untuk item ini \n\xE2\x9E\xA1 Full pricelist cek di https://rl.insider.gg";
    //             break;
            
    //         case 5:
    //             $msg = "\xE2\x9D\x93 Apakah anda mencari item ini?\n\n";
    //             $msg .= $external_msg;
    //             $msg .= "\n\xE2\x9E\xA1 Full pricelist cek di https://rl.insider.gg";
    //             break;
            
            
    //         default:
    //             //return the text
    //             $msg = $code;
    //             break;
                
    //     endswitch;
        
    //     $this->error     = true;
    //     $this->error_msg = $msg;
        
    //     $this->speech = $msg;
    //     return $msg;
    // }
    
    function result($msg)
    {
        $this->response = $msg;
        return $msg;
    }
    
    function getID()
    {
        $key = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
        $user = $this->query;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://api.steampowered.com/ISteamUser/ResolveVanityURL/v0001/?key=$key&vanityurl=$user",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $temp = json_decode($response);
        $this->response = $temp->response;

        if ($err) {
            $this->error($err);
        } else {
            // return $response;
            return $this->_makeResponse($response);
        }
    }

    function _makeResponse()
    {
        $response   = $this->response;
        $error_code = $this->error_code;
        
        // //check error
        // if (isset($error_code)) {
        //     return $this->error($error_code);
        // }

        // Generate text
        $result = '';
        if($response->success == 1){
            $result .= "\xE2\x9E\xA1 Steam ID : " . $response->steamid;
        } else {
            $result .= "\xE2\x9A\xA0 ID tidak ditemukan";
        }
            return $result;
    }
}

?>
