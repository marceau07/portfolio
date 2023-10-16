$('#dtBasicExample').Tabledit({
    url: 'index.php?page=os',
    columns: {
        identifier: [0, 'idOs'],
        editable: [[1, 'nomOs']]
    },
    onDraw: function () {
        console.log('onDraw()');
    },
    onSuccess: function (data, textStatus, jqXHR) {
        console.log('onSuccess(data, textStatus, jqXHR)');
        console.log(data);
        console.log(textStatus);
        console.log(jqXHR);
    },
    onFail: function (jqXHR, textStatus, errorThrown) {
        console.log('onFail(jqXHR, textStatus, errorThrown)');
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    },
    onAlways: function () {
        console.log('onAlways()');
    },
    onAjax: function (action, serialize) {
        console.log('onAjax(action, serialize)');
        console.log(action);
        console.log(serialize);
    }
});