<?php
session_start();
require("config.php");

// Check if user is logged in
if (!isset($_SESSION['auser'])) {
    header("location:index.php");
    exit();
}

$csvFile = 'c:\xampp\htdocs\real-estate\real-estate\admin\datasets.csv'; 

if (!file_exists($csvFile) || !is_readable($csvFile)) {
    die("Error: The file '$csvFile' is not accessible.");
}

$data = array_map('str_getcsv', file($csvFile)); 

$data = array_slice($data, 1); // Remove header row

$locations = [];
$prices = [];
$bedrooms = [];
$bathrooms = [];
$sqft = [];
$kitchens = [];
$balconies = [];
$halls = [];
$status = [];
$propertyTypes = [];

foreach ($data as $row) {
    if (count($row) >= 11) {
        $locations[] = htmlspecialchars($row[0]); 

        $cleanedPrice = preg_replace('/[^0-9.]/', '', $row[1]); 
        $prices[] = is_numeric($cleanedPrice) ? (float)$cleanedPrice : 0; 

        $bedrooms[] = is_numeric($row[3]) ? (int)$row[3] : 0; 

        $bathrooms[] = is_numeric($row[2]) ? (int)$row[2] : 0; 

        $cleanedSqft = preg_replace('/[^0-9.]/', '', $row[5]); 
        $sqft[] = is_numeric($cleanedSqft) ? (float)$cleanedSqft : 0; 

		$kitchens[] = is_numeric($row[6]) ? (int)$row[6] : 0; 

        $balconies[] = is_numeric($row[7]) ? (int)$row[7] : 0; 

        $halls[] = is_numeric($row[10]) ? (int)$row[10] : 0; 

        $propertyTypes[] = htmlspecialchars($row[11]);

		// Clean status data
		$statusValue = strtoupper(trim(preg_replace('/[^A-Za-z]/', '', $row[4])));
		$status[] = ($statusValue == 'AVAILABLE') ? 'AVAILABLE' : 'UNAVAILABLE';



    }
}
// Calculate the average price per property type
$propertyTypePrices = [];
$propertyTypeCounts = [];

foreach ($propertyTypes as $index => $type) {
    if (!isset($propertyTypePrices[$type])) {
        $propertyTypePrices[$type] = 0;
        $propertyTypeCounts[$type] = 0;
    }
    $propertyTypePrices[$type] += $prices[$index];
    $propertyTypeCounts[$type] += 1;
}

// Calculate average prices
$avgPropertyTypePrices = [];
foreach ($propertyTypePrices as $type => $totalPrice) {
    $avgPropertyTypePrices[$type] = $totalPrice / $propertyTypeCounts[$type];
}


$statusCount = [
    'AVAILABLE' => 0,
    'UNAVAILABLE' => 0
];

foreach ($status as $stat) {
    if (isset($statusCount[$stat])) {
        $statusCount[$stat]++;
    }
}

$availableCount = $statusCount['AVAILABLE'];
$unavailableCount = $statusCount['UNAVAILABLE'];


?>



<!DOCTYPE html>
<html lang="en">
    
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>4Real State - Dashboard</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="images/logo/4real_state.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		
		<!-- Feathericon CSS -->
        <link rel="stylesheet" href="assets/css/feathericon.min.css">
		
		<link rel="stylesheet" href="assets/plugins/morris/morris.css">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		<style>
			canvas {
			width: 100% !important;  
			height: 400px !important; 
	
				}
		</style>
		
		
    </head>
    <body>
	
		<!-- Main Wrapper -->

		
			<!-- Header -->
				<?php include("header.php"); ?>
			<!-- /Header -->
			
			<!-- Page Wrapper -->
            <div class="page-wrapper">
			
                <div class="content container-fluid">
					
					<!-- Page Header -->
					<div class="page-header" style="margin-top: 30px;">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="page-title">Welcome Admin!</h3>
								<p></p>
								<ul class="breadcrumb">
									<li class="breadcrumb-item active">Dashboard</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->

					<div class="row">
						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-primary">
											<i class="fe fe-users"></i>
										</span>
										
									</div>
									<div class="dash-widget-info">
										
										<h3><?php $sql = "SELECT * FROM user WHERE utype = 'user'";
										$query = $con->query($sql);
                						echo "$query->num_rows";?></h3>
										
										<h6 class="text-muted">Registered Users</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-primary w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-success">
											<i class="fe fe-users"></i>
										</span>
										
									</div>
									<div class="dash-widget-info">
										
									<h3><?php $sql = "SELECT * FROM user WHERE utype = 'agent'";
										$query = $con->query($sql);
                						echo "$query->num_rows";?></h3>
										
										<h6 class="text-muted">Agents</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-success w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-danger">
											<i class="fe fe-user"></i>
										</span>
										
									</div>
									<div class="dash-widget-info">
										
									<h3><?php $sql = "SELECT * FROM user WHERE utype = 'builder'";
										$query = $con->query($sql);
                						echo "$query->num_rows";?></h3>
										
										<h6 class="text-muted">Builder</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-danger w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-info">
											<i class="fe fe-home"></i>
										</span>
										
									</div>
									<div class="dash-widget-info">
										
									<h3><?php $sql = "SELECT * FROM property";
										$query = $con->query($sql);
                						echo "$query->num_rows";?></h3>
										
										<h6 class="text-muted">Properties</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-info w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-warning">
											<i class="fe fe-table"></i>
										</span>
										
									</div>
									<div class="dash-widget-info">
										
									<h3><?php $sql = "SELECT * FROM property where type = 'apartment'";
										$query = $con->query($sql);
                						echo "$query->num_rows";?></h3>
										
										<h6 class="text-muted">No. of Apartments</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-info w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-info">
											<i class="fe fe-home"></i>
										</span>
										
									</div>
									<div class="dash-widget-info">
										
									<h3><?php $sql = "SELECT * FROM property where type = 'house'";
										$query = $con->query($sql);
                						echo "$query->num_rows";?></h3>
										
										<h6 class="text-muted">No. of Houses</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-info w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-secondary">
											<i class="fe fe-building"></i>
										</span>
										
									</div>
									<div class="dash-widget-info">
										
									<h3><?php $sql = "SELECT * FROM property where type = 'building'";
										$query = $con->query($sql);
                						echo "$query->num_rows";?></h3>
										
										<h6 class="text-muted">No. of Buildings</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-info w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-primary">
											<i class="fe fe-tablet"></i>
										</span>
										
									</div>
									<div class="dash-widget-info">
										
									<h3><?php $sql = "SELECT * FROM property where type = 'flat'";
										$query = $con->query($sql);
                						echo "$query->num_rows";?></h3>
										
										<h6 class="text-muted">No. of Flat</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-info w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-success">
											<i class="fe fe-quote-left"></i>
										</span>
										
									</div>
									<div class="dash-widget-info">
										
									<h3><?php $sql = "SELECT * FROM property where stype = 'sale'";
										$query = $con->query($sql);
                						echo "$query->num_rows";?></h3>
										
										<h6 class="text-muted">On Sale</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-info w-50"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card">
								<div class="card-body">
									<div class="dash-widget-header">
										<span class="dash-widget-icon bg-info">
											<i class="fe fe-quote-right"></i>
										</span>
										
									</div>
									<div class="dash-widget-info">
										
									<h3><?php $sql = "SELECT * FROM property where stype = 'rent'";
										$query = $con->query($sql);
                						echo "$query->num_rows";?></h3>
										
										<h6 class="text-muted">Rentals</h6>
										<div class="progress progress-sm">
											<div class="progress-bar bg-info w-50"></div>
										</div>
									</div>
								</div>

							</div>
						</div>

                        <div class="col-lg-12">
        <div class="form-group">
            <label for="propertyTypeFilter">Filter by Property Type:</label>
            <select id="propertyTypeFilter" class="form-control">
                <option value="all">All</option>
                <?php foreach (array_keys($avgPropertyTypePrices) as $type): ?>
                    <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-lg-12">
    <div class="form-group">
        <label for="priceRangeFilter">Filter by Price Range:</label>
        <select id="priceRangeFilter" class="form-control">
            <option value="all">All</option>
            <option value="range1">Below $100,000</option>
            <option value="range2">$100,000 - $300,000</option>
            <option value="range3">$300,000 - $500,000</option>
            <option value="range4">Above $500,000</option>
        </select>
    </div>
</div>

						<!-- Graph Section -->
						<div class="row">

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Price Chart</h5>
						<canvas id="priceChart"></canvas>
						</div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Bedroom Distribution</h5>
                        <canvas id="bedroomChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Price Range Distribution</h5>
                        <canvas id="priceRangeChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Average Price by City</h5>
                        <canvas id="averagePriceCityChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Average Price</h5>
                        <canvas id="averagePriceChart"></canvas>
                    </div>
                </div>
            </div>

			<div class="col-lg-6">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Bathroom Distribution</h5>
            <canvas id="bathroomChart"></canvas>
        </div>
    </div>
</div>
<div class="col-lg-6">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Area Distribution (Sqft)</h5>
            <canvas id="sqftChart"></canvas>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Property Features Distribution</h5>
            <canvas id="featuresChart"></canvas>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Property Status (AVAILABLE vs UNAVAILABLE)</h5>
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>


            </div>
<!--end of graph -->
					</div>

				</div>			
			</div>
			<!-- /Page Wrapper -->

			<!--script-->
			
			<script>

const locations = <?php echo json_encode($locations); ?>;
const prices = <?php echo json_encode($prices); ?>;
const bedrooms = <?php echo json_encode($bedrooms); ?>;
const bathrooms = <?php echo json_encode($bathrooms); ?>;
const sqft = <?php echo json_encode($sqft); ?>;
const kitchens = <?php echo json_encode($kitchens); ?>;
const balconies = <?php echo json_encode($balconies); ?>;
const halls = <?php echo json_encode(value: $halls); ?>;


new Chart(document.getElementById("priceChart"), {
    type: 'bar',
    options: {
        responsive: true,
        maintainAspectRatio: false, 
        indexAxis: 'y', 
        scales: {
            x: {
                beginAtZero: true 
            },
            y: {
                beginAtZero: true, 
            }
        }
    },
    data: {
        labels: locations, 
        datasets: [{
            label: "Price (PHP)",
            data: prices, 
            backgroundColor: '#007bff'
        }]
    }
});

new Chart(document.getElementById("sqftChart"), {
    type: 'bar',
    data: {
        labels: ['Below 500 sqft', '500-1000 sqft', '1000-2000 sqft', '2000+ sqft'],
        datasets: [{
            label: "Area Distribution (Sqft)",
            data: [
                sqft.filter(area => area < 500).length,
                sqft.filter(area => area >= 500 && area < 1000).length,
                sqft.filter(area => area >= 1000 && area < 2000).length,
                sqft.filter(area => area >= 2000).length
            ],
            backgroundColor: ['#ffc107', '#007bff', '#28a745', '#dc3545']
        }]
    }
});

new Chart(document.getElementById("featuresChart"), {
    type: 'bar',
    data: {
        labels: locations, 
        datasets: [
            {
                label: 'Kitchens',
                data: kitchens,
                backgroundColor: '#007bff'
            },
            {
                label: 'Balconies',
                data: balconies,
                backgroundColor: '#28a745'
            },
            {
                label: 'Halls',
                data: halls,
                backgroundColor: '#ffc107'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            tooltip: {
                mode: 'index',
                intersect: false
            }
        },
        indexAxis: 'y',  
        scales: {
            x: {
                beginAtZero: true, 
                stacked: true,  
                title: {
                    display: true,
                    text: 'Count'
                }
            },
            y: {
                beginAtZero: true,  
                stacked: true,  
                title: {
                    display: true,
                    text: 'Locations'
                }
            }
        }
    }
});



new Chart(document.getElementById("bedroomChart"), {
    type: 'pie',
    data: {
        labels: ['1 Bedroom', '2 Bedrooms', '3 Bedrooms', '4 Bedrooms', '5+ Bedrooms'],
        datasets: [{
            label: "Bedroom Distribution",
            data: [
                bedrooms.filter(x => x === 1).length,
                bedrooms.filter(x => x === 2).length,
                bedrooms.filter(x => x === 3).length,
                bedrooms.filter(x => x === 4).length,
                bedrooms.filter(x => x >= 5).length,
            ],
            backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545', '#007bff']
        }]
    }
});

new Chart(document.getElementById("bathroomChart"), {
    type: 'pie',
    data: {
        labels: ['1 Bathroom', '2 Bathrooms', '3 Bathrooms', '4+ Bathrooms'],
        datasets: [{
            label: "Bathroom Distribution",
            data: [
                bathrooms.filter(x => x === 1).length,
                bathrooms.filter(x => x === 2).length,
                bathrooms.filter(x => x === 3).length,
                bathrooms.filter(x => x >= 4).length,
            ],
            backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
        }]
    }
});


new Chart(document.getElementById("priceRangeChart"), {
    type: 'bar',
    data: {
        labels: ['Below 1M', '1M - 5M', '5M - 10M', 'Above 10M'],
        datasets: [{
            label: "Price Range Distribution",
            data: [
                prices.filter(price => price < 1000000).length,
                prices.filter(price => price >= 1000000 && price < 5000000).length,
                prices.filter(price => price >= 5000000 && price < 10000000).length,
                prices.filter(price => price >= 10000000).length
            ],
            backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
        }]
    }
});

const averagePriceByCity = locations.reduce((acc, city, index) => {
    if (!acc[city]) {
        acc[city] = { sum: 0, count: 0 };
    }
    acc[city].sum += prices[index];
    acc[city].count += 1;
    return acc;
}, {});

const cityNames = Object.keys(averagePriceByCity);
const avgPrices = cityNames.map(city => averagePriceByCity[city].sum / averagePriceByCity[city].count);

new Chart(document.getElementById("averagePriceCityChart"), {
    type: 'bar',
    data: {
        labels: cityNames, 
        datasets: [{
            label: "Average Price by City",
            data: avgPrices, 
            backgroundColor: '#17a2b8'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, 
        indexAxis: 'y', 
        scales: {
            x: {
                beginAtZero: true 
            },
            y: {
                beginAtZero: true
            }
        }
    }
});

new Chart(document.getElementById("statusChart"), {
    type: 'pie',
    data: {
        labels: ['AVAILABLE', 'UNAVAILABLE'],
        datasets: [{
            label: "Property Status Distribution",
            data: [<?php echo $availableCount; ?>, <?php echo $unavailableCount; ?>],
            backgroundColor: ['#28a745', '#dc3545']
        }]
    }
});


var propertyTypes = <?php echo json_encode(array_keys($avgPropertyTypePrices)); ?>; 
var avgPropertyPrices = <?php echo json_encode(array_values($avgPropertyTypePrices)); ?>; 
new Chart(document.getElementById("averagePriceChart"), {
    type: 'bar', 
    data: {
        labels: propertyTypes, 
        datasets: [{
            label: "Average Price by Property Type",
            data: avgPropertyPrices, 
            borderColor: "#dc3545",
            backgroundColor: "#dc3545",
            fill: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                beginAtZero: true
            },
            y: {
                beginAtZero: true
            }
        }
    }
});




console.log(locations);
console.log(prices);
console.log(bedrooms);
console.log(bathrooms);
console.log(sqft);
console.log(propertyTypes);



</script>

		<!-- /Main Wrapper -->
		<!-- jQuery -->
        <script src="assets/js/jquery-3.2.1.min.js"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
		
		<!-- Slimscroll JS -->
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		
		<script src="assets/plugins/raphael/raphael.min.js"></script>    
		<script src="assets/plugins/morris/morris.min.js"></script>  
		<script src="assets/js/chart.morris.js"></script>
		
		<!-- Custom JS -->
		<script  src="assets/js/script.js"></script>

		
		
    </body>

</html>
