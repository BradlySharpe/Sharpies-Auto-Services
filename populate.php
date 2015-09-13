<?php
  $populate = 1;
  if ($populate) {
    echo "Refreshing Database...\n";
    $con = @mysql_connect('localhost', 'autoservice', 'password') or
      // User must have following permissions:
      //  SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE ROUTINE, EXECUTE
      die('Error connecting to MySQL server: ' . mysql_error());

    $lines = file('database.sql');
    $templine = '';
    $delimiter = ';';
    foreach ($lines as $line)
    {
      if ('--' == substr($line, 0, 2) || '' == $line) {
        echo "  $line";
        continue;
      }

      $matches = [];
      preg_match('/^\s*delimiter\s+(.+)$/i', $line, $matches);
      if ($matches && 2 == count($matches)) {
        $delimiter = $matches[1];
        continue;
      }

      $templine .= $line;
      if ($delimiter == substr(trim($line), -strlen($delimiter), strlen($delimiter)))
      {
        $query = $templine;
        $pos = strrpos($query, $delimiter);
        if (false != $pos)
          $query = substr_replace($query, '', $pos, strlen($delimiter));
        if (!mysql_query($query)) {
          echo "\n\nError re-populating database (delimiter: $delimiter):\n";
          echo "Query:\n$query\n";
          echo "Message:\n\t" . mysql_error();
          die();
        }
        $templine = '';
      }
    }

    mysql_close($con);
    unset($con);

    echo "\n\nDatabase Populated!\n\n";
  }

  require('Tests.php');
