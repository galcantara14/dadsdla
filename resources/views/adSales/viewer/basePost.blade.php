@extends('layouts.mirror')
@section('title', 'Base Viewer')
@section('head')
    <script src="/js/viewer.js"></script>
    <?php 
        include(resource_path('views/auth.php'));
        use App\base;
        $bs = new base();
    ?>
@endsection
@section('content')

	<div class="container-fluid">
        <div class="row">
            <div class="col">
                <form method="POST" action="{{ route('basePost') }}" runat="server"  onsubmit="ShowLoading()">
                    @csrf
                    <div class="row">                        

                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Region: </span></label>

                            @if($errors->has('region'))
                                <label style="color: red;">* Required</label>
                            @endif

                            @if($userLevel == 'L0' || $userLevel == 'SU')
                                {{$render->region($region)}}                            
                            @else
                                {{$render->regionFiltered($region, $regionID, $special)}}
                            @endif
                        </div>


                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Source: </span></label>
                            @if($errors->has('sourceDataBase'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->sourceDataBasev2()}}
                        </div>
                        
                        <div class="col" id="especificNumberCol" style="display:block;">
                            <label class="labelLeft"><span class="bold" id="especificNumberName"> Map Number: </span></label>
                            {{$render->especificNumber($brand)}}
                        </div>
                        
                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Year: </span></label>
                            @if($errors->has('year'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->year($regionID)}}                    
                        </div>  
                        <div class="col">
                            <label class='labelLeft'><span class="bold">Months:</span></label>
                            @if($errors->has('month'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->months()}}
                        </div>
                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Brand: </span></label>
                            @if($errors->has('brand'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->brand($brands)}}
                        </div>
                         
                                            
                    </div>

                    <div class="row">
                        <div class="col">
                            <label class='labelLeft'><span class="bold">Sales Rep:</span></label>
                            @if($errors->has('salesRep'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->salesRep()}}
                        </div>
                        

                        <div class="col">
                            <label class='labelLeft'><span class="bold">Agency:</span></label>
                            @if($errors->has('agency'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->AgencyForm()}}
                        </div>

                        <div class="col">
                            <label class='labelLeft'><span class="bold">Client:</span></label>
                            @if($errors->has('client'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->ClientForm()}}

                            <input type="hidden" name="sizeOfClient" id="sizeOfClient" value="">
                        </div>

                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Currency: </span></label>
                            @if($errors->has('currency'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->currency($currencies)}}
                        </div>

                        <div class="col" id="stageFCSTCol" style="display:block;">
                            <label class="labelLeft"><span class="bold" id="stageFCST"> Stage </span></label>
                            {{$render->stageFCST()}}
                        </div>

                        <div class="col" >
                            <label class="labelLeft"><span class="bold"> &nbsp; </span></label>                            
                        </div>
                        <div class="col">
                            <label> &nbsp; </label>
                            <input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">     
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>

    	<div class="row justify-content-end mt-2">
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col" style="visibility: hidden;">
                <select id="ExcelPDF" class="form-control">
                    <option value="Excel">Excel</option>
                    <option value="PDF">PDF</option>
                </select>
            </div>
            
            <div class="col-4" style="color: #0070c0; font-size:22px">
                <span style="float: right; margin-right: 2.5%;">Data Current Through: <?php echo $bs->sourceCMAPS(); ?></span>
            </div> 
            <div class="col-2">
                <button type="button" id="excel" class="btn btn-primary" style="width: 100%">
                    Generate Excel
                </button>               
            </div>           
    	</div>
    </div>

        <div class="container-fluid">
            <div class="row mt-4">
                <div class="col table-responsive">
                    @if($mtx)
                        {{$bRender->assemble($mtx,$months,$year,$regions,$brand,$source,$currencies,$total)}}
                    @else
                        THE IS NO DATA TO THE SELECTED YEAR !!!
                    @endif
                </div>
            </div>
        </div>
        
        <div id="vlau"></div>

        <script type="text/javascript">
            
            $(document).ready(function(){

                ajaxSetup();

                $("#ExcelPDF").change(function(event){
                    if ($("#ExcelPDF").val() == "PDF") {
                        $("#excel").text("Generate PDF");
                    }else{
                        $("#excel").text("Generate Excel");
                    }
                });


                $('#excel').click(function(event){

                    var regionExcel = "<?php echo $regionExcel; ?>";
                    var sourceExcel = "<?php echo $sourceExcel; ?>";
                    var yearExcel = "<?php echo base64_encode(json_encode($yearExcel)); ?>";
                    var monthExcel = "<?php echo base64_encode(json_encode($monthExcel)); ?>";
                    var brandExcel = "<?php echo base64_encode(json_encode($brandExcel)); ?>";
                    var salesRepExcel = "<?php echo base64_encode(json_encode($salesRepExcel)); ?>";
                    var agencyExcel = "<?php echo base64_encode(json_encode($agencyExcel)); ?>";
                    var clientExcel = "<?php echo base64_encode(json_encode($clientExcel)); ?>";
                    var currencyExcel = "<?php echo $currencyExcel; ?>";
                    var valueExcel = "<?php echo $valueExcel; ?>";
                    var especificNumber = "<?php echo $especificNumberExcel; ?>";
                    var mtx = <?php echo (json_encode($mtx)); ?>;
                    var total = <?php echo (json_encode($total)); ?>;

                    var div = document.createElement('div');
                    var img = document.createElement('img');
                    img.src = '/loading_excel.gif';
                    div.innerHTML ="Generating File...</br>";
                    div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;    background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
                    div.appendChild(img);
                    document.body.appendChild(div);

                    var typeExport = $("#ExcelPDF").val();
                    //var typeExport = "Excel";
                    var auxTitle = "<?php echo $title; ?>";

                    if (typeExport == "Excel") {
                        var title = "<?php echo $titleExcel; ?>";
                        
                        $.ajax({
                            xhrFields: {
                                responseType: 'blob',
                            },
                            url: "/generate/excel/viewer/vBase",
                            type: "POST",
                            data: {regionExcel,sourceExcel,yearExcel,monthExcel,brandExcel,salesRepExcel,agencyExcel,clientExcel,currencyExcel,valueExcel,title,especificNumber, typeExport, auxTitle, mtx,total},
                            /*success: function(output){
                                $("#vlau").html(output);
                            },*/
                            success: function(result,status,xhr){
                                var disposition = xhr.getResponseHeader('content-disposition');
                                var matches = /"([^"]*)"/.exec(disposition);
                                var filename = (matches != null && matches[1] ? matches[1] : title);

                                //download
                                var blob = new Blob([result], {
                                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                });
                                var link = document.createElement('a');
                                link.href = window.URL.createObjectURL(blob);
                                link.download = filename;

                                document.body.appendChild(link);

                                link.click();
                                document.body.removeChild(link);
                                document.body.removeChild(div);
                            },
                            error: function(xhr, ajaxOptions, thrownError){
                                document.body.removeChild(div);
                                alert(xhr.status+" "+thrownError);
                            }
                        });
                    }else{
                        var title = "<?php echo $titlePdf; ?>";
                    
                        $.ajax({
                            xhrFields: {
                                responseType: 'blob',
                            },
                            url: "/generate/excel/viewer/vBase",
                                type: "POST",
                                data: {regionExcel,sourceExcel,yearExcel,monthExcel,brandExcel,salesRepExcel,agencyExcel,clientExcel,currencyExcel,valueExcel,title,especificNumber, typeExport, auxTitle,mtx,total},
                            /*success: function(output){
                                $("#vlau").html(output);
                            },*/
                            success: function(result, status, xhr){
                                var disposition = xhr.getResponseHeader('content-disposition');
                                var matches = /"([^"]*)"/.exec(disposition);
                                var filename = (matches != null && matches[1] ? matches[1] : title);
                                var link = document.createElement('a');
                                link.href = window.URL.createObjectURL(result);
                                link.download = filename;

                                document.body.appendChild(link);

                                link.click();
                                document.body.removeChild(link);
                                document.body.removeChild(div);
                            },
                            error: function(xhr, ajaxOptions,thrownError){
                                document.body.removeChild(div);
                                alert(xhr.status+" "+thrownError);
                            }
                        });
                    }
                });
            });

        </script>
@endsection