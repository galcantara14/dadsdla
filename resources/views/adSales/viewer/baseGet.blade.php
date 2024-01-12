@extends('layouts.mirror')

@section('title', '@')

@section('head')
    <script src="/js/viewer.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
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
                            <label class="labelLeft"><span class="bold"> Year: </span></label>
                            @if($errors->has('year'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->year($regionID)}}                    
                        </div> 

                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Source: </span></label>
                            @if($errors->has('sourceDataBase'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->sourceDataBasev2()}}
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
                            {{$render->brand($brand)}}
                        </div>
                         
                                            
                    </div>

                    <div class="row">

                        <div class="col">
                            <label class='labelLeft'><span class="bold">Manager:</span></label>
                            @if($errors->has('manager'))
                                <label style="color: red;">* Required</label>
                            @endif
                                {{$render->director()}}
                        </div>

                        <div class="col">
                            <label class='labelLeft'><span class="bold">Sales Rep:</span></label>
                            @if($errors->has('salesRep'))
                                <label style="color: red;">* Required</label>
                            @endif
                                {{$render->salesRep()}}
                        </div>
{{--
                        <div class="col">
                            <label class='labelLeft'><span class="bold">Sales Rep Unit:</span></label>
                            @if($errors->has('salesRepUnit'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->salesRepUnit()}}
                        </div>
--}}                   
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
                            {{$render->currency($currency)}}
                        </div>

                        <!--<div class="col" id="stageFCSTCol" style="display:block;">
                            <label class="labelLeft"><span class="bold" id="stageFCST"> Stage: </span></label>
                            {{$render->stageFCST()}}
                        </div>-->
                        
                     
                        <div class="col">
                            <label> &nbsp; </label>
                            <input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">     
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="vlau"></div>
    </div>



@endsection



    

    