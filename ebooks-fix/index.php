<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de-DE" lang="de-DE">
<head>
  <meta charset="utf-8" />
  <title>Fix MARC file</title>
  <style>
    .label {
      display: inline-block;
      width:10em;
      margin-right: 2em;
    }
    .button {
      font-weight: bold;
      font-size: 200%;
    }
    input[type="file"] {
      width: 50em;
    }
  </style>
</head>
<body>
  <form enctype="multipart/form-data" action="fix.php" method="POST">
    <h1>MARC-Datei bereinigen</h1>
    
    <label class="label" for="marc_file">MARC Datei laden</label>
    <input type="file" name="marc_file" id="marc_file"/>

    <p/>

    <label class="label" for="sigel">Sigel</label><input type="text" name="sigel" id="sigel"/><span style="margin-left: 2em;">(z.B. f&uuml;r Nomos: ZDB-18-NOL oder ZDB-18-NSW. Standard: leer)</span>
    <p/>
    <input class="button" type="submit" value="Bereinigen"></input>
    <p/>
    <br/>
    <p/>
    ebooks Fixes: Git-Hash: <?= file_get_contents('githash.txt'); ?> 
  </form>
</body>
</html>
