<?php include_once 'functions.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home</title>
	<!--Stylesheet-->
	<link rel="stylesheet" href="./../styles/bootstrap.min.css">
	<link rel="stylesheet" href="./../styles.css">
    <!-- <a href="./styles.css"></a> -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="./../js/jquery.min.js"></script>
</head>
<body>

<section id="nav-bar">
         <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
               <a class="navbar-brand" href="#">
               <img src="./../images/Tamiser logo_LinkedIn.png">
               </a>
               <button class="navbar-toggler m-0 p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarNav">
                  <ul class="navbar-nav ms-auto">
                     <li class="nav-item">
                        <a class="nav-link" href="./../index.php">Home</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="./../about.html">About Us</a>
                     </li>
                     <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Our Services
                      </a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="./../cloud.html">Cloud</a></li>
                        <li><a class="dropdown-item" href="./../project.html">Projects</a></li>
                        <li><a class="dropdown-item" href="./../digital.html">Digital Transformation</a></li>
                        <li><a class="dropdown-item" href="./../It.html">IT Management</a></li>
                      </ul>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="./../chooseUs.html">Why Choose Us?</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="./../contact.html">Contact</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="./../india.php">Panchangam</a>
                     </li>
                  </ul>
               </div>
            </div>
         </nav>
      </section>

	<section class="calendar-months">
		<div class="container-fluid p-0">
			<div class="row">
				<div class="col-lg-12">
					<div id="calendar_div">
						<?php echo getCalender(); ?>
					</div>
					

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
                window.location.href = './usa.php';
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
                window.location.href = './../india.php';
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



<script src="./../javascript/bootstrap.bundle.js"></script>
</body>
</html>

