@extends('layouts.mirror')
@section('title', 'Core')
@section('head')	
<script src="/js/performance.js"></script>
    <?php include(resource_path('views/auth.php')); 
    ?>

<style>
	table{
		text-align: center;
	}
</style>
@endsection
@section('content')
	<div class="container-fluid">		
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('corePerformancePost') }} " runat="server"  onsubmit="ShowLoading()" >
					@csrf
					<div class="row justify-content-center">
						<div class="col">	
							<label class='labelLeft'><span class="bold">Region:</span></label>
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
							<label class='labelLeft'><span class="bold">Year:</span></label>
							@if($errors->has('year'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->year()}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Tiers:</span></label>
							@if($errors->has('brand'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->tiers()}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Brands:</span></label>
							@if($errors->has('brand'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Months:</span></label>
							@if($errors->has('month'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->months()}}
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col">
							<label class='labelLeft'><span class="bold">Sales Rep Group:</span></label>
							@if($errors->has('salesRepGroup'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->salesRepGroup($salesRepGroup)}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Sales Rep:</span></label>
							@if($errors->has('salesRep'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->salesRep()}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Currency:</span></label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->currency($currency)}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Value:</span></label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->value()}}
						</div>
						<div class="col">
							<label class='labelLeft'> &nbsp; </label>
							<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
						</div>
					</div>
				</form>
				<div class="row justify-content-end mt-2">
					<div class="col-sm"></div>
					<div class="col-sm"></div>
					<div class="col-sm"></div>
					<div class="col-sm" style="color: #0070c0;font-size: 22px">
						<span style="float: right;"> Core Performance </span>
					</div>

					<div class="col-sm">
						<button id="excel" type="button" class="btn btn-primary" style="width: 100%">
							Generate Excel
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col" style='width:100%; zoom:80%;' >
				<div class="form-group" style='width:100%;'>
					<div class="form-inline" style='width:100%; margin-left: 1.2%; margin-right: auto;'>
						<div class="row" style="width: 100%;">
							<div class="col-sm" id="type1" style=" width: 100%; margin-top: 2%; display: block;">
								{{$render->case1($mtx,$cYear)}}
							</div>
						</div>
						<div class="row" style="width: 100%;">
							<div class="col-sm" id="type2" style=" width: 100%; margin-top: 2%; display: none;">
								{{$render->case2($mtx,$cYear)}}
							</div>
						</div>
						<div class="row" style="width: 100%;">
							<div class="col-sm" id="type3" style=" width: 100%; margin-top: 2%; display: none;">
								{{$render->case3($mtx,$cYear)}}
							</div>
						</div>
						<div class="row" style="width: 100%;">
							<div class="col-sm" id="type4" style=" width: 100%; margin-top: 2%; display: none;">
								{{$render->case4($mtx,$cYear)}}
							</div>
						</div>
					</div>
				</div>					
			</div>
		</div>
	</div>

	<div id="vlau"></div>

	<script>
		var matrix = [true,true];
		
		$(document).ready(function(){   

			ajaxSetup();

			$("#excel").click(function(event){

				var regionExcel = <?php echo $regionExcel; ?>;
				var currencyExcel = "<?php echo base64_encode(json_encode($currencyExcel)); ?>";
				var yearExcel = "<?php echo $yearExcel; ?>";
				var valueExcel = "<?php echo $valueExcel; ?>";
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
					url: "/generate/excel/core",
					type: "POST",
					data: {regionExcel, valueExcel, yearExcel, currencyExcel, title},
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

			$(".tierClick").click(function(){
				if (matrix[0]) {
					matrix[0] = false;
				}else{
					matrix[0] = true;
				}
				loadMatrix(matrix);
			});
			
			$(".quarterClick").click(function(){
				if (matrix[1]) {
					matrix[1] = false;
				}else{
					matrix[1] = true;
				}
				loadMatrix(matrix);
			});

		});


		function loadMatrix(matrix){
			$("#type1").css("display","hidden");
			for (var i = 1; i < 5; i++) {
				$("#type"+i).css("display","none");
			}

			if(matrix[0] && matrix[1]){
				$("#type1").css("display","block");
			}else if(!matrix[0] && matrix[1]){
				$("#type2").css("display","block");
			}else if(matrix[0] && !matrix[1]){
				$("#type3").css("display","block");
			}else{
				$("#type4").css("display","block");
			}
		}

	</script>
@endsection