function confirmation(link){
    var currentLink=link;
    swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            if(currentLink.data('ajax')){
                $.ajax({'url': currentLink.data('ajax'), 'method': 'POST', 'success': function(data){
                    if(currentLink.data('callback')){
                        var funcion = currentLink.data('callback');
                        window[funcion]();
                    }
                    swal(
                        'Deleted!',
                        'Your item has been deleted.',
                        'success'
                    );
                }});
            }else{
                document.location.href=currentLink.attr('href');
                swal(
                    'Deleted!',
                    'Your item has been deleted.',
                    'success'
                );
            }
    } else if (
        // Read more about handling dismissals
        result.dismiss === swal.DismissReason.cancel
    ) {
        swal(
            'Cancelled',
            'Your imaginary item is safe :)',
            'error'
        );
    }
})

}