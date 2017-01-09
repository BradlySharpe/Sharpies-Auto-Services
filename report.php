<?php
  /*
  ini_set("display_startup_errors", 1);
  ini_set("display_errors", 1);
  error_reporting(E_ALL);
  */

  setlocale(LC_MONETARY,"en_AU");
  require_once ('API/DBase.php');

  $db = new DBase();

  $sql = "SELECT cus.id AS 'cusID', cus.firstname, cus.lastname, cus.address, cus.city, cus.state, cus.postcode, c.id AS 'carID', c.make, c.model, c.registration, ser.odo, inv.bankDetails, inv.created, inv.id AS 'invID' FROM invoice inv INNER JOIN service ser ON (inv.service = ser.id) INNER JOIN customer cus ON (ser.owner = cus.id) INNER JOIN car c ON (ser.car = c.id) WHERE inv.created > '2015-06-30' AND inv.created < '2016-07-01' GROUP BY cus.id, c.id, inv.id ORDER BY cus.id, c.id, inv.id";
  $invoices = $db->fetchAll($sql);

  $lastCustomer = null;
  $lastCar = null;
  $lastInv = null;

  $totalCustomer = 0;
  $totalCar = 0;
  $totalInv = 0;
  $total = 0;

  function printCustomerHeader($line) {
    echo "<h1>Customer: " . $line['firstname'] . " " . $line['lastname'] . "</h1>";
    echo "Address: " . $line['address'] . ", " . $line['city'] . " " . $line['state'] . " " . $line['postcode'];
  }

  function printCarHeader($line) {
    echo "<h2>Car: " . $line['make'] . " " . $line['model'] . " (Rego: " . $line['registration'] . ")</h2>";
  }

  function getInvoiceLines($db, $invoiceID) {
    $sql = "SELECT id AS 'lineNumber', description, cost, quantity, cost*quantity AS total, comment FROM detail WHERE invoice = " . $db->escape($invoiceID);
    return $db->fetchAll($sql);
  }

  function printInvoiceHeader($db, $line) {
    $line['created'] = DateTime::createFromFormat('Y-m-d H:i:s', $line['created']);
    $line['created'] = $line['created']->format("j F Y h:ia");
    echo "<h3>Invoice: #" . $line['invID'] . ((bool)$line['bankDetails'] ? "" : " (Cash Job)") . "</h3>";
    echo "<p>Date: " . $line['created'] . "</p><p>ODO: " . number_format($line['odo']) . "</p>";
    $items = getInvoiceLines($db, $line['invID']);
    $total = 0;
    $lineNumber = 1;
    echo "<table><thead><tr><th>Line</th><th>Description</th><th class=\"right\">Quantity</th><th class=\"right\">Cost</th><th class=\"right\">Total</th><th>Comment</th></tr></thead><tbody>";
    foreach ($items as $item) {
      $total += $item['total'];
      echo "<tr>
      <td>" . $lineNumber . "</td>
      <td>" . $item['description'] . "</td>
      <td class=\"right\">" . $item['quantity'] . "</td>
      <td class=\"right\">" . money_format('%.2n', $item['cost']) . "</td>
      <td class=\"right\">" . money_format('%.2n', $item['total']) . "</td>
      <td>" . $item['comment'] . "</td>
      </tr>";
      $lineNumber += 1;
    }
    echo "</tbody></table>";

    return $total;
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Report - Sharpie's Auto Services</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
    <style>
      h1, h2, h3, .total {
        font-size: 18px;
        font-weight: bold;
        border-bottom: 1px solid #333;
        margin: 30px 0 10px;
      }

      body {
        padding-right: 20px;
      }

      .header {
        margin-left: 20px;
      }

      .invoice p {
        margin: 0 0 5px;
      }

      .total {
        border-bottom: none;
        font-size: 16px;
        margin: 0 0 10px;
      }

      table {
        border-collapse: collapse;
        margin-bottom: 10px;
        font-size: 14px;
      }

      td, th {
        border: 1px solid #999;
        padding: 0.5rem;
        text-align: left;
      }

      .right {
        text-align: right;
      }
    </style>
  </head>
  <body>
    <?php

      foreach ($invoices as $line) {
        if ($line['invID'] !== $lastInv && $lastInv !== null) {
          echo "<p class=\"total\">Invoice Total: " . money_format('%.2n', $totalInv) . "</p>";
          echo "</div><!-- New Invoice -->\n";
          $totalInv = 0;
        }

        if ($line['carID'] !== $lastCar && $lastCar !== null) {
          echo "<p class=\"total\">Car Total: " . money_format('%.2n', $totalCar) . "</p>";
          echo "</div><!-- New Car -->\n";
          $totalCar = 0;
          $totalInv = 0;
        }

        if ($line['cusID'] !== $lastCustomer) {
          if ($lastCustomer !== null) {
            echo "<p class=\"total\">Customer Total: " . money_format('%.2n', $totalCustomer) . "</p>";
            echo "</div><!-- New Customer -->\n";
            $totalCustomer = 0;
            $totalCar = 0;
            $totalInv = 0;
          }
          echo "<div class=\"customer header\">\n";
          printCustomerHeader($line);
        }

        if ($line['carID'] !== $lastCar) {
          echo "<div class=\"car header\">\n";
          printCarHeader($line);
        }

        if ($line['invID'] !== $lastInv) {
          echo "<div class=\"invoice header\">\n";
        }

        $totalInv = printInvoiceHeader($db, $line);
        $totalCar += $totalInv;
        $totalCustomer += $totalInv;
        $total += $totalInv;

        $lastCustomer = $line['cusID'];
        $lastCar = $line['carID'];
        $lastInv = $line['invID'];
      }

      echo "<p class=\"total\">Invoice Total: " . money_format('%.2n', $totalInv) . "</p>";
      echo "</div><!-- New Invoice -->\n";
      echo "<p class=\"total\">Car Total: " . money_format('%.2n', $totalCar) . "</p>";
      echo "</div><!-- New Car -->\n";
      echo "<p class=\"total\">Customer Total: " . money_format('%.2n', $totalCustomer) . "</p>";
      echo "</div><!-- New Customer -->\n";

      echo "<p class=\"total\">Year Total: " . money_format('%.2n', $total) . "</p>";
    ?>
  </body>
</html>
