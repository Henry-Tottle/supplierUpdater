<?php

require_once('dbConnect.php');

$query = $db->query("SELECT `isbn` FROM `books` WHERE `publisher` IS NULL");
$query->execute();
$isbnList = $query->fetchAll(PDO::FETCH_COLUMN);

$bookData = [];

foreach ( $isbnList as $isbn )
{
    $isbn = trim($isbn);
    $apiURL = "https://openlibrary.org/api/books?bibkeys=ISBN:$isbn&format=json&jscmd=data";


    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $apiURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "cURL ERROR: " .  curl_error($ch) . "\n";
        curl_close($ch);
        continue;
    }

    curl_close($ch);

    $bookInfo = json_decode($response, true);

    if (!empty($bookInfo) && isset($bookInfo["ISBN:$isbn"])) {
        $bookDetails = $bookInfo["ISBN:$isbn"];

        if (isset($bookDetails['publishers'][0]['name'])) {
            $publisher = $bookDetails['publishers'][0]['name'];
        }
    }

    echo "ISBN: $isbn -> Publisher: $publisher \n";

    $query = $db->prepare('UPDATE `books` SET `publisher` = :publisher WHERE `isbn` = :isbn');
    $query->execute(['publisher' => $publisher, 'isbn' => $isbn]);

    usleep(500000);
}

print_r($bookData);
echo 'Database Updated';




