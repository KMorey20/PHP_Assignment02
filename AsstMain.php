<?php
    //http://localhost/MoreyKaraCodingAsst/AsstMain.php
    require_once("AsstInclude.php");

    //FUNCTIONS --------------------------------------------------------------------------------------------->
    function DisplayMainForm()
    {
        echo "<form action=? method=post>";
            DisplayButton("Create_Table_Button", "Create Table", "button_create-table.png", "Create Table Button", 35, 128);
            DisplayButton("Add_Record_Button", "Add Record", "button_add-record.png", "Add Record Button", 35, 128);
            DisplayButton("Display_Data_Button", "Display Data", "button_display-data.png", "Display Data Button", 35, 128);
        echo "</form>";
    };

    //CREATING THE DATABASE FOR THE SHOWS
    function CreateTableForm($mysqlObj, $TableName)
    {
        /**Dropping the Table*/
        $dropQuery = "DROP TABLE IF EXISTS $TableName";
        $dropObj = $mysqlObj->prepare($dropQuery);
        if(!$dropObj){
            echo "<p>Error: cannot drop table" . $TableName . "</p>";
            return;
        }
        $dropObj->execute();
        /**End Drop Table */

        /**Create Table*/
        $showName = "ShowName VARCHAR(50) PRIMARY KEY"; //User Entered
        $performanceDateAndTime = "performanceDateAndTime DATETIME"; //User Entered
        $nbrTickets = "nbrTickets INT"; //User Entered
        $ticketPrice = "ticketPrice DECIMAL(10, 2)"; //User Entered
        $totalCost = "totalCost DECIMAL(10, 2)"; //Site Calcualated

        $SQLStatement = "CREATE TABLE $TableName ($showName, 
                $performanceDateAndTime, $nbrTickets, $ticketPrice, $totalCost)";

        $stmtObj = $mysqlObj->prepare($SQLStatement);
        $CreateResult = $stmtObj->execute();

        //Displaying the results of the create table
        if ($CreateResult) {
            echo "<p>Successfully created table " . $TableName . "</p>";
        } else {
            echo "<p>Error: Cannot create table " . $TableName . "</p>";
            return;
        }
        /**End of Create Table*/

        echo "<form action=? method=post>";
            DisplayButton("Home_Button", "Home Link", "button_home.png", "Return Home Button", 35, 95);
        echo "</form>";
      };

    //FORM FOR ADDING RECORDS TO TABLE
    function AddRecordForm(){
        echo "<form action = ? method=post>";

            echo "<div class=\"FormArea\">";
                echo "<div class=\"DataPair\">";
                    DisplayLabel("Showing's Name: ");
                    DisplayTextbox("text", "showName", 10, 'InputStyles', "Play Name", 0, true);
                echo "</div>";

                echo "<div class=\"DataPair\">";
                    DisplayLabel("Performance Date: ");
                    echo '<input type="date" name="performanceDate" class="InputStyles">';
                echo "</div>";

                echo "<div class=\"DataPair\">";
                    DisplayLabel("Performance Time: ");
                    echo '<input type="time" name="performanceTime" class="InputStyles">';
                echo "</div>";

                echo"<div class=\"DataPair\">";
                    DisplayLabel("Number of Tickets: ");
                    DisplayTextbox("number", "nbrTickets", .2, 'InputStyles', 1, 1);
                echo "</div>";

                echo "<div class=\"DataPair\">";
                    DisplayLabel("Ticket Price: ");
                    echo "
                        <input type=\"radio\" name=\"ticketPrice\" value=\"100\" checked = \"checked\"> 100 
                        <input type=\"radio\" name=\"ticketPrice\" value=\"150\" > 150
                        <input type=\"radio\" name=\"ticketPrice\" value=\"200\" > 200
                        </div>
                    ";

                echo "<div class=\"ButtonsArea\">";
                    DisplayButton("Save_Record_Button", "Save Record", "button_save-record.png", "Save Button", 35, 128);
                    DisplayButton("Home_Button", "Home Link", "button_home.png", "Return Home Button", 35, 95);
                echo "</div>";
            echo "</div>";
        echo "</form>";
    };

    //ADDING RECORDS TO TABLE USING FORMS
    function AddRecordToTable($mysqlObj, $TableName)
    {
        //Check if all required form data was submitted
        if (isset($_POST['showName'], $_POST['performanceDate'], $_POST['performanceTime'],
            $_POST['nbrTickets'], $_POST['ticketPrice'])) {

            $showName = $_POST['showName'];
            $performanceDate = $_POST['performanceDate'];
            $performanceTime = $_POST['performanceTime'];
            $performanceDateAndTime = $performanceDate . ' ' . $performanceTime;

            /* calculate total cost */
            $nbrTickets = $_POST['nbrTickets'];
            $ticketPrice = $_POST['ticketPrice'];
            $initialPrice = $ticketPrice * $nbrTickets;
            $tax = $initialPrice * 0.13;
            $totalCost = $tax + $initialPrice;
            /* end calculate total cost */

            $addShow = $mysqlObj->prepare("INSERT INTO $TableName (showName, performanceDateAndTime, nbrTickets, ticketPrice, totalCost)
                                        VALUES (?, ?, ?, ?, ?)");
            $addShow->bind_param("ssidd", $showName, $performanceDateAndTime, $nbrTickets, $ticketPrice, $totalCost);


            if ($addShow->execute()) {
                echo "Successfully Added " . $showName;
            } else {
                echo "Error: Could not add " . $showName . " Error: " . $addShow->error;
            }

            echo "<form action=? method=post>";
            DisplayButton("Home_Button", "Home Link", "button_home.png", "Return Home Button", 35, 95);
            echo "</form>";
        }
        else
        {
            echo "Error: Not all required form data was submitted.";
        }
    }

    //SHOW ALL THE ADDED RECORDS
    function ShowDataForm($mysqlObj, $TableName) {
        $allShows = $mysqlObj->query("SELECT * FROM $TableName ORDER BY ticketPrice ASC, nbrTickets DESC");
    
        if ($allShows) {
            echo "<table><tr><th>Broadway Shows</th></tr>
                <tr>
                    <th>Show Name </th>
                    <th>Performance Date and Time </th>
                    <th>Number of Tickets </th>
                    <th>Ticket Price </th>
                    <th> Total Price </th>
                 </tr>";
    
            while ($show = $allShows->fetch_assoc()) {
                echo "<tr>
                            <td>" . $show['ShowName'] . "</td>
                            <td>" . $show['performanceDateAndTime'] . "</td>
                            <td>" . $show['nbrTickets'] . "</td>
                            <td>" . $show['ticketPrice'] . "</td>
                            <td>" . $show['totalCost'] . "</td>
                       </tr>";
            }
            echo "</table>";
    
            // Count the number of records
            $numRecords = $allShows->num_rows;
            echo "<p> Number of Records to Date: $numRecords</p>";
        } else {
            echo "Error: Could not retrieve the shows or no shows found. ";
        }
    
        echo "<form action=? method=post>";
        DisplayButton("Home_Button", "Home Link", "button_home.png", "Return Home Button", 35, 95);
        echo "</form>";
    }
    
    //Main --------------------------------------------------------------------------------------------------->
    date_default_timezone_set ('America/Toronto');
    $mysqlObj = CreateConnectionObject();
    $TableName = "BroadwayShows";

    WriteHeaders("Kara Morey Assignment 1");

    if(isset($_POST['Create_Table_Button']))
        CreateTableForm($mysqlObj, $TableName);
    elseif(isset($_POST['Add_Record_Button']))
        AddRecordForm();
            elseif(isset($_POST['Save_Record_Button']))
                AddRecordToTable($mysqlObj, $TableName);
                    elseif(isset($_POST['Display_Data_Button']))
                        ShowDataForm($mysqlObj, $TableName);
                            else
                                DisplayMainForm();
    CloseConnection($mysqlObj);
    WriteFooters();
?>