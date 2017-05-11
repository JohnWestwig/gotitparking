<?php
    require_once "_session.php";
    require_once "_db.php";
    require_once "stripe.php";

    $header_info = array(
        "title" => "Seller Setup",
        "css" => array(
            "_stylesheets/seller_setup.css",
            "_stylesheets/stripe.css"
        ),
        "scripts" => array("_scripts/seller_setup.js")
    );
    require_once "_header.php";
    include_once "navbar.php";
?>

    <body>
        <?php include_once "social_links.php"; ?>
            <div class="container">
                <div class="tab-container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#driveways" aria-controls="driveways" role="tab" data-toggle="tab"><i class="fa fa-home"></i>&nbsp;Driveways</a></li>
                        <li role="presentation"><a href="#stripe" aria-controls="stripe" role="tab" data-toggle="tab"><i class="fa fa-usd"></i>&nbsp;Payment</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="driveways">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table">
                                            <tbody id="driveway_tbody">
                                            </tbody>
                                        </table>
                                        <hr>
                                        <div class="col-md-12 text-right">
                                            <button id="add_driveway" class="btn btn-primary">Add a Driveway&nbsp;<i class="glyphicon glyphicon-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="map"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="stripe">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>ParkU uses Stripe to manage its payments.  We require all homeowners to create a Stripe account to allow for easy transferral of funds from your customers to you.  ParkU requires you to connect with Stripe before placing your driveways on the market.</h5>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <p id="stripe_account_id"></p>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <button class="btn btn-lg btn-success" id="createStripeAccountButton">Create Account</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--------------------- Driveway Add Modal --------------------------->
            <div id="driveway_add_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add Driveway</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="driveway_add_line1" class="col-md-2 col-form-label">Line 1</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="driveway_add_line1" placeholder="123 Main St.">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="driveway_add_line2" class="col-md-2 col-form-label">Line 2</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="driveway_add_line2">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="driveway_add_city" class="col-md-2 col-form-label">City</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="driveway_add_city" placeholder="Ithaca">
                                </div>
                                <label for="driveway_add_state" class="col-md-1 col-form-label">State</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id="driveway_add_state" placeholder="NY">
                                </div>
                                <label for="driveway_add_zipcode" class="col-md-2 col-form-label">Zip Code</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id="driveway_add_zipcode" placeholder="14850">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label for="driveway_add_num_spots" class="col-md-9 col-form-label">How many spots are typically available? (Think mid-size car)</label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" id="driveway_add_num_spots" placeholder="1" />
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label for="driveway_add_lat" class="col-md-2 col-form-label">Latitude</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="driveway_add_lat" placeholder="0.000" />
                                </div>
                                <label for="driveway_add_lng" class="col-md-2 col-form-label">Longitude</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="driveway_add_lng" placeholder="0.000" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                            <button type="button" id="driveway_add_confirm_button" class="btn btn-success">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--------------------- Driveway Edit Modal -------------------------->
            <div id="driveway_edit_modal" class="modal fade" tabindex="-1" role="dialog">
                <input type="hidden" id="driveway_edit_id" />
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Edit Driveway</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="driveway_num_spots" class="col-md-9 col-form-label">How many spots are typically available? (Think mid-size car)</label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" id="driveway_edit_num_spots" placeholder="1" />
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label for="driveway_edit_image" class="col-md-3 col-form-label">Upload an Image</label>
                                <div class="col-md-2">
                                    <button id="driveway_edit_image_upload_button" class='btn btn-primary'><i class='fa fa-upload'></i></button>
                                </div>
                                <div class="col-md-1">
                                    <form id="driveway_edit_image_upload_form">
                                        <div class="hidden-file-input">
                                            <input name="image" id="driveway_edit_image" class="form-control" type="file" accept="image/*">
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6 text-right">
                                    <img id="driveway_edit_image_display" height="100" width="100" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                            <button type="button" id="driveway_edit_confirm_button" class="btn btn-success">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
            <script async defer src="http://maps.googleapis.com/maps/api/js?key=AIzaSyA92W3yX07dGidPcLYvBsOcpgNq0LBP2pM&callback=load_map"></script>
    </body>
