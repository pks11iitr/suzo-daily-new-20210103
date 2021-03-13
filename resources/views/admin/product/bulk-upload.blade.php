@extends('layouts.admin')
@section('content')
    <link rel="stylesheet" href="{{asset('../admin-theme/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('../admin-theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Product Bulk Upload</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('product.list')}}">Product</a> </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Enter Below Details</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Upload Speadsheet</label>
                                                <input type="file" name="excel" class="form-control" id="excel" placeholder="Enter Name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Browse Images</label>
                                                <input type="file" name="images[]" class="form-control" id="images" placeholder="Enter Name" multiple>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (right) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
        <section class="content">

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Upload Progress</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body" id="progress-text">
                    Start creating your amazing application!
                </div>
                <!-- /.card-body -->
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->

        </section>
    </div>


    <!-- ./wrapper -->
@endsection
@section('scripts')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
    <script>
        var ExcelToJSON = function() {

            this.parseExcel = function(file) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var data = e.target.result;
                    var workbook = XLSX.read(data, {
                        type: 'binary'
                    });
                    workbook.SheetNames.forEach(function(sheetName) {
                        // Here is your object
                        var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                        var json_object = JSON.stringify(XL_row_object);
                        rows=JSON.parse(json_object);

                        ajaxerror=false;

                        var files = $('#images')[0].files; //where files

                        rows.forEach(function(row,index){
                            row=Object.values(row)

                            if(ajaxerror==false){

                                formdata=new FormData()
                                formdata.append('name',  row[0])
                                formdata.append('company', row[1])
                                formdata.append('description', row[2])
                                formdata.append('stock_type', row[3])
                                formdata.append('stock',row[4])
                                formdata.append('is_offer',row[5])
                                formdata.append('isactive', row[6])
                                formdata.append('category',row[7])
                                formdata.append('sub_category',row[8])
                                formdata.append('size',row[9])
                                formdata.append('price',row[10])
                                formdata.append('cut_price',row[11])
                                formdata.append('min_qty',row[12])
                                formdata.append('max_qty',row[13])
                                //formdata.append('size_stock',row[14])
                                formdata.append('consumed_units',row[14])
                                formdata.append('is_size_active',row[15])
                                formdata.append('new_arrival',row[17])
                                formdata.append('hot_deal',row[18])
                                formdata.append('discounted',row[19])


                                file_count=0;
                                image_identifier=row[16]

                                //alert(image_identifier)
                                for (var i = 0; i < files.length; i++) {
                                    if(files[i].name.search(image_identifier)==0){
                                        formdata.append('images['+file_count+']', files[i])
                                        console.log(files[i].name+'matched:'+row[0])
                                        file_count++
                                    }
                                }

                                $.ajax({

                                    url:'{{route('product.bulk.upload')}}',
                                    data:formdata,
                                    method:'post',
                                    async:false,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success:function(data1){
                                        $('#progress-text').html("Last Uploaded Item: ")
                                        $('#progress-text').append(row[0]+'-->'+row[1]+'-->'+row[9])
                                    },
                                    error:function(){
                                        ajaxerror=true
                                        $('#progress-text').append('Error Occurred in uploading: '+row[0]+'-->'+row[1]+'-->'+row[9])
                                    }

                                })
                            }

                            console.log(row)
                        })
                        //jQuery( '#xlx_json' ).val( json_object );
                    })
                };

                reader.onerror = function(ex) {
                    console.log(ex);
                };

                reader.readAsBinaryString(file);
            };
        };

        // function handleFileSelect(evt) {
        //
        //     var files = evt.target.files; // FileList object
        //     var xl2json = new ExcelToJSON();
        //     xl2json.parseExcel(files[0]);
        // }


        $(document).ready(function(){

            $("#submit").click(function(){
                file=$('#excel')[0].files
                var files = file; // FileList object
                var xl2json = new ExcelToJSON();
                xl2json.parseExcel(files[0]);
            })

            // $("#excel").change(function(evt){
            //
            //     var files = evt.target.files; // FileList object
            //     var xl2json = new ExcelToJSON();
            //     xl2json.parseExcel(files[0]);
            // })


        })
        // document.getElementById('excel').addEventListener('change', handleFileSelect, false);

    </script>

@endsection

