<?php

function testRootUserListShouldDisplayUserList() {

    $url = "http://127.0.0.1/novo/appDemo//index.php?route=userList";
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($curl);

        if (!$response) {

            die('error: ' .curl_error($curl) .' code: '.curl_errno($curl));
        }
        curl_close($curl);

        if (!strstr($response, 'id="email-filter"')) {
            die ('Lista se ne prikazuje');
            
        }
}

testRootUserListShouldDisplayUserList();

function testCreateUserShouldCreateUser(){
    $url = "http://127.0.0.1/novo/appDemo//index.php?route=createUser";
    $curl = curl_init();
    $response = curl_exec($curl);
        if ($response )
    $params = [
        'email' => $params['email'], 
        'password' => createPasswordHash($params['password']),
        'firstName' => $params['firstName'], 
        'lastName' => $params['lastName'], 
        'username' => $params['username'], 
    ];

    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($curl);

    if (!$response) {

        die('error: ' .curl_error($curl) .' code: '.curl_errno($curl));
    }
    curl_close($curl);

    if (strstr($response, 'probaproba@test.com')) {
        die ('Nema forme');
        
    }
}

testCreateUserShouldCreateUse();

function testRootUpdateArticleShouldUpdateArticleData(){
    $url = "http://127.0.0.1/novo/appDemo//index.php?route=updateArticleForm&title='Harry Potter'";
    $curl = curl_init();
    $params = [
        'description' => $params['description'], 
        'body' => $params['body'], 
        'category' => $params['category'], 
    ];

    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $dom = new DOMDocument(1.0);
    $dom = loadHTML($response);
    $element = $dom->getElementById('body');
        echo $element->nodeValue;
            if ($element->nodeValue === $params['body']) {
                echo 'Update body je uspeo';

            }
    curl_close($curl);

}

testRootUpdateArticleShouldUpdateArticleData();


?>