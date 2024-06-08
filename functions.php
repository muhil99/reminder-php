<?php
include_once "dbConfig.php";

/*
 * Load function based on the Ajax request
 */
if (isset($_POST["func"]) && !empty($_POST["func"])) {
    switch ($_POST["func"]) {
        case "getCalender":
            getCalender($_POST["year"], $_POST["month"]);
            break;
        case "getEvents":
            getEvents($_POST["date"]);
            break;
        case "getEventsRight":
            getEventsRight($_POST["date"]);
            break;
        case "getAnotherEventsTopBar":
            getAnotherEventsTopBar($_POST["date"]);
            break;
        default:
            break;
    }
}

/*
 * Generate event calendar in HTML format
 */
function getCalender($year = "", $month = "")
{
    $dateYear = $year != "" ? $year : date("Y");
    $dateMonth = $month != "" ? $month : date("m");
    $date = $dateYear . "-" . $dateMonth . "-01";
    $currentMonthFirstDay = (date("N", strtotime($date)) % 7) + 1;

    $totalDaysOfMonth = cal_days_in_month(CAL_GREGORIAN, $dateMonth, $dateYear);
    $totalDaysOfMonthDisplay =
        $currentMonthFirstDay == 1
            ? $totalDaysOfMonth
            : $totalDaysOfMonth + ($currentMonthFirstDay - 1);
    $boxDisplay = $totalDaysOfMonthDisplay <= 35 ? 35 : 42;

    $prevMonth = date("m", strtotime("-1 month", strtotime($date)));
    $prevYear = date("Y", strtotime("-1 month", strtotime($date)));
    $totalDaysOfMonth_Prev = cal_days_in_month(
        CAL_GREGORIAN,
        $prevMonth,
        $prevYear
    );
    // Get the month name
    $monthName = date("F", strtotime($date));

    // Construct the title bar HTML
    $titleBarHTML =
        '
	<div class="calendar-title">
		
	
		<div class="dropdowns">
			<div class="calendar-year-month-filters__months">
				<select class="month-dropdown">
					' .
        getMonthList($dateMonth) .
        '
				</select>
			</div>
	
			<div class="calendar-year-month-filters__years">
				<select class="year-dropdown">
					' .
        getYearList($dateYear) .
        '
				</select>
			</div>
		</div>

		<div class="calendar-year-month-filters">
			<div class="calendar-year-month-filters__months">
				<h1>' .
        $monthName .
        '</h1>
			</div>
			<div class="calendar-year-month-filters__years">
				<h1>' .
        $dateYear .
        '</h1>
			</div>
		</div>     
   



        <!-- Today button -->
        <div class="today-button">
        <button class="date-btn" onclick="goToToday()"> ' .
        date("d/m/y") .
        '</button>
    </div>

	 <div class="country-state-dropdowns">
         <select class="country-dropdown" onchange="updatePage(this)">
             <option value="" >Select Country</option>
             <option value="India" selected  >India</option>
             <option value="dubai">Dubai</option>
             <option value="USA" >USA</option>
             <option value="UK">UK</option>
             <option value="Australia">Australia</option>
             <option value="Canada">Canada</option>
         </select>
     </div>
	
		<div class="calendar-navigation">
			<a href="javascript:void(0);" onclick="getCalendar(\'calendar_div\', \'' .
        date("Y", strtotime($date . " - 1 Month")) .
        '\', \'' .
        date("m", strtotime($date . " - 1 Month")) .
        '\');"><</a>
			<a href="javascript:void(0);" onclick="getCalendar(\'calendar_div\', \'' .
        date("Y", strtotime($date . " + 1 Month")) .
        '\', \'' .
        date("m", strtotime($date . " + 1 Month")) .
        '\');">></a>
		</div>
	</div>
	';
    // Include the selected country name in the title bar HTML
    if (isset($_POST["country"]) && !empty($_POST["country"])) {
        $titleBarHTML = str_replace(
            "Select Country",
            $_POST["country"],
            $titleBarHTML
        );
    }
    echo $titleBarHTML;
    ?>

<script>
    // JavaScript function to go to today's date
    function goToToday() {
        var today = new Date();
        var day = today.getDate();
       
        var month = today.getMonth() + 1; // January is 0
        var year = today.getFullYear();
        
        // var formattedDate = year + '-' + month + '-' + day;
        var formattedDate = day + '-' + month + '-' + year;
        // Reload the calendar with today's date
        getCalendar('calendar_div', year, month);
        // Update the button text with the current date
        document.querySelector('.today-button button').textContent =   formattedDate;
    }
</script>




<aside class="another-events-top-bar" id="another_events_top_bar">
			<?php echo getAnotherEventsTopBar(); ?>
		</aside>


	<div class="calendar-contain">
		
		
		<aside class="calendar__sidebar" id="event_list">
			<?php echo getEvents(); ?>
		</aside>

		

		<div class="calendar__days">	
			<div class="calendar__top-bar">
				<span class="top-bar__days">SUN</span>
				<span class="top-bar__days">MON</span>
				<span class="top-bar__days">TUE</span>
				<span class="top-bar__days">WED</span>
				<span class="top-bar__days">THU</span>
				<span class="top-bar__days">FRI</span>
				<span class="top-bar__days">SAT</span>	
			</div>
				
			
			<?php
   $dayCount = 1;
   $eventNum = 0;

   echo '<div class="calendar__week">';

   for ($cb = 1; $cb <= $boxDisplay; $cb++) {
       if (
           ($cb >= $currentMonthFirstDay || $currentMonthFirstDay == 1) &&
           $cb <= $totalDaysOfMonthDisplay
       ) {
           // Current date
           $currentDate = $dateYear . "-" . $dateMonth . "-" . $dayCount;

           // Get number of events based on the current date
           global $db;
           $result = $db->query(
               "SELECT title FROM events WHERE date = '" .
                   $currentDate .
                   "' AND status = 1"
           );
           $eventNum = $result->num_rows;

           // Define date cell color
           if (strtotime($currentDate) == strtotime(date("Y-m-d"))) {
               echo '
								<div class="calendar__day today" onclick="getEvents(\'' .
                   $currentDate .
                   '\');">
									<span class="calendar__date">' .
                   $dayCount .
                   '</span>
									
								</div>
							';
           } elseif ($eventNum > 0) {
               echo '
								<div class="calendar__day event" onclick="getEvents(\'' .
                   $currentDate .
                   '\');">
									<span class="calendar__date">' .
                   $dayCount .
                   '</span>
									 
								</div>
							';
           } else {
               echo '
								<div class="calendar__day no-event" onclick="getEvents(\'' .
                   $currentDate .
                   '\');">
									<span class="calendar__date">' .
                   $dayCount .
                   '</span>
									 
								</div>
							';
           }
           $dayCount++;
       } else {
           if ($cb < $currentMonthFirstDay) {
               $inactiveCalendarDay =
                   $totalDaysOfMonth_Prev - $currentMonthFirstDay + 1 + $cb;
               // $inactiveLabel = 'expired';
           } else {
               $inactiveCalendarDay = $cb - $totalDaysOfMonthDisplay;
               // $inactiveLabel = 'upcoming';
           }
           echo '
							<div class="calendar__day inactive">
								
								
							</div>
						';
       }
       echo $cb % 7 == 0 && $cb != $boxDisplay
           ? '</div><div class="calendar__week">'
           : "";
   }
   echo "</div>";
   ?>
		</div>


		

		<aside class="calendar__rightsidebar" id="right_event_list">
    		<?php echo getEventsRight(); ?>
		</aside>

      

		
			</div>

	<script>
		function getCalendar(target_div, year, month, date) {
    $.ajax({
        type: 'POST',
        url: 'functions.php',
        data: {
            func: 'getCalender',
            year: year,
            month: month,
            date: date // Add the 'date' parameter here
        },
        success: function(html) {
            $('#' + target_div).html(html);
        }
    });
}
		
		function getEvents(date){
			$.ajax({
				type:'POST',
				url:'functions.php',
				data:'func=getEvents&date='+date,
				success:function(html){
					$('#event_list').html(html);
					getEventsRight(date);
				}
			});
		}

		function getEventsRight(date){
    $.ajax({
        type:'POST',
        url:'functions.php',
        data:{ func: 'getEventsRight', date: date }, // Ensure the func parameter is set to 'getEventsRight'
        success:function(html){
            $('#right_event_list').html(html); // Update the right sidebar container
        }
    });
}

function getEventsRights(date, country){
    $.ajax({
        type:'POST',
        url:'functions.php',
        data:{ func: 'getEventsRight', date: date, country: country }, // Ensure the func parameter is set to 'getEventsRight'
        success:function(html){
            $('#right_event_list').html(html); // Update the right sidebar container
        }
    });
}

function getEventsRightbar(date,country,state){
    $.ajax({
        type:'POST',
        url:'functions.php',
        data:{ func: 'getEventsRightbar', date: date }, // Ensure the func parameter is set to 'getEventsRight'
        success:function(html){
            $('#right_event_list').html(html); // Update the right sidebar container
        }
    });
}


// Add this JavaScript function along with other functions
function getAnotherEventsTopBar(date){
    $.ajax({
        type:'POST',
        url:'functions.php',
        data:{ func: 'getAnotherEventsTopBar', date: date }, // Ensure the func parameter is set to 'getAnotherEventsTopBar'
        success:function(html){
            $('#another_events_top_bar').html(html); // Update the another events top bar container
        }
    });
}


		/*
 * Generate event calendar in HTML format
 */	
		
		$(document).ready(function(){
			$('.month-dropdown').on('change',function(){
				getCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val());
			});
			$('.year-dropdown').on('change',function(){
				getCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val());
			});
			$('.country-dropdown').on('change', function(){
             var country = $(this).val();
                
    });
	$('.state-dropdown').on('change', function(){
        var state = $(this).val();
        // Send AJAX request to fetch and update Rahukalam timings based on the selected state
    });
		});
	</script>




<?php
}

/*
 * Generate months options list for select box
 */
function getMonthList($selected = "")
{
    $options = "";
    for ($i = 1; $i <= 12; $i++) {
        $value = $i < 10 ? "0" . $i : $i;
        $selectedOpt = $value == $selected ? "selected" : "";
        $options .=
            '<option value="' .
            $value .
            '" ' .
            $selectedOpt .
            " >" .
            date("F", mktime(0, 0, 0, $i + 1, 0, 0)) .
            "</option>";
    }
    return $options;
}

/*
 * Generate years options list for select box
 */
function getYearList($selected = "")
{
    $yearInit = !empty($selected) ? $selected : date("Y");
    $yearPrev = $yearInit - 5;
    $yearNext = $yearInit + 5;
    $options = "";
    for ($i = $yearPrev; $i <= $yearNext; $i++) {
        $selectedOpt = $i == $selected ? "selected" : "";
        $options .=
            '<option value="' .
            $i .
            '" ' .
            $selectedOpt .
            " >" .
            $i .
            "</option>";
    }
    return $options;
}

/*
 * Generate events list in HTML format
 */
function getEvents($date = "")
{
    // Set default date if not provided
    $date = $date ? $date : date("Y-m-d");

    // Initialize the HTML string
    $eventListHTML =
        '<h2 class="sidebar__heading">' .
        date("l", strtotime($date)) .
        "<br>" .
        date("F d", strtotime($date)) .
        "</h2>";

    global $db;
    $query =
        "SELECT tamil_year, tamil_month,  tamil_ritu, solstice, solar, lunar, date, rahukalam, yamakandam, gulikai,lagnam,tyajyam,sun, sun_rashi, sun_transit,thithi, thithi_end, nakshatra,nakshatra_starttime,yogam, photo_url,aphoto_url,pphoto_url  FROM events WHERE date = ? AND status = 1";

    // Prepare and execute the query
    $statement = $db->prepare($query);
    $statement->bind_param("s", $date);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
        // Start building the table with Bootstrap classes
        $eventListHTML .= '<div class="table-responsive">';
        $eventListHTML .= '<table class="table event-table">';
        $eventListHTML .= "<tbody>"; // Opening tbody tag

        while ($row = $result->fetch_assoc()) {
            // $eventListHTML .= "<tr>";
            // $eventListHTML .= '<td class="head-table">Country Name</td>';
            // $eventListHTML .=
            //     '<td class="head-table">' . $row["country"] . "</td>";
            // $eventListHTML .= "</tr>";
            $eventListHTML .= "<tr>";
            $eventListHTML .= '<td class="head-table">Nakshatram</td>';
            $eventListHTML .=
                '<td class="head-table">' . $row["nakshatra"] . "</td>";
            $eventListHTML .= "</tr>";
            $eventListHTML .= "<tr>";
            $eventListHTML .= '<td class="head-table">Yogam</td>';
            $eventListHTML .=
                '<td class="head-table">' . $row["yogam"] . "</td>";
            $eventListHTML .= "</tr>";
            $eventListHTML .= "<tr>";
            $eventListHTML .= '<td class="head-table">Nakshatram time</td>';
            $eventListHTML .=
                '<td class="head-table">' .
                $row["nakshatra_starttime"] .
                "</td>";
            $eventListHTML .= "</tr>";
            $eventListHTML .= "<tr>";
            $eventListHTML .= '<td class="head-table">Rahukalam</td>';
            $eventListHTML .=
                '<td class="head-table">' . $row["rahukalam"] . "</td>";
            $eventListHTML .= "</tr>";
            $eventListHTML .= "<tr>";
            $eventListHTML .= '<td class="head-table">Yamakandam</td>';
            $eventListHTML .=
                '<td class="head-table">' . $row["yamakandam"] . "</td>";
            $eventListHTML .= "</tr>";
            $eventListHTML .= "<tr>";
            $eventListHTML .= '<td class="head-table">Gulikai</td>';
            $eventListHTML .=
                '<td class="head-table">' . $row["gulikai"] . "</td>";
            $eventListHTML .= "</tr>";
            $eventListHTML .= "<tr>";
            $eventListHTML .= '<td class="head-table">Lagnam</td>';
            $eventListHTML .=
                '<td class="head-table">' . $row["lagnam"] . "</td>";
            $eventListHTML .= "</tr>";
            $eventListHTML .= "<tr>";
            $eventListHTML .= '<td class="head-table">Tyajyam</td>';
            $eventListHTML .=
                '<td class="head-table">' . $row["tyajyam"] . "</td>";
            $eventListHTML .= "</tr>";
            $eventListHTML .= "<tr>";
            $eventListHTML .= '<td class="head-table">Today Thithi</td>';
            $eventListHTML .=
                '<td class="head-table">' . $row["thithi"] . "</td>";
            $eventListHTML .= "</tr>";
            $eventListHTML .= "<tr>";
            $eventListHTML .= '<td class="head-table">Thithi Upto</td>';
            $eventListHTML .=
                '<td class="head-table">' . $row["thithi_end"] . "</td>";
            $eventListHTML .= "</tr>";

            // Add photo URLs with tooltips
            $photoUrls = [
                [
                    "url" => $row["photo_url"],
                    "tooltip" => "Muhurtham",
                    "width" => "70px",
                ],
                [
                    "url" => $row["aphoto_url"],
                    "tooltip" => "Amavasai",
                    "width" => "90px",
                ],
                [
                    "url" => $row["pphoto_url"],
                    "tooltip" => "Purnima",
                    "width" => "90px",
                ],
            ];

            foreach ($photoUrls as $photo) {
                if (!empty($photo["url"])) {
                    $eventListHTML .= "<tr>";
                    // $eventListHTML .= '<td class="head-table">' . $photo['tooltip'] . '</td>';
                    $eventListHTML .=
                        '<td class="head-table"><span class="sidebar__list-item image" data-toggle="tooltip" data-placement="right" title="' .
                        $photo["tooltip"] .
                        '"><img src="' .
                        $photo["url"] .
                        '" alt="Event Photo" style="width:' .
                        $photo["width"] .
                        ';" ></span></td>';
                    $eventListHTML .= "</tr>";
                }
            }
        }

        $eventListHTML .= "</tbody>"; // Closing tbody tag
        $eventListHTML .= "</table>";
        $eventListHTML .= "</div>";
    } else {
        $eventListHTML .= "<p>No events found for the selected date.</p>";
    }
    echo $eventListHTML;
}
function getAnotherEventsTopBar($date = "")
{
    $date = $date ? $date : date("Y-m-d");
    $eventListHTML = "";
    // Fetch events for the another top bar based on the specific date or any other condition
    global $db;
    // Example query, replace it with your actual query
    $result = $db->query(
        "SELECT tamil_year, tamil_month, tamil_ritu, solstice, solar, lunar, date, rahukalam, yamakandam, gulikai,lagnam,tyajyam,sun, sun_rashi, sun_transit,thithi, thithi_end, nakshatra,nakshatra_starttime,yogam, special_days, special_days1, special_days2, special_days3, special_days4,special_days5,photo_url   FROM events WHERE date = '" .
            $date .
            "' AND status = 1"
    );
    if ($result->num_rows > 0) {
        $eventListHTML .= '<div class="another-events-top-bar">';
        $eventListHTML .= "<table>";
        // Table headers
        // $eventListHTML .= '<tr>';
        // $eventListHTML .= '<th>Tamil Year</th>';
        // $eventListHTML .= '<th>Solar Month</th>';
        // $eventListHTML .= '<th>Lunar Month</th>';
        // $eventListHTML .= '<th>Solstice</th>';
        // $eventListHTML .= '<th>Tamil Ritu</th>';
        // $eventListHTML .= '</tr>';
        while ($row = $result->fetch_assoc()) {
            // Table row
            $eventListHTML .= "<tr>";
            $tooltipText = "Tamil Year";
            $eventListHTML .=
                '<td><span data-toggle="tooltip" data-bs-placement="bottom" title="' .
                $tooltipText .
                '">' .
                $row["tamil_year"] .
                "</span></td>";
            $tooltipText = "Solar Month";
            $eventListHTML .=
                '<td><span data-toggle="tooltip" data-bs-placement="bottom" title="' .
                $tooltipText .
                '">' .
                $row["solar"] .
                "</span></td>";
            $tooltipText = "Lunar Month";
            $eventListHTML .=
                '<td><span data-toggle="tooltip" data-bs-placement="bottom" title="' .
                $tooltipText .
                '">' .
                $row["lunar"] .
                "</span></td>";
            $tooltipText = "Solstice";
            $eventListHTML .=
                '<td><span data-toggle="tooltip" data-bs-placement="bottom" title="' .
                $tooltipText .
                '">' .
                $row["solstice"] .
                "</span></td>";
            $tooltipText = "Tamil Ritu";
            $eventListHTML .=
                '<td><span data-toggle="tooltip" data-bs-placement="bottom" title="' .
                $tooltipText .
                '">' .
                $row["tamil_ritu"] .
                "</span></td>";
            $eventListHTML .= "</tr>";
        }
        $eventListHTML .= "</table>";
        $eventListHTML .= "</div>";
    }
    echo $eventListHTML;
}

/*
 * Fetch events for the right sidebar
 */
function getEventsRight($date = "")
{
    $date = $date ? $date : date("Y-m-d");
    $eventListHTML = "";

    // Fetch different events based on the specific date or any other condition
    global $db;
    $result = $db->query(
        "SELECT tamil_year, tamil_month, tamil_ritu, solstice, solar, lunar, date, rahukalam, yamakandam, gulikai,lagnam,tyajyam,sun, sun_rashi, sun_transit,thithi, thithi_end, nakshatra,nakshatra_starttime,yogam,tspecial,tspecial1, special_days, special_days1, special_days2, special_days3, special_days4, special_days5   FROM events WHERE date = '" .
            $date .
            "' AND status = 1"
    );

    $hasTodaySpecialEvents = false; // Flag to check if there are today's special events

    if ($result->num_rows > 0) {
        $eventListHTML .= '<div class="table-responsive">';
        $eventListHTML .= '<table class="table">';

        $eventListHTML .= '<tbody style="display: block;">';
        $eventListHTML .= '<ul class="sidebar__list">';
        $monthName = date("F", strtotime($date)); // Get the month name dynamically
        while ($row = $result->fetch_assoc()) {
            // Check if there are Todays Special events
            if (!empty($row["tspecial"]) || !empty($row["tspecial1"])) {
                // Set flag to true if there are Todays Special events
                $hasTodaySpecialEvents = true;

                // Output Todays Special event details

                $eventListHTML .=
                    '<li class="sidebar__list-item"><span class="list-item__times"></span>' .
                    $row["tspecial"] .
                    "</li>";
                $eventListHTML .=
                    '<li class="sidebar__list-item"><span class="list-item__times"></span>' .
                    $row["tspecial1"] .
                    "</li>";
            }

             // Output Festival event details
             $eventListHTML .=
             '<li class="sidebar__list-item"><h2 class="special_days">Festivals in ' .
             $monthName .
             "</h2></li>";
         // Output Festival event details
         $eventListHTML .= "<tr>";
         $eventListHTML .= '<td class="head-table"></td>';
         $eventListHTML .=
             '<td class="head-table">' . $row["special_days"] . "</td>";
         $eventListHTML .= "</tr>";
         $eventListHTML .= "<tr>";
         $eventListHTML .= '<td class="head-table"></td>';
         $eventListHTML .=
             '<td class="head-table">' . $row["special_days1"] . "</td>";
         $eventListHTML .= "</tr>";
         $eventListHTML .= "<tr>";
         $eventListHTML .= '<td class="head-table"></td>';
         $eventListHTML .=
             '<td class="head-table">' . $row["special_days2"] . "</td>";
         $eventListHTML .= "</tr>";
         $eventListHTML .= "<tr>";
         $eventListHTML .= '<td class="head-table"></td>';
         $eventListHTML .=
             '<td class="head-table">' . $row["special_days3"] . "</td>";
         $eventListHTML .= "</tr>";
         $eventListHTML .= "<tr>";
         $eventListHTML .= '<td class="head-table"></td>';
         $eventListHTML .=
             '<td class="head-table">' . $row["special_days4"] . "</td>";
         $eventListHTML .= "</tr>";
         $eventListHTML .= "<tr>";
         $eventListHTML .= '<td class="head-table"></td>';
         $eventListHTML .=
             '<td class="head-table">' . $row["special_days5"] . "</td>";
         $eventListHTML .= "</tr>";

         $eventListHTML .= "</tbody>";
         $eventListHTML .= "</table>";
         $eventListHTML .= "</div>";

            // Wrap the sun details with a <div> tag
            $eventListHTML .= '<div class="sun-transit">';
            $eventListHTML .=
                '<li class="sidebar__list-item"><span class="list-item__time">' .
                $row["sun"] .
                "</span></li>";
            $eventListHTML .=
                '<li class="sidebar__list-item"><span class="list-item__time">' .
                $row["sun_rashi"] .
                "</span></li>";
            $eventListHTML .=
                '<li class="sidebar__list-item"><span class="list-item__time">' .
                $row["sun_transit"] .
                "</span></li>";
            $eventListHTML .= "</div>";
        }
        $eventListHTML .= "</ul>";
    }

    // Only display "Todays Special" heading if there are Todays Special events
    if ($hasTodaySpecialEvents) {
        $eventListHTML =
            '<li class="sidebar__list-item"><h2 class="special_days">Todays Special</h2></li>' .
            $eventListHTML;
    }

    echo $eventListHTML;
}
