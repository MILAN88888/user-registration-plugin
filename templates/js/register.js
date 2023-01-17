//function for rating filter
function rating(value) {
    //ajax for rating
    jQuery.ajax({
        url: myscript.ajaxurl,
        method: 'post',
        data: { action: "rating_filter", rating: value },
        success: function (result) {
            //list of filtered data according to rating
            jQuery('#all-list').html(result);
           
        }
    });
}

//Function to filter latest
function latest(value) {
    //ajax call
    jQuery.ajax({
        url: myscript.ajaxurl,
        method: 'post',
        data: { action: "latest_filter", 'type': value },
        success: function (result) {
            // Data list according to latest filter
            jQuery('#all-list').html(result);
        }
    });
}
