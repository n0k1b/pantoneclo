function datatable_sum(dt_selector, columnNo) {
    var rows        = dt_selector.rows().indexes();
    var rowsdataCol = dt_selector.cells( rows, columnNo, { page: 'current' } ).data();

    let text, data, total = 0, resultOfFooter;
    for (let i = 0; i < rowsdataCol.length; i++) {
        text = rowsdataCol[i];
        data = text.replace("$", "");
        total  += parseFloat(data);
    }
    var resultOfSum  = total.toFixed(2);

    var currencyFormat        = {!! json_encode(env('CURRENCY_FORMAT')) !!};
    var defaultCurrencySymbol = {!! json_encode(env('DEFAULT_CURRENCY_SYMBOL')) !!};
    if (currencyFormat=='prefix') {
        resultOfFooter = defaultCurrencySymbol +' '+ resultOfSum;
    }else{
        resultOfFooter = resultOfSum +' '+ defaultCurrencySymbol;
    }
    $(dt_selector.column(columnNo).footer()).html(resultOfFooter);
}
