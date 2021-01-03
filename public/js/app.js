

jQuery(document).ready(function () {
    // Get the ul that holds the collection of tags
    var $tagsCollectionHolder = $('div.tags');
    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $tagsCollectionHolder.data('index', $tagsCollectionHolder.find('input').length);

    $('body').on('click', '.add_item_link', function (e) {
        var $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');
        // add a new tag form (see next code block)

        addFormToCollection($collectionHolderClass);
    })

});
function addFormToCollection($collectionHolderClass) {
    // Get the ul that holds the collection of tags
    var $collectionHolder = $('.' + $collectionHolderClass);

    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');


    //var tab=prototype.split('<div class="form-group">');




    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);


    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('.tags').append(newForm);
    // Add the new form at the end of the list
    $collectionHolder.append($newFormLi)
    $('body').on('click', '.remove_item_link', function (e) {
        $(this).closest('.row').remove();

    })
}
//////////////////////////////////////////////////////////////////////
function addTagFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button">Delete this tag</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}
/////////////////////////////////////////////////////////////////////////////////

jQuery(document).ready(function () {
    $('.add_ingredient').on('click', function (e) {
        //e.preventDefault();

        form = $('#task_form').val();
        if (form === "") {
            $('.ingredient_exist').empty();
            $('#folder_exists_error').show();
            $('.ingredient_exist').append("Veuillez rensigner le champs ingrédient svp")

        }
        else {

            $.ajax({
                url: 'ingredient/new/ajax',
                type: 'POST',
                dataType: 'json',
                async: true,
                data: {
                    "task": form
                },
                async: true,
                success: function (data) {
                    if (data.message === 1) {
                        window.location.reload(true);
                    }
                    else if (data.message === 0) {
                        $('.ingredient_exist').empty();
                        $('#folder_exists_error').show();
                        $('.ingredient_exist').append("Cet ingrédient existe déja!")

                    }

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert('Failed')

                }

            })
        }

        //var modal = $(this)
        // modal.find('.modal-title').text('New message to ' + recipient)
        // modal.find('.modal-body input').val(recipient)
        // })
    })



    $(".my-rating").starRating({
        starSize: 25,
        ratedColors: ['#333333'],
        callback: function (currentRating, $el) { // make a server call here
            var $id = $('.recipe_id').html();
            $id = parseInt($id);



            $.ajax({
                url: '/rating/new/ajax',
                type: 'POST',
                dataType: 'json',
                async: true,
                data: {
                    "vote": currentRating,
                    "recipe": $id
                },
                async: true,
                success: function (data) {
                    console.log(data.message)


                },


                error: function (xhr, ajaxOptions, thrownError) {
                    alert('Failed')

                }

            })
        }
    });
    /////////////////////////////////////////////



})





