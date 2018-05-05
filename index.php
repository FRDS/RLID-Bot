<?php
error_reporting( E_ALL );
ini_set('display_errors',1);
$method = $_SERVER['REQUEST_METHOD'];

//include file
include_once dirname(__FILE__) .DIRECTORY_SEPARATOR. 'response.php' ;
include_once dirname(__FILE__) .DIRECTORY_SEPARATOR. 'item.php' ;
include_once dirname(__FILE__) .DIRECTORY_SEPARATOR. 'rank.php' ;

$response = new ResponseMsg;


// Process only when method is POST
if( $method )
{
		$requestBody = file_get_contents('php://input');
		$json = json_decode($requestBody);

		if( isset($_GET['debug'] )){
			if($_GET['debug'] == 'php'):
				echo '<pre>'.print_r($json->result,TRUE).'</pre>';
			else:
				echo json_encode($json);
			endif;
		}

		//check the text
		if( isset($json->result->resolvedQuery) ){
			$text = $json->result->resolvedQuery;
		}

		//check action
		if( isset($json->result->action) ){
			$action = $json->result->action;
		}

		//check action, future implementation
		if( isset($action) ){

			switch( $action ):

					case 'checkprice':
						$item = new Item;
						$result = $item->setQuery($json->result->resolvedQuery)
														->setPlatform($item->platform)
														->getPrice();

						// echo $response->setText($item->speech,$result)->result();
						echo $response->setText( $result ,$result)->result();
						die();
					break;

					case 'checkrank':
						$rank = new Rank;
						$result = $rank->setQuery($json->result->resolvedQuery)
														->setPlatform($rank->platform)
														->getID();

						echo $response->setText( $result ,$result)->result();
						die();
					break;
			endswitch;
		}


		//normal response
		switch($text) {
			case '!credit':
				$text = "All prices courtesy of https://rl.insider.gg\nSpecial thanks to:\n \xE2\x97\xBE Ruffe - Ananta Rizki F. (Mastermind) \n \xE2\x97\xBE xGFYSx - Dewantara Tirta (Programmer)\n \xE2\x97\xBE FRDS  - Agung Firdaus (Tester and Helper)\n \xE2\x97\xBE Devs at RL Insider (Yggdrasil128 and colleagues)";
				break;

			case '!help':
				$text = "\xE2\x9D\x97 Daftar perintah :\n \xE2\x97\xBE !help - Menampilkan pesan panduan\n \xE2\x97\xBE !price <nama item> <warna (optional)> <platform> - Mengecek harga item\n \xE2\x97\xBE !credit - Menampilkan pesan credit";
				break;

			case '!rlid':
				$text = "\xE2\x9E\xA1 Facebook Page : http://www.facebook.com/RocketLeagueID\n\xE2\x9E\xA1 Grup Steam : http://steamcommunity.com/groups/RLID\n\xE2\x9E\xA1 Instagram : http://www.instagram.com/rocketleague.id\n\xE2\x9E\xA1 Grup Line Square : http://line.me/ti/g2/ICUFW8K9FE\n\xE2\x9E\xA1 Discord : https://discord.gg/Fg7B557";
				break;

			case '!mabar':
				$text = "\xF0\x9F\x8F\x81 SPARRING/MABAR RLID \xE2\x9A\xBD \xF0\x9F\x8F\x8E\n\xF4\x80\x82\x8D Ayo ikut sparring/mabar komunitas!\n\nIkutnya gampang, tinggal join private match dengan format room :\n\xE2\x9E\xA1 name : rlid\n\xE2\x9E\xA1 password : rlid\n\n\xE2\x9D\x97 Jangan lupa untuk join di voice chat discord di channel \"Parkiran\"";
				break;

			case '!rewards':
				$text = "\xE2\x97\xBE Jadwal RLCS\n\xF0\x9F\x8C\x8ENorth America: Minggu, 02:00 WIB\n\xF0\x9F\x8C\x8D Europe: Senin, 00:00 WIB\n\xF0\x9F\x8C\x8FOceania: Minggu, 07:00 WIB\n\xE2\x97\xBE Jadwal RLRS\n\xF0\x9F\x8C\x8D Europe: Sabtu, 00:00 WIB\n\xF0\x9F\x8C\x8ENorth America: Sabtu 06:00 WIB";
				break;

			//no response (biar gak duplikat, atau ngulang kalimat kita)
			default:
				die();
			break;
		}
		echo $response->setText($text,$text)->result();
		die();
}
else{
		echo 'Not allowed';
}

?>
