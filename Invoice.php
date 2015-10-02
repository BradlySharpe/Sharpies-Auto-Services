<?php

  if (!array_key_exists('id', $_GET))
    die("Couldn't find invoice ID");

  $invoiceId = $_GET['id'];

  require_once ('API/DBase.php');

  $db = new DBase();
  $sql = "select c.firstname, c.lastname, c.address, c.city, c.state, c.postcode, cast(sc.created as date) as created, ca.make, ca.registration from safetycheck sc INNER JOIN service s ON (sc.id = s.safetyCheck) INNER JOIN customer c ON (s.owner = c.id) INNER JOIN car ca ON (s.car = ca.id) where sc.id = " . $db->escape($safetyCheckId);

  $check = $db->fetchOne($sql);
  $check['created'] = DateTime::createFromFormat('Y-m-d', $check['created']);
  $check['created'] = $check['created']->format("d/m/Y");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Customer - Sharpie's Auto Services</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" media="all" href="css/safetycheck.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
  </head>
  <body>
    <table>
      <tr>
        <td class="logo">
          <img src="images/logo.png" />
          <div class="safetyCheckNumber">
            Safety Check: <?php echo $safetyCheckId; ?>
          </div>
        </td>
        <td class="customerDetails">
          <label>Customer Name:</label> <?php echo $check['firstname'] . " " . $check['lastname']; ?><br />
          <label>Address:</label> <?php echo $check['address']; ?>,<br />
          <?php echo $check['city']; ?>, <?php echo $check['state']; ?> <?php echo $check['postcode']; ?>
        </td>
        <td class="safetyCheckDetails">
          <label>Date:</label> <?php echo $check['created']; ?><br />
          <label>Make:</label> <?php echo $check['make']; ?><br />
          <label>Reg No:</label> <?php echo $check['registration']; ?>
        </td>
      </tr>
      <tr>
        <td class="checklistContainer" colspan="3">
          <table class="checklist">
            <tr>
              <th></th>
              <th>
                OK
              </th>
              <th>
                RFA
              </th>
              <th></th>
              <th>
                OK
              </th>
              <th>
                RFA
              </th>
            </tr>
            <tr>
              <td class="spacer" colspan="6">&nbsp;</td>
            </tr>
            <tr>
              <td class="heading">
                Under Bonnet
              </td><td class="shade"></td><td class="shade"></td>
              <td class="heading">
                Under Body
              </td><td class="shade"></td><td class="shade"></td>
            </tr>
            <tr>
              <td>
                INSPECT FOR SIGNS OF
              </td><td class="shade"></td><td class="shade"></td>
              <td>
                CHECK FOR ANY DETERIORATION
              </td><td class="shade"></td><td class="shade"></td>
            </tr>
            <tr>
              <td>
                DETERIORATION OR LEAKS FROM
              </td><td class="shade"></td><td class="shade"></td>
              <td>
                Tyres
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Engine
              </td><td></td><td></td>
              <td>
                Steering Linkages
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Fluid Reservoirs
              </td><td></td><td></td>
              <td>
                Suspension Joints
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Radiator Hoses
              </td><td></td><td></td>
              <td>
                Exhaust System
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Brake &amp; Clutch Lines
              </td><td></td><td></td>
              <td>
                Universal Joints
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Fuel Lines
              </td><td></td><td></td>
              <td>
                Handbrake Cable
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                CHECK
              </td><td class="shade"></td><td class="shade"></td>
              <td>
                INSPECT FOR FLUID LEAKS
              </td><td class="shade"></td><td class="shade"></td>
            </tr>
            <tr>
              <td>
                Brake Master Cylinder Level
              </td><td></td><td></td>
              <td>
                Fuel Lines
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Clutch Master Cylinder Level
              </td><td></td><td></td>
              <td>
                Shock Absorbers
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Automatic Transmission Fluid Level
              </td><td></td><td></td>
              <td>
                Transmission
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Coolant Hose Condition
              </td><td></td><td></td>
              <td>
                Differential
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Coolant Level
              </td><td></td><td></td>
              <td>
                Engine
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Battery Level
              </td><td></td><td></td>
              <td>
                CHECK TYRE INFLATION (INC. SPARE)
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Battery Security
              </td><td></td><td></td>
              <td>
                CHANGE ENGINE OIL
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Power Steering Level
              </td><td></td><td></td>
              <td>
                LUBRICATE GREASE NIPPLES
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Drive Belts Condition
              </td><td></td><td></td>
              <td>
                REMOVE WHEELS
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Drive Belts Tension
              </td><td></td><td></td>
              <td>
                Rotate Tyres
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Air Cleaner Element
              </td><td></td><td></td>
              <td>
                Clean &amp; Repack FRONT bearings RWD
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Fuel Filter
              </td><td></td><td></td>
              <td>
                Clean &amp; Repack REAR bearings FWD
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                CHANGE RADIATOR COOLANT
              </td><td></td><td></td>
              <td colspan="3"></td>
            </tr>
            <tr>
              <td class="spacer" colspan="6">&nbsp;</td>
            </tr>
            <tr>
              <td class="heading">
                Brake Inspection
              </td><td class="shade"></td><td class="shade"></td>
              <td class="heading">
                Body
              </td><td class="shade"></td><td class="shade"></td>
            </tr>
            <tr>
              <td>
                REMOVE WHEELS, DRUMS &amp; INSPECT
              </td><td class="shade"></td><td class="shade"></td>
              <td>
                CHECK
              </td><td class="shade"></td><td class="shade"></td>
            </tr>
            <tr>
              <td>
                Brake Cylinders
              </td><td></td><td></td>
              <td>
                Head Lights
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Brake Shoes
              </td><td></td><td></td>
              <td>
                Tail Lights
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Brake Pads
              </td><td></td><td></td>
              <td>
                Indicators
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Brake Drums
              </td><td></td><td></td>
              <td>
                Hazard Warning Lights
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Brake Disk
              </td><td></td><td></td>
              <td>
                Brake Lights
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Linkages
              </td><td></td><td></td>
              <td>
                Reversing Lights
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                Lines
              </td><td></td><td></td>
              <td>
                Horn
              </td><td></td><td></td>
            </tr>
            <tr>
              <td>
                ADJUST BRAKES
              </td><td></td><td></td>
              <td>
                Wipers
              </td><td></td><td></td>
            </tr>
            <tr>
              <td colspan="3">
              <td>
                Washers
              </td><td></td><td></td>
            </tr>
            <tr>
              <td class="heading">
                Road Test
              </td><td class="shade"></td><td class="shade"></td>
              <td colspan="3"></td>
            </tr>
            <tr>
              <td>
                CHECK
              </td><td class="shade"></td><td class="shade"></td>
              <td colspan="3"></td>
            </tr>
            <tr>
              <td>
                Squeaks &amp; Rattles
              </td><td></td><td></td>
              <td colspan="3"></td>
            </tr>
            <tr>
              <td>
                Drive Train Noises
              </td><td></td><td></td>
              <td colspan="3"></td>
            </tr>
            <tr>
              <td>
                Steering Operation
              </td><td></td><td></td>
              <td colspan="3"></td>
            </tr>
            <tr>
              <td>
                Brake Operation
              </td><td></td><td></td>
              <td colspan="3"></td>
            </tr>
            <tr>
              <td>
                Engine Performance
              </td><td></td><td></td>
              <td colspan="3"></td>
            </tr>
          </table>
          <div class="legend">RFA = Requires Further Attention</div>
        </td>
      </tr>
      <tr>
        <td class="additionalWork" colspan="3">
          <label>Additional Work Required:</label>
          <p class="dotted"></p>
          <p class="dotted"></p>
          <p class="dotted"></p>
        </td>
      </tr>
      <tr>
        <td class="comments" colspan="3">
          <label>Technicians Comments:</label>
          <p class="dotted"></p>
          <p class="dotted"></p>
          <p class="dotted"></p>
        </td>
      </tr>
    </table>
    <script>
      /*
      window.print();
      setTimeout(function() { window.close(); }, 100);
      */
    </script>
  </body>
</html>
