$(document).ready(function() {
    $('.js-user-autocomplete').each(function(){
        var autocompleteUrl = $(this).data('my-autocomplete-url'); // need to be data(.....)

        $(this).autocomplete({hint: false}, [
            {
                displayKey: 'id',
                debounce: 500, // only request every 1/2 second
                source: function(query, cb) {
                    /*cb([
                        {value: 'foo'},
                        {value: 'bar'}
                        ])*/
                    $.ajax({
                        url: autocompleteUrl+'?query='+query
                    }).then(function(data) {
                        cb(data.users_list_values); // get "users_list-values" data
                    });
                }
            }
        ]);
    })
});