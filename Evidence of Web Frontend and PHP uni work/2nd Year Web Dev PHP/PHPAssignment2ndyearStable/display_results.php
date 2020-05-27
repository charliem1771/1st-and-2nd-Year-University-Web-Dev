<?php
require_once "header.php";
//Echong out the header in HTML
echo"<h3>Static survey results</h3>";

    //Boiler plate connection code with a error check
    $connection = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

    if(!$connection)
    {
        die("Connection failed" . $mysqli_connect_error);
    }

    $query = "";
    $result = "";
    //Getting all responses from the static survey table
    $query = "SELECT * FROM staticsurvey WHERE username IS NOT NULL";

    $result = mysqli_query($connection,$query);
    //Getting the number of rows since there is no priamry key in the static survey table the number of rows shows the true number of rows in the table
    $n = mysqli_num_rows($result);
    //Creating all of the table headings
     echo <<<_END
        <table>
            <th>Survey Results</th>
            <tr>
            <th>Username</th>
            <th>Favourite Colour</th>
            <th>Tell us about yourself</th>
            <th>Male</th>
            <th>Female</th>
            <th>DOB</th>
            <th>Favourite Animal</th>
            <th>Lion</th>
            <th>Tiger</th>
            </tr>
_END;
    if($n > 0)
    {
        //Looping through every row in the table 
        for ($i = 0; $i < $n; $i++) 
        {
            //Storing the results of the query in a variable
            $row = mysqli_fetch_assoc($result);

            //Putting all of the responses and usernames in the correct place
            echo <<<_END
                <tr>
                <td>{$row['username']}</td>
                <td>{$row['resp1']}</td>
                <td>{$row['resp2']}</td>
                <td>{$row['resp3']}</td>
                <td>{$row['resp4']}</td>
                <td>{$row['resp5']}</td>
                <td>{$row['resp6']}</td>
                <td>{$row['resp7']}</td>
                <td>{$row['resp8']}</td>
_END;
        }
    }
    /*Grabbing the values from all reponses that are equl to 1 so I can use them to plot true reponses to questions asked 
    with radio buttons on the graph */
    $query = "SELECT resp3 FROM staticsurvey WHERE resp3 = 1";
    $result = mysqli_query($connection,$query);
    $males = mysqli_num_rows($result);

    $query = "SELECT resp4 FROM staticsurvey WHERE resp4 = 1";
    $result = mysqli_query($connection,$query);
    $females = mysqli_num_rows($result);

    $query = "SELECT resp7 FROM staticsurvey WHERE resp7 = 1";
    $result = mysqli_query($connection,$query);
    $lions = mysqli_num_rows($result);

    $query = "SELECT resp8 FROM staticsurvey WHERE resp8 = 1";
    $result = mysqli_query($connection,$query);
    $tigers = mysqli_num_rows($result);
         if ($n > 0) {
            // create a HEREDOC to hold the Google Charts script
           echo <<<_END
                    <!--Load the AJAX API-->
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                
                      //Load visualation API and corechart
                      google.charts.load('current', {'packages':['corechart', 'controls']});
                      //setting callback to run
                      google.charts.setOnLoadCallback(drawDataChart);
                
                      //Function to create the data charts
                      function drawDataChart() 
                      {
                        //Creating the datatable
                        var data = new google.visualization.DataTable();
                        //adding two columns to the table 
                        data.addColumn('string', 'Question');
                        data.addColumn('number', 'Response');
                        //adding rows
                        data.addRows([
                            
_END;
            //echoing in all of the respones using $n for the total of all repsonses the rest of the variables only add values that are true       
            echo "['Total Responses',{$n}],['Number of Males',{$males}],['Number of Females',{$females}],['Lion beats Tiger',{$lions}],['Tiger beats Lion',{$tigers}]";

 echo <<<_END
  
                        ]);
                                       
                        //Setting the chart options
                        var options = {'title':'Survey Responses',
                                       'width':600,
                                       'height':300,
                                       legend: {position: "left"},
                                       };
                                       
                        //Instaniating and drawing the crat in the div bar_chart_div
                        var chart = new google.visualization.BarChart(document.getElementById('bar_chart_div'));
                        chart.draw(data, options);
                        
                        //Creates a dashboard which the range slider and the barchart are drawn on, along with grabbing a div to place it in
                        var dashboard = new google.visualization.Dashboard(
                        document.getElementById('dashboard_div'));
                    
                        //Creating the rangeslider and passing some values to it
                               var slider = new google.visualization.ControlWrapper({
                              'controlType': 'NumberRangeFilter',
                              'containerId': 'filter_div',
                              'options': {
                              'filterColumnLabel': 'Response'
                              }
                            });
                        
                        //set pie chart options
                        var pieChart = new google.visualization.ChartWrapper({
                               'chartType': 'PieChart',
                               'containerId': 'pie_chart_div',
                               'options': {
                                   'title':'Responses',
                                   'width': 600,
                                   'height': 300,
                                   'pieSliceText': 'value',
                                    'legend': 'right'
                                }
                        }); 
                        
                        //Binding the slider and piechart to the dashboard and drawing them
                        dashboard.bind(slider, pieChart);
                        dashboard.draw(data);
                        
                      }
                    </script>
                  </head>
                  <body>
                    <!--Table styling to keep the page neat -->
                    <table bgcolor='#ffffff' cellspacing='0' cellpadding='2'><tr>
                    <!--Div that holds the bar charts-->
                    <td>
                        <div id="bar_chart_div"></div>
                    </td>
                    
                    <!-- divs to hold the range slider,dashboard and piechart -->
                    <td><div id="dashboard_div">
                        <div id="filter_div"></div>
                        <div id="pie_chart_div"></div>
                    </div></td>
                    </tr></table>                
_END;


        }

        // if anything else happens indicate a problem
        else {
            echo "No data available to plot<br>";
        }

// finish off the HTML for this page:
require_once "footer.php"
?>