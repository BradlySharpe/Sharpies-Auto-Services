<?php

  require('Helpers.php');
  require('DBase.php');
  require('Toro.php');

  // Require all files in Logic folder
  foreach (glob('Logic' . DIRECTORY_SEPARATOR . '*.php') as $file)
    require($file);

  ToroHook::add("404",  function() {
    new Error("Route not found");
  });

  Toro::serve(array(
    '/customer' => "Customer",
    '/customer/:number' => "Customer",
    '/customer/:number/cars' => "Car",
    '/customer/:number/cars/:number' => "Car",
    '/cars' => "Car",
    '/safetycheck' => "SafetyCheck",
    '/safetycheck/:number' => "SafetyCheck",
    '/service' => "Service",
    '/service/:number' => "Service",
    '/invoice' => "Invoice",
    '/invoice/:number' => "Invoice",
    '/item' => "Item",
    '/item/:number' => "Item",
    '/detail' => "Detail",
    '/detail/:number' => "Detail",
    '/payment' => "Payment",
    '/payment/:number' => "Payment"
  ));
