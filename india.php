<?php include_once 'functions.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home</title>
	<!--Stylesheet-->
	<link rel="stylesheet" href="./../styles/bootstrap.min.css">
	<link rel="stylesheet" href="./styles.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="./../js/jquery.min.js"></script>
</head>
<body>
	<section class="calendar-months">
		<div class="container-fluid p-0">
		
			<div class="row">
				<div class="col-lg-12">
					<div id="calendar_div">
						<?php echo getCalender(); ?>
					</div>
					

				</div>
			</div>

            
           
	</section>




<!--Script-->
<script>
$(document).ready(function(){
    console.log("Document ready function called."); // Add this line
    // Initialize Bootstrap tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>

<script>
function updatePage(select) {
    var selectedCountry = select.value;
    if (selectedCountry !== '') {
        switch (selectedCountry) {
            case 'USA':
                window.location.href = './../usa/usa.php';
                break;
            case 'UK':
                window.location.href = './../uk/uk.php';
                break;
            case 'Australia':
                window.location.href = './../australia/australia.php';
                break;
            case 'Canada':
                window.location.href = './../canada/canada.php';
                break;
            case 'India':
                window.location.href = './index.php';
                break;
            case 'dubai':
                window.location.href = './../dubai/dubai.php';
                break;  
            default:
                break;
        }
    }
}
document.addEventListener('DOMContentLoaded', function() {
    var urlParams = new URLSearchParams(window.location.search);
    var countryParam = urlParams.get('country');
    var dropdown = document.querySelector('.country-dropdown');
    if (countryParam && dropdown) {
        dropdown.value = countryParam;
    }
});
</script>








<!-- <script src="./../javascript/bootstrap.bundle.min.js"></script> -->
<script src="./javascript/bootstrap.bundle.min.js"></script>
</body>
</html>

