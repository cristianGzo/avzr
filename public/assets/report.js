$(document).ready(init);


function init(){

    let tbl=new DataTable('#tbl', {
        ajax: '/api/reports/',
        columns:[
            {data: 'SERIAL_NUMBER'},
            {data: 'BUILD_CODE'},
            {data: 'CREATE_TS'},
            {data: 'ShipSerial'},
            {data: 'ShipLabelTimeStamp'},
        ]
    });


    $('#anios').select2({
        ajax: {
            url: '/api/year',
            dataType: 'json',
            processResults: function(data){
                return {
                    results: data.data
                };
            }
        }
    });


    $('#anios').on('select2:select', function (e){
        var data = e.params.data;
        console.log(data.text);
        let pr=parseFloat(data.text);
        tbl.ajax.url('/api/filter/'+pr).load();
    })
}
