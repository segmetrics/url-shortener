// Add a Link
// --------------------------------------------
$(document).on('submit', '#create-link', function (e) {
    e.preventDefault();
    var formData = objectifyForm($(this).serializeArray());

    // Simple URL Checking
    if(formData.dest.indexOf('http://') === -1 && formData.dest.indexOf('https://') === -1){
        swal({
            title: 'Please included http or https in your URL',
            type: 'warning',
            confirmButtonText: 'Ok'
        });
        return;
    }

    $.post( "./?action=create", formData, function( res ) {
        if(res.data){
            showMessage('Link Created!', 'success');
            createRow(res.data.link, res.data.dest, res.data.url);
        } else {
            showMessage(res.error, 'danger');
        }
    }, "json");
});

// Show Edit Modal
// --------------------------------------------
$(document).on('click', '[data-toggle="edit"]', function (e) {
    e.preventDefault();
    var link = $(this).data('target');

    // Set Values
    $modal = $('#editModal');
    $modal.find('input[name="old"]').val( link );
    $modal.find('input[name="link"]').val( link );
    $modal.find('input[name="dest"]').val( $('#dest-'+link).text() );

    // Show Modal
    $modal.modal('show');
});

// Save the Edit
// --------------------------------------------
$(document).on('submit', '#edit-link', function (e) {
    e.preventDefault();
    var formData = objectifyForm($(this).serializeArray());
    $.post( "./?action=edit", formData, function( res ) {
        if(res.data){

            // Delete Old Row
            $('#row-'+formData.old).remove();

            // Create New Row
            createRow(res.data.link, res.data.dest, res.data.url);

            // Clear Value from Modal
            $modal = $('#editModal');
            $modal.modal('hide');
            $modal.find('input[name="old-link"]').val();
            $modal.find('input[name="link"]').val();
            $modal.find('input[name="dest"]').val();

        } else {
            showMessage(res.error, 'danger');
        }
    }, "json");
});

// Delete a Link
// --------------------------------------------
$(document).on('click', '[data-toggle="delete"]', function (e) {
    e.preventDefault();
    var target = $(this).data('target');

    // Confirm and Send
    swal({
        title: 'Are you sure you want to delete this Short URL?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ok',
        cancelButtonText: 'Cancel'
    }).then(function(approveConfirm) {
        if(approveConfirm.value){
            $.post( "./?action=delete", {id: target, _token: window.csrfToken}, function( res ) {
                if(res.data){
                    showMessage('Link Deleted', 'success');
                    $("#row-"+target).fadeOut('fast', function(){ $(this).remove() });
                } else {
                    showMessage(res.error, 'danger');
                }
            }, "json");
        }
    }.bind(this));
});

// Clipboard Copying
// ------------------------
$(document).on('click', '[data-toggle="copy"]', function (e) {
    e.preventDefault();
    var target = $(this).data('target');

    // Create the Text Area to copy from
    var $textArea = $('<textarea style="position: absolute; height: 1px; width: 1px; left: -9999px;" readonly></textarea>')
        .val( $(target).text() )
        .appendTo('body')
        .select();
    document.execCommand('copy');
    $textArea.remove();
    showMessage('Boom, Bam! Link copied to clipboard!', 'success');
});

// Clickable Delete Forms with Confirmation
// --------------------------------------------
$(document).on('click', '[data-method]', function (e) {
    e.preventDefault();

    // If we need to confirm, and we say "no", cancel
    if($(this).data('confirm')){
        swal({
            title: $(this).data('confirm'),
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ok',
            cancelButtonText: 'Cancel'
        }).then(function(approveConfirm) {
            if(approveConfirm.value){
                $("<form action='" + $(this).attr('href') + "' method='POST' style='display:none'>" +
                    "<input type='hidden' name='_method' value='" + $(this).attr('data-method') + "'>" +
                    "<input name=\"_token\" type=\"hidden\" value=\"" +  window.csrfToken + "\">" +
                    "</form>")
                    .appendTo($('body'))
                    .submit();
            }
        }.bind(this));
    } else {
        $("<form action='" + $(this).attr('href') + "' method='POST' style='display:none'>" +
            "<input type='hidden' name='_method' value='" + $(this).attr('data-method') + "'>" +
            "<input name=\"_token\" type=\"hidden\" value=\"" +  window.csrfToken + "\">" +
            "</form>")
            .appendTo($('body'))
            .submit();
    }
});

function showMessage(message, type) {
    $('<div class="banner banner-' + type + '">' + message + '</div>')
        .hide()
        .appendTo('#banner')
        .fadeIn(200)
        .delay(3000)
        .fadeOut(500);
}

function createRow(link, dest, url){
    $('#link-table tbody').prepend('<tr id="row-'+link+'">' +
        '<td>' +
            '<button class="btn btn-xs btn-primary-outline" data-toggle="copy" data-target="#link-'+link+'">Copy</button>&nbsp;&nbsp; ' +
            '<span id="link-'+link+'">'+url+'</span>' +
        '</td>' +
        '<td><a id="dest-'+link+'" href="'+dest+'" target="_blank">'+dest+'</a></td>' +
        '<td class="text-right">' +
            '<button class="btn btn-xs btn-info" data-toggle="edit" data-target="'+link+'">Edit</button>&nbsp;&nbsp; ' +
            '<button class="btn btn-xs btn-danger" data-toggle="delete" data-target="'+link+'">Delete</button>' +
        '</td>' +
        '</tr>');

}

function objectifyForm(formArray) {//serialize data function

    var returnArray = {};
    for (var i = 0; i < formArray.length; i++){
        returnArray[formArray[i]['name']] = formArray[i]['value'];
    }
    return returnArray;
}