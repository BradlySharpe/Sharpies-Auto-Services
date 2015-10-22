<?php

  if (!array_key_exists('id', $_GET))
    die("Couldn't find invoice ID");

  $invoiceId = $_GET['id'];
  $close = (array_key_exists('close', $_GET)) ? (bool)$_GET['close'] : true;

  require_once ('API/DBase.php');

  $db = new DBase();

  $sql = "SELECT CAST(inv.created AS Date) as 'invoiceDate', inv.id as 'invoiceId', cust.firstname, cust.lastname, cust.address, cust.city, cust.state, cust.postcode, c.make, c.model, c.registration FROM invoice inv INNER JOIN service serv ON (serv.id = inv.service) INNER JOIN customer cust ON (cust.id = serv.owner) INNER JOIN car c ON (serv.car = c.id) WHERE inv.id = " . $db->escape($invoiceId);
  $invoice = $db->fetchOne($sql);

  $invoice['invoiceDate'] = DateTime::createFromFormat('Y-m-d', $invoice['invoiceDate']);
  $invoice['invoiceDate'] = $invoice['invoiceDate']->format("j F Y");

  $sql = "SELECT det.*, (det.cost * det.quantity) AS total FROM detail det WHERE det.invoice = " . $db->escape($invoiceId);
  $invoiceDetails = $db->fetchAll($sql);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Invoice - Sharpie's Auto Services</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" media="all" href="css/invoice.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
  </head>
  <body>
    <table>
      <tr>
        <td width="50%">
          <img class="logo" src="images/logo.png" />
        </td>
        <td width="50%">
          <div class="u-pull-right">
            Sharpie's Auto Services<br />
            68 Donovans Road,<br />
            Warrnambool, Victoria 3280<br />
            <strong>Phone:</strong> 0418 360 145<br />
            <strong>Email:</strong> sharpie@tailormadesolutions.com.au
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="invoiceNumber">
          <span>Invoice</span> #<?php echo $invoice['invoiceId']; ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <strong>Invoice Date:</strong> <?php echo $invoice['invoiceDate']; ?>
        </td>
      </tr>
      <tr>
        <td>
          <strong>Billing Address:</strong><br />
          <?php echo $invoice['firstname'] . " " . $invoice['lastname']; ?><br />
          <?php echo $invoice['address']; ?>,<br />
          <?php echo $invoice['city']; ?>, <?php echo $invoice['state'] . " " . $invoice['postcode']; ?>
        </td>
        <td>
          <strong>Vehicle Details:</strong><br />
          Make: <?php echo $invoice['make']; ?><br />
          Model: <?php echo $invoice['model']; ?><br />
          Registration: <?php echo $invoice['registration']; ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <table id="invoiceDetails" border="1">
            <tr>
              <th>Description</th>
              <th class="text-right">Cost</th>
              <th class="text-right">Quantity</th>
              <th class="text-right">Total</th>
            </tr>
            <?php
              $subtotal = 0;
              for ($i=0; $i < count($invoiceDetails); $i++) {
                $detail = $invoiceDetails[$i];
                $detail['cost'] = round($detail['cost'], 2);
                $detail['quantity'] = round($detail['quantity'], 2);
                $detail['total'] = round($detail['total'], 2);
                $row  = "<tr>";
                $row .= " <td>" . $detail['description'] . "<div class=\"item-description\">" . $detail['comment'] . "</div></td>";
                $row .= " <td class=\"text-right\">$" . number_format($detail['cost'], 2) . "</td>";
                $row .= " <td class=\"text-right\">" . number_format($detail['quantity'], 2) . "</td>";
                $row .= " <td class=\"text-right\">$" . number_format($detail['total'], 2) . "</td>";
                echo $row;
                $subtotal += $detail['total'];
              }
              /*
              foreach ($invoiceDetails as $key => $detail) {
                $detail['cost'] = round($detail['cost'], 2);
                $detail['quantity'] = round($detail['quantity'], 2);
                $detail['total'] = round($detail['total'], 2);
                $row  = "<tr>";
                $row .= " <td>" . $detail['description'] . "</td>";
                $row .= " <td class=\"text-right\">$" . number_format($detail['cost'], 2) . "</td>";
                $row .= " <td class=\"text-right\">" . number_format($detail['quantity'], 2) . "</td>";
                $row .= " <td class=\"text-right\">$" . number_format($detail['total'], 2) . "</td>";
                echo $row;
                $subtotal += $detail['total'];
              }
              */
            ?>
            <!--
            <tr>
              <td colspan="3" class="total">
                Subtotal:
              </td>
              <td class="text-right">
                $<?php echo number_format($subtotal, 2); ?>
              </td>
            </tr>
            <tr>
              <td colspan="3" class="total">
                Tax:
              </td>
              <td class="text-right">
                $<?php echo number_format($subtotal * 0.1, 2); ?>
              </td>
            </tr>
          -->
            <tr>
              <td colspan="3" class="total">
                Total:
              </td>
              <td class="text-right">
                <!-- $<?php //echo number_format($subtotal * 1.1, 2); ?> -->
                $<?php echo number_format($subtotal * 1, 2); ?>
              </td>
            </tr>
          </table>
          <div class="terms">
            Payment Terms: Cash On Delivery
          </div>
        </td>
      </tr>
    </table>
    <script>
      window.print();
      <?php
      if ($close) { echo 'setTimeout(function() { window.close(); }, 500);'; }
      ?>
    </script>
  </body>
</html>
