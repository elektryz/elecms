$('#step1_form_skip').click(function() {
    $('#smtp').toggle(400);
});

if($('#step1_form_skip').is(":checked")) {
    $('#smtp').hide();
}

$('.what').popover();

function randomString() {
    var min = 12;
    var max = 16;
    var length = Math.floor(Math.random() * (max - min + 1)) + min,
    charset = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    $('#step1_form_token').val(retVal);
}