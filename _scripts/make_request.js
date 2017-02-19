/*global document, window, alert, console, require, $*/

var make_request_url;

function make_request(action, data, success, error) {
    'use strict';
    if (data === null) {
        data = {};
    }
    data.action = action;
    
    console.log(data);
    
    $.ajax({
        url: make_request_url,
        method: "post",
        data: data,
        success: function (data) {
            data  = JSON.parse(data);
            console.log(data);
            if (success) {
                success(data);
            }
        },
        error: function (data) {
            data = JSON.parse(data);
           if (error) {
               error(data);
           }
        }
    });
}

function make_request_file_upload(action, data, success, error) {
    'use strict';
    if (data == null) {
        return;
    }
    
    data.append("action", action);
    
    console.log(data);
    
    $.ajax({
        url: make_request_url,
        method: "post",
        data: data,
        success: function(data) {
            data = JSON.parse(data);
            console.log(data);
            success(data);
        },
        error: function(data) {
            data = JSON.parse(data);
            error(data);
        },
        contentType: false,
        processData: false,
        cache: false
    });
}
