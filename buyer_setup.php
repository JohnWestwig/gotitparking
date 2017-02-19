<?php
    require_once "_session.php";
    require_once "_db.php";

    $header_info = array(
        "title" => "Buyer Setup",
        "css" => array("_stylesheets/buyer_setup.css"),
        "scripts" => array("_scripts/buyer_setup.js")
    );
    require_once "_header.php";
    require_once "navbar.php";
?>

    <body>
        <div class="container">
            <div class="tab-container">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#vehicles" aria-controls="vehicles" role="tab" data-toggle="tab"><i class="fa fa-car"></i>&nbsp;Vehicles</a></li>
                    <li role="presentation"><a href="#stripe" aria-controls="stripe" role="tab" data-toggle="tab"><i class="fa fa-usd"></i>&nbsp;Payment</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="vehicles">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table">
                                        <tbody id="vehicle_tbody">
                                        </tbody>
                                    </table>
                                    <hr>
                                    <div class="col-md-12 text-right">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#vehicle_create">Add a Vehicle&nbsp;<i class="glyphicon glyphicon-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="stripe">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="text-success">ParkU uses Stripe to manage its payments.  We do not store any credit-card or other sensitive information.  We will ask you to re-enter you credit card for every purchase for safety.</h4>
                                </div>
                                <div class="col-md-6">
                                    <img src="_images/stripe/Blue/Stripe%20Logo%20(blue).png" height="200px" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="vehicle_create" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add a Vehicle</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="vehicle_plate" class="col-md-2 col-form-label">Plate No.</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="vehicle_plate" placeholder="ABC-1234">
                            </div>
                            <label for="vehicle_color" class="col-md-2 col-form-label">Color</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="vehicle_color" placeholder="Blue">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="vehicle_make" class="col-md-2 col-form-label">Make</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="vehicle_make" placeholder="Honda">
                            </div>
                            <label for="vehicle_model" class="col-md-2 col-form-label">Model</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="vehicle_model" placeholder="Accord">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                        <button id="vehicle_create_button" type="button" class="btn btn-success">Confirm</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <?php include_once "social_links.php"; ?>
    </body>
    <?php
    include "_footer.php";
?>
