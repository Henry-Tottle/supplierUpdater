<?php
//Single use script to import a file into a database.  Import requires csv to be converted to JSON as sequel Ace
// didn't enjoy csv import.  This can be upscaled to work with distributors outside macmillan.

require_once('dbConnect.php');

$json = file_get_contents('csvjsonInvoices.json');

$booksData = json_decode($json, true);


foreach ($booksData as $book)
{
    $distributor = $book['Distributor'];
    $invoice = $book['Invoice'];
    $date = $book['Date'];
    $isbn = $book['ISBN'];
    $title = $book['Title'];
    $author = $book['Author'];
    $qty = $book['QTY'];
    $rrp = $book['RRP'];
    $disc = $book['DISC'];
    $lineCost = $book['LINE COST'];
    $lineRRP = $book['LINE RRP'];

    try{
        $query = $db->prepare('INSERT INTO `books` (`distributor`, `invoice`, `date`, `isbn`, `title`, `author`, `qty`, `rrp`, `disc`, `lineCost`, `lineRRP`)
VALUES (:distributor, :invoice, :date, :isbn, :title, :author, :qty, :rrp, :disc, :lineCost, :lineRRP)');

        $query->execute(['distributor' => $distributor,
            'invoice' => $invoice,
            'date' => $date,
            'isbn' => $isbn,
            'title' => $title,
            'author' => $author,
            'qty' => $qty,
            'rrp' => $rrp,
            'disc' => $disc,
            'lineCost' => $lineCost,
            'lineRRP' => $lineRRP]);
    } catch (PDOException $e) {
        echo 'It didn\'t work \n';
        echo $e->getMessage();
    }
}