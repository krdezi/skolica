<?php
    function testIfGoogleComIsRunning() {
        $url = "http://127.0.0.1/PHP%20kurs/appDemo/index.php";
        $curl = curl_init();

// dobijamo nazad resurs kao razultat (nije string, nije broj, samo resurs)

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);

// vraca false ili response, moze i prazan string kao false
        $response = curl_exec($curl);

        if (!$response) {

            die('error: ' .curl_error($curl) .' code: '.curl_errno($curl));
        }
        curl_close($curl);
var_dump(123);
        var_dump($response);

        if (strstr($response, 'id=1st-ib')) {
            die ('nema input polja na google.com');
            
        }
}

testIfGoogleComIsRunning();

?>