<?php
  require('DBase.php');
  require('Toro.php');
  require('Helpers.php');

  // Require all files in Logic folder
  foreach (glob('Logic' . DIRECTORY_SEPARATOR . '*.php') as $file)
    require($file);

  ToroHook::add("404",  function() {
    new Error("Route not found");
  });

  Toro::serve(array(
    '/customer' => "Customer",
    '/customer/:number' => "Customer"
  ));
