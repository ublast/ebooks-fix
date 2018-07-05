<?php
  if(empty($_FILES['marc_file'])) {
    echo "Filename not given.";
  } elseif (!file_exists($_FILES['marc_file']['tmp_name'])) {
    echo "File not found.";
  } else {
    $inputfile = basename( $_FILES['marc_file']['name']);

    $path_parts = pathinfo($inputfile);

    $outputfile = $path_parts['filename']."_corr.".$path_parts['extension'];

    $tmp_input = $_FILES['marc_file']['tmp_name'];

    $tmp_output = tempnam ("/tmp", "yyy");
    
    $sigel = $_POST['sigel'];
    
    if (empty($sigel)) {
      $sigel = 'leer';
    } else {
      $outputfile = $path_parts['filename']."_".$sigel.".".$path_parts['extension'];
    }

    #$cmd = "/var/www/apps/ebooks/perl/ebookpaket.pl -b -i \"$tmp_input\" -o \"$tmp_output\" -s \"$sigel\" -f /var/www/apps/ebooks/fixes/ebook_noinclude.fix";
    $cmd = "/var/www/apps/ebooks/perl/ebookpaket.pl -b -i \"$tmp_input\" -o \"$tmp_output\" -s \"$sigel\"";

    #$rv = shell_exec($cmd);
    $op = array();

    $rv = exec("$cmd 2>&1", $op, $rc);

    $stacktrace = "";
    foreach ($op as $line) {
      $stacktrace .= $line ."\n";
    };
    unset($line);

    if ($rc == 0 && file_exists($tmp_output)) {

      header('Content-Type: application/octet-stream');
      header("Content-Transfer-Encoding: Binary"); 
      header("Content-disposition: attachment; filename=\"" . $outputfile . "\"");

      readfile($tmp_output);

      unlink($tmp_output);
      # no whitespace between last code-byte (}) and php-endtag, else additional whitespace in output file!
    } else {?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de-DE" lang="de-DE">
  <head>
    <meta charset="utf-8" />
    <title>Catmandu::MARC: Error when processing input file</title>
    <style>
      pre {
        padding: 0px;
        margin: 0px;
      }

      table {
        border-collapse: collapse;
      }
      table, th, td {
        border: 1px solid black;
      }
      th {
        text-align: left;
      }
      th, td {
        vertical-align: top;
      }
      th, td {
        padding: 0.25em 0.5em;
      }
    </style>
  </head>
  <body>
  <h1>Fehler beim Verarbeiten der MARC-Datei</h1>
  <table>
    <tr>
      <th>Eingabedatei</th>
      <td><?= $inputfile ?></td>
    </tr>
    <tr>
      <th>Ausgabedatei</th>
      <td><?= $outputfile ?></td>
    </tr>
    <tr>
      <th>Sigel</th>
      <td><?= $sigel ?></td>
    </tr>
    <tr>
      <th>Kommando</th>
      <td><pre><?= $cmd ?></pre></td>
    </tr>
    <tr>
      <th>R&uuml;ckgabewert</th>
      <td><pre><?= $rc ?></pre></td>
    </tr>
    <tr>
      <th>Fehler</th>
      <td><pre><?= $rv ?></pre></td>
    </tr>
    <tr>
      <th>Stacktrace</th>
      <td><pre><?= $stacktrace ?></pre></td>
    </tr>
  </table>
  </body>
</html>
<?php
}}?>
