<?php
//http://localhost//KaraInclude.php
function WriteHeaders($Heading = "Welcome", $Titlebar="MySite")
{
    echo "
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

function DisplayTextbox($Type, $Name, $Size, $Value, $Boolean)
{
    if($Boolean == true){
        echo "<input type = " . $Type . 
        " name = " . $Name .
        " Size = " . $Size . 
        " value =" . $Value . ">";}
    else
        $Boolean == false;
}

function DisplayContactInfo()
{
    echo "<footer> \"Questions? Comments? 
              <a href = \"mailto:kmorey20@student.sl.on.ca\"> kmorey20@student.sl.on.ca</a>\"</footer>";
}

function DisplayImage($Filename, $Alt, $Height, $Width)
{
    echo "<img scr = " . $Filename . " alt = " . $Alt . " style = " . $Width . $Height . ">
          </img>";
}

function DisplayButton($ButtonName, $Text)
{
    echo "<button name = " . $ButtonName . ">  <\button>";
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

?>