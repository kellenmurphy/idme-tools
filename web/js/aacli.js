$(document).ready(function() {

    var form = $('#idpform :input')

    updateForm(form, $("#buildUrl"));
    $('#idpform select#scheme').on('change', function() {
        alert("this");
    });

});

function updateForm(formInputs, updateTarget) {
    formInputs.each( function() {
        var elem = "<div id=\"" + $(this).attr('id') + "\">";
        if ($(this).attr('id') != "idp" && $(this).attr('id') != "scheme") {
            elem += "&" + $(this).attr('id') + "=";
        }
        if ($(this).val()) {
            elem +=  $(this).val()
        } else {
            elem +=  $(this).attr('placeholder')
        }
        if ($(this).attr('id') == "idp") {
            elem += "/idp/profile/admin/resolvertest?saml2"
        }
        elem += "</div>";
        $(updateTarget).append(elem);

        var debug = true;
        if (debug) {
            if ($(this).val()) {
                console.debug("DOM element with id = " + $(this).attr('id') + " has value: " + $(this).val());
            }
            else {
                console.debug("DOM element with id = " + $(this).attr('id') + " has no value, using placeholder: " + $(this).attr('placeholder'));
            }
        }   

    });
}