<?php
    require_once "_db.php";
    require_once "_session.php";

    $header_info = array(
        "title" => "Manage Spots",
        "css" => array("_stylesheets/sell.css"),
        "scripts" => array("_scripts/sell.js"),
        "social" => true,
        "bootstrap_toggle" => true
    );
    require_once "_header.php";
    require_once "navbar.php";

    $is_archived = isset($_GET['archived']) && $_GET['archived'] == "true"; 
?>

    <body>
        <div class="container">
            <div id="property_warning" class="alert alert-warning" hidden>
                <p>No properties yet! Visit your <a href="seller_setup.php">setup page</a> to add a driveway.</p>
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
                    <input id="archive_toggle" type="checkbox" <?php echo (($is_archived) ? "" : "checked");?> data-toggle="toggle" data-on="Upcoming" data-off="Archived" data-onstyle="primary" data-offstyle="default">
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <th><i class="fa fa-2x fa-home"></i>&ensp;My Driveways</th>
                            <th><i class="fa fa-2x fa-calendar"></i>&ensp;Events</th>
                        </thead>
                        <tbody id="offerings_tbody">
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="modal fade" id="offering_availability_details_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">
                            <i class="fa fa-lg fa-info"></i>
                            &ensp;
                            <span class="text-primary" id="offering_availability_details_modal_title"></span>
                            <small id="offering_availability_details_modal_subtitle"></small>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover">
                            <thead>
                                <th class="text-center"><i class="fa fa-user"></i>&ensp;Buyer</th>
                                <th class="text-center"><i class="fa fa-clock-o"></i>&ensp;Purchased</th>
                                <th class="text-center"><i class="fa fa-money"></i>&ensp;Price</th>
                            </thead>
                            <tbody id="offering_availability_details_tbody">
                            </tbody>
                        </table>
                        <!--<hr>-->
                        <div class="row">
                            <div class="col-md-12">
                                <h4><i class='fa fa-edit'></i>&ensp;<span class='text-primary'>Edit</span></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-md-offset-1 text-right">
                                <label for='offering_availability_details_num_spots'># Spots</label>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-btn"><button class="btn btn-danger num-spots-adjust adjust-down"><i class="fa fa-minus"></i></button></span>
                                    <input id="offering_availability_details_num_spots" class="form-control input-number offering-availability-details-edit num-spots-adjust" type="number" />
                                    <span class="input-group-btn"><button class="btn btn-success num-spots-adjust adjust-up"><i class="fa fa-plus"></i></button></span>
                                </div>
                            </div>
                            <div class="col-md-2 text-right">
                                <label for='offering_availability_details_price'>Price</label>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-btn"><button class="btn btn-danger price-adjust adjust-down"><i class="fa fa-minus"></i></button></span>
                                    <input id="offering_availability_details_price" class="form-control input-number offering-availability-details-edit price-adjust" type="number"/>
                                    <span class="input-group-btn"><button class="btn btn-success price-adjust adjust-up"><i class="fa fa-plus"></i></button></span>
                                </div>
                            </div>
                        </div>
                        <hr class="show-on-offering-availability-details-edit">
                        <div class="row show-on-offering-availability-details-edit">
                            <div class="col-md-1">
                                <i class="text-warning fa fa-warning"></i>
                            </div>
                            <div class="col-md-11 text-left">
                                <p class="text-warning">Once a spot has been purchased you cannot edit this offering; all prices and available spots are final. Please verify your changes before saving.</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <?php
    require_once "_footer.php";
?>
