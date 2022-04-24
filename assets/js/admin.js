$(function () {

    $('.idbox-slide').slideUp();

    $(".modifier").on('click', function () {
        $id = $(this).attr('id') + 'box';
        $('.idbox-slide#' + $id).toggle("slow");

    });

})