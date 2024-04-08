<?php

////// Write the Database connection code below (Q1)
$servername = 'localhost'; //for XAMPP we use localhost
$username = 'root'; //default username in XAMPP
$password = ''; //default password in XAMPP
$dbname = 'project_3_db'; //Change this to whatever database name you set in PHPmyAdmin

$link = mysqli_connect($servername, $username, $password, $dbname);    

if(mysqli_connect_error()){
    die("Database connection unsuccessful and exiting program");
}

else {
    echo "Database connection successful";
}

///////// (Q1 Ends)

$operation_val = '';
if (isset($_POST['operation']))
{
    $operation_val = $_POST["operation"];
    #echo $operation_val;
}

function getId($link) {
    
    $queryMaxID = "SELECT MAX(id) FROM fooditems;";
    $resultMaxID = mysqli_query($link, $queryMaxID);
    $row = mysqli_fetch_array($resultMaxID, MYSQLI_NUM);
    return $row[0]+1;
}



if (isset($_POST['updatebtn']))
{//// Write PHP Code below to update the record of your database (Hint: Use $_POST) (Q9)
//// Make sure your code has an echo statement that says "Record Updated" or anything similar or an error message
    // Retrieve values from the form
    $id = $_POST['update_id']; 
    $amount = $_POST['update_amount'];
    $calories = $_POST['update_calories']; 
    // Update query
    $queryUpdate = "UPDATE fooditems SET amount=$amount, calories=$calories WHERE id=$id";

    // Perform the update
    if (mysqli_query($link, $queryUpdate)) {
        echo " Record Updated";
    } else {
        echo "Error updating record: " . mysqli_error($link);
    }
    




//// (Q9 Ends)
}


if (isset($_POST['insertbtn']))
{//// Write PHP Code below to insert the record into your database (Hint: Use $_POST and the getId() function from line 25, if needed) (Q10)
//// Make sure your code has an echo statement that says "Record Saved" or anything similar or an error message
     // Retrieve form data
     $item = isset($_POST['item']) ? $_POST['item'] : '';
     $amount = isset($_POST['amount']) ? $_POST['amount'] : '';
     $unit = isset($_POST['unit']) ? $_POST['unit'] : '';
     $calories = isset($_POST['calories']) ? $_POST['calories'] : '';
     $protein = isset($_POST['protein']) ? $_POST['protein'] : '';
     $carbohydrate = isset($_POST['carbohydrate']) ? $_POST['carbohydrate'] : '';
     $fat = isset($_POST['fat']) ? $_POST['fat'] : '';

     $id = getId($link);
     
 
     // Construct the INSERT query
     $insertQuery = "INSERT INTO fooditems (id, item, amount, unit, calories, protein, carbohydrate, fat) VALUES ('$id', '$item', '$amount', '$unit', '$calories', '$protein', '$carbohydrate', '$fat')";
 
     // Perform the INSERT query
     if(mysqli_query($link, $insertQuery)) {
         echo " Record Saved successfully";
     } else {
         echo "Error saving record: " . mysqli_error($link);
     }



//// (Q10 Ends)
}


if (isset($_POST['deletebtn']))
{//// Write PHP Code below to delete the record from your database (Hint: Use $_POST) (Q11)
//// Make sure your code has an echo statement that says "Record Deleted" or anything similar or an error message
   // Retrieve ID from form data
   $delete_id = isset($_POST['delete_id']) ? $_POST['delete_id'] : '';

   // Construct the DELETE query
   $deleteQuery = "DELETE FROM fooditems WHERE id = $delete_id";

   // Perform the DELETE query
   if(mysqli_query($link, $deleteQuery)) {
       echo " Record Deleted successfully";
   } else {
       echo "Error deleting record: " . mysqli_error($link);
   }



//// (Q11 Ends)
}



?>


<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>
            $(document.ready(function() {
                $("#testbtn").click(function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: 'p3.php',
                        type: 'POST',
                        data: {
                            'operation_val' : $("#operation_val").val(),
                        },
                        success: function(data, status) {
                            $("#test").html(data)
                        }
                    });
                });
                $("#insertbtn").click(function(e) {
                    echo "here0";
                    e.preventDefault();

                    $.ajax({
                        url: 'p3.php',
                        type: 'POST',
                        data: {
                            'operation_val' : $("#operation_val").val(),
                        },
                        success: function(data, status) {
                            echo "here";
                        }
                    });
                });
            }));
            
        </script>
        <link rel="stylesheet" href="p3.css">
    </head>

    <body>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="cars">Choose an operation:</label>
            <select name="operation" id="operation" onchange="this.form.submit()">
                <option value="0" <?php print ($operation_val == 0) ? "selected" : '' ;?>><b>Select Operation</b></option>
                <option value="1" <?php print ($operation_val == 1) ? "selected" : '' ;?>>Show</option>
                <option value="2" <?php print ($operation_val == 2) ? "selected" : '' ;?>>Update</option>
                <option value="3" <?php print ($operation_val == 3) ? "selected" : '' ;?>>Insert</option>
                <option value="4" <?php print ($operation_val == 4) ? "selected" : '' ;?>>Delete</option>
            </select></br></br>
            <?php


            
            $query = "SELECT * FROM fooditems;";
            if ($operation_val == 1) {
                if ($result = mysqli_query($link, $query)) {
                    $fields_num = mysqli_num_fields($result);
                    echo "<table class=\"customTable\"><tr>";
                    for ($i = 0; $i < $fields_num; $i++) {
                        $field = mysqli_fetch_field($result);
                        if ($i > 0) {
                            echo "<th>{$field->name}</th>";
                        } else {
                            echo "<th>id</th>";
                        }
                    }
                    echo "</tr>";

                    // Fetching and displaying rows
                    while ($row = mysqli_fetch_row($result)) {
                        echo "<tr>";
                        foreach ($row as $key => $value) {
                            echo "<td>" . $value . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";

                    // Free result set
                    mysqli_free_result($result);
                } else {
                    echo "Error executing query: " . mysqli_error($link);
                }
            }
            ?>


            


            <div id="div_update" runat="server" class=<?php if($operation_val == 2) {echo "display-block";} else {echo "display-none";}?>>
            <!--Create an HTML table below to enter ID, amount, and calories in different text boxes. This table is used for updating records in your table. (Q3) --->
            <table>
                <tr>
                    <td>ID:</td>
                    <td><input type="text" name="update_id" id="update_id"></td>
                </tr>
                <tr>
                    <td>Amount:</td>
                    <td><input type="text" name="update_amount" id="update_amount"></td>
                </tr>
                <tr>
                    <td>Calories:</td>
                    <td><input type="text" name="update_calories" id="update_calories"></td>
                </tr>
            </table>




            <!--(Q3) Ends --->
            



            <!--Create a button below to submit and update record. Set the name and id of the button to be "updatebtn"(Q4) --->
            <button type="submit" name="updatebtn" id="updatebtn">Update Records</button>

            
            <!--(Q4) Ends --->
            </div>



            <div id="div_insert" runat="server" class=<?php if($operation_val == 3) {echo "display-block";} else {echo "display-none";}?>>
            <!--Create an HTML table below to enter item, amount, unit, calories, protein, carbohydrate and fat in different text boxes. This table is used for inserting records in your table. (Q5) --->
                <!-- (Q5) Create an HTML table below to enter item, amount, unit, calories, protein, carbohydrate, and fat in different text boxes -->
                <table>
                    <tr>
                        <th align="left">Item</th>
                        <td><input type="text" name="item" placeholder="Enter Item"></td>
                    </tr>
                    <tr>
                        <th align="left">Amount</th>
                        <td><input type="text" name="amount" placeholder="Enter Amount"></td>
                    </tr>
                    <tr>
                        <th align="left">Unit</th>
                        <td><input type="text" name="unit" placeholder="Enter Unit"></td>
                    </tr>
                    <tr>
                        <th align="left">Calories</th>
                        <td><input type="text" name="calories" placeholder="Enter Calories"></td>
                    </tr>
                    <tr>
                        <th align="left">Protein</th>
                        <td><input type="text" name="protein" placeholder="Enter Protein"></td>
                    </tr>
                    <tr>
                        <th align="left">Carbohydrate</th>
                        <td><input type="text" name="carbohydrate" placeholder="Enter Carbohydrate"></td>
                    </tr>
                    <tr>
                        <th align="left">Fat</th>
                        <td><input type="text" name="fat" placeholder="Enter Fat"></td>
                    </tr>
                </table>





            <!--(Q5) Ends --->




            <!--Create a button below to submit and insert record. Set the name and id of the button to be "insertbtn"(Q6) --->
            <button type="submit" name="insertbtn" id="insertbtn">Insert Record</button>

            <!--(Q6) Ends --->
            </div>

            <div id="div_delete" runat="server" class=<?php if($operation_val == 4) {echo "display-block";} else {echo "display-none";}?>>
            <!--Create an HTML table below to enter id a text box. This table is used for deleting records from your table. (Q7) --->
            <table>
                <tr>
                    <th>ID</th>
                </tr>
                <tr>
                    <td><input type="text" name="delete_id" placeholder="Enter ID"></td>
                </tr>
            </table>


            <!--(Q7) Ends--->    




            <!--Create a button below to submit and delete record. Set the name and id of the button to be "deletebtn"(Q8) --->
            <button type="submit" name="deletebtn" id="deletebtn">Delete Record</button>

            <!--(Q8) Ends --->
            </div>
            
        </form>

    </body>




</html>



