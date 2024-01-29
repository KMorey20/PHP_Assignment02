<?php
function WriteHeaders($Heading = "Welcome", $Titlebar="MySite")
{
    echo "
        <link rel=\"stylesheet\" type=\"text/css\" href=\"AsstStyle.css\"/>
        <!doctype html>
        <html lang = \"en\">
        <head>
            <meta charset = \"UTF-8\">
            <title>$Titlebar</title>\n
        </head>
        <body>\n
        <h1>$Heading</h1>\n
        ";
}

function DisplayLabel($prompt)
{
    echo "<label>".$prompt."</label>";
}

function DisplayTextbox($Type, $Name, $Size, $Class = '', $Value = 0, $Min, $Focus=false) 
{
    echo '<input type="' . $Type . '"
    name="' . $Name . '"
    size="' . $Size . '"
    value="' . $Value . '"
    class="' . $Class . '"
    min="' . $Min . '"';

    if ($Focus) {
        echo ' autofocus=""';
    }

    echo ">";
}

function DisplayContactInfo()
{
    echo "<footer> \"Questions? Comments? u
              <a href = \"mailto:kmorey20@student.sl.on.ca\"> kmorey20@student.sl.on.ca</a>\"</footer>";
}

function DisplayImage($Filename, $Alt, $Height, $Width)
{
    echo "<img src='" . $Filename . "' alt='" . $Alt . "' style='width: " . $Width . 
        "px; height: " . $Height . "px; border: 0;'>";
}

function DisplayButton($ButtonName, $Text, $Path = null, $Alt = null, $Height = 0, $Width = 0)
{
    echo "<button class= \"ButtonStyles\" name='" . $ButtonName . "'>";
    if ($Path) {
        echo DisplayImage($Path, $Alt, $Height, $Width);
    } else {
        echo $Text;
    }
    echo "</button>";
}

function WriteFooters()
{
    echo "</body>\n";
    echo "</html>\n";

    DisplayContactInfo();
}

function CreateConnectionObject(){
    $fh = fopen('auth.txt','r');
    $Host = trim(fgets($fh));
    $UserName = trim(fgets($fh));
    $Password = trim(fgets($fh));
    $Database = trim(fgets($fh));
    $Port = trim(fgets($fh));
    fclose($fh);
    
    $mysqlObj = new mysqli($Host, $UserName, $Password, $Database, $Port);

    if ($mysqlObj->connect_errno != 0) 
    {
        echo "<p>Connection failed. Unable to open database $Database. Error: "
            . $mysqlObj->connect_error . "</p>";
        exit;
    }
    
    return $mysqlObj;
}

function CloseConnection(&$mysqlObj)
{
    $mysqlObj->close();
}

?>