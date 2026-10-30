$(document).ready(function(){
    const doc = $(document);
    
    function getData(kategorie) {
        const list = $('#itemContainer').empty();
        kategorien[kategorie].items.forEach(function (item) {
            const card = $('<div class="rowClick card mb-2">');
            $('<div class="card-body">').text(item).appendTo(card);
            list.append(card);
        });
    }

    getData('backwaren');
    $('#selectItem').val('backwaren');

    doc.on('change', '#selectItem', function () {
        getData($(this).val());
    });

    $('#qtyPlus').on('click', function () {
        const input = $('#itemQuantity');
        input.val((parseInt(input.val()) || 0) + 1);
    });

    $('#qtyMinus').on('click', function () {
        const input = $('#itemQuantity');
        input.val(Math.max(1, (parseInt(input.val()) || 1) - 1));
    });

    $('#itemQuantity').on('keydown', function (e) {
        if (['e', 'E', '+', '-', '.', ','].includes(e.key)) e.preventDefault();
    });
    
    doc.on('click', '.rowClick', function() {
        const item = $(this).find('.card-body').text();
        $('#headerItem').text(item);
        $('#itemQuantity').val(1);
        $('#itemModal').modal('show');
    });

    doc.on('click', '#saveItem', function () {
        const item = $('#headerItem').text();
        const qty  = parseInt($('#itemQuantity').val());

        if (!qty || qty < 1) {
            swal('Ungültige Menge!');
        } else {
            swal(item + ' - ' + qty);
            $('#itemModal').modal('hide');
        }
    });

});