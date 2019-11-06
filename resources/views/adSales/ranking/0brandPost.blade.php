@extends('layouts.mirror')
@section('title', 'Ranking Brand')
@section('head')	
	<script src="/js/rankingBrand.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('brandPost') }}" runat="server" onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						<div class="col">
							<label class="labelLeft bold"> Region: </label>
							@if($errors->has('region'))
								<label style="color: red;">* Required</label>
							@endif
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($salesRegion)}}							
							@else
								{{$render->regionFiltered($salesRegion, $regionID, $special)}}
							@endif
						</div>
						<div class="col">
							<label class="labelLeft bold"> Type: </label>
							@if($errors->has('type'))
								<label style="color: red;">* Required</label>
							@else
								{{$render->type()}}
							@endif
						</div>
						<div class="col">
							<label class="labelLeft bold"> Brand: </label>
							@if($errors->has('brands'))
								<label style="color: red">* Required</label>
							@endif
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label class="labelLeft bold">Months:</label>
							@if($errors->has('month'))
								<label style="color: red">* Required</label>
							@endif
							{{$render->months()}}
						</div>
						<div class="col">
							<label class="labelLeft bold"> Currency: </label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->currency()}}
						</div>
						<div class="col">
							<label class="labelLeft bold"> Value: </label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->value2()}}
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="row justify-content-end mt-2">
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm" style="color: #0070c0;font-size: 22px;">
				<div style="float: right;"> {{$rName}} - {{ucfirst($type)}} Brand Ranking </div>
			</div>
			<div class="col-sm">
				<button id="excel" type="button" class="btn btn-primary" style="width: 100%">
					Generate Excel
				</button>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row mt-2 justify-content-center">
			<div class="col">
				{{$render->assembler($mtx, $pRate, $value, $rtr, $names)}}
			</div>
		</div>
	</div>

	<div id="vlau"></div>

	<script type="text/javascript">
		$(document).ready(function(){
			
			var months = <?php echo json_encode($months); ?>;
            var type = "{{$type}}";
            var value = "{{$value}}";
            var currency = <?php echo json_encode($pRate); ?>;
            var region = "{{$region}}";
            var brands = <?php echo json_encode($brands); ?>;

			ajaxSetup();

			@for($b = 0; $b < sizeof($brand); $b++)
				$(document).on('click', "#"+"{{$brand[$b]['name']}}", function(){

                    var name = $(this).text();

                    if ($("#sub"+"{{$brand[$b]['name']}}").css("display") == "none") {

                        $.ajax({
                            url: "/ajaxRanking/brandSubRanking",
                            method: "POST",
                            data: {name, months, type, value, currency, region, brands},
                            success: function(output){
                                $("#sub"+"{{$brand[$b]['name']}}").html(output);
                                $("#sub"+"{{$brand[$b]['name']}}").css("display", "");
                            },
                            error: function(xhr, ajaxOptions,thrownError){
                                alert(xhr.status+" "+thrownError);
                            }
                        });
                    }else{
                    	$("#sub"+"{{$brand[$b]['name']}}").html(" ");
                        $("#sub"+"{{$brand[$b]['name']}}").css("display", "none");
                    }
                });
            @endfor

            $("#excel").click(function(event){

				var regionExcel = <?php echo $regionExcel; ?>;
				var valueExcel = "<?php echo $valueExcel; ?>";
				var currencyExcel = "<?php echo base64_encode(json_encode($currencyExcel)); ?>";
				var typeExcel = "<?php echo $typeExcel; ?>";
				var monthsExcel = "<?php echo base64_encode(json_encode($monthsExcel)); ?>";
				var brandsExcel = "<?php echo base64_encode(json_encode($brandsExcel)); ?>";
				var title = "<?php echo $title; ?>";

				var div = document.createElement('div');
				var img = document.createElement('img');
				img.src = '/loading_excel.gif';
				div.innerHTML = "Generating Excel...<br/>";
				div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;        background-image: url("/Loading.gif");        background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
				div.appendChild(img);
				document.body.appendChild(div);

				$.ajax({
					xhrFields: {
						responseType: 'blob',
					},
					url: "/generate/excel/brand",
					type: "POST",
					data: {regionExcel, valueExcel, currencyExcel, typeExcel,  monthsExcel, brandsExcel, title},
					/*success: function(output){
						$("#vlau").html(output);
					},*/
					success: function(result, status, xhr){
						var disposition = xhr.getResponseHeader('content-disposition');
				        var matches = /"([^"]*)"/.exec(disposition);
				        var filename = (matches != null && matches[1] ? matches[1] : title);

						// The actual download
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
					error: function(xhr, ajaxOptions,thrownError){
						document.body.removeChild(div);
                        alert(xhr.status+" "+thrownError);
                    }
				});
			});
		});
	</script>


@endsection
