<?php
    include "_session.php";
    include "_db.php";

    $header_info = array(
        "title" => "Buy a Spot",
        "css" => array("_stylesheets/buy.css"),
        "scripts" => array("_scripts/buy.js"),
        "slider" => true,
        "data_table" => true,
        "social" => true,
        "autocomplete" => true
    );

    include "_header.php";
    include "navbar.php";
?>

    <body>
        <div class="container">
            <div class="alert alert-danger" role="alert" id="reservation_failed_alert" hidden="hidden">
                <strong>Uh-Oh!</strong> <span id="reservation_failed_message"></span>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-1">
                            <i class='fa fa-2x fa-map-marker'></i>
                        </div>
                        <div class="col-md-5">
                            <input id="stadium_search" type="text" class="form-control input-sm" placeholder="Search stadiums...">
                            <p class="text-info" id="geolocation_message">Based on your current location</p>
                        </div>
                        <div class="col-md-1">
                            <i class='fa fa-calendar fa-2x'></i>
                        </div>
                        <div class="col-md-5">
                            <select class="form-control input-sm" id="event_select">
                            </select>
                        </div>
                    </div>
                    <hr class="no-margin-top">
                    <div class="row">
                        <div class="col-md-6">
                            <input id="price_filter" data-slider-id='price_filter' type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="50" />
                            <p class="text-primary small"><strong>Price</strong></p>
                        </div>
                        <div class="col-md-6">
                            <input id="distance_filter" data-slider-id='distance_filter' type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="20" />
                            <p class="text-primary small"><strong>Distance</strong></p>
                        </div>
                    </div>
                    <hr class="no-margin-top">
                    <div class="row">
                        <div class="col-md-12" id="map_wrapper">
                            <div id="map_loader"></div>
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-1">
                            <i class="fa fa-search fa-2x"></i>
                        </div>
                        <div class="col-md-11">
                            <input id="offerings_table_search" type="text" class="form-control input-sm" placeholder="Filter results by address or owner...">
                        </div>
                    </div>
                    <hr>
                    <table id="offerings_table" class="table table-hover">
                        <thead class="thead-inverse">
                            <th class="text-center"></th>
                            <th class="text-center"><i class="fa fa-lg fa-home"></i>&nbsp;&nbsp;Address</th>
                            <th class="text-center"><i class="fa fa-lg fa-user"></i>&nbsp;&nbsp;Owner</th>
                            <th class="text-center"><i class="fa fa-lg fa-road"></i>
                                <!--&nbsp;&nbsp;Distance-->
                            </th>
                            <th class="text-center"><i class="fa fa-lg fa-money"></i>
                                <!--&nbsp;&nbsp;Price-->
                            </th>
                            <th class="text-center"></th>
                        </thead>
                        <tbody id="offerings_tbody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA92W3yX07dGidPcLYvBsOcpgNq0LBP2pM&callback=load_map"></script>
    </body>
    <?php
    include "_footer.php";
?>
