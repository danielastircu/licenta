<div id="viewProductModal" class="modal inmodal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Product</h4>
            </div>
            <form method="post" id="addProductForm">
                <div class="modal-body">
                    <h3 class="text-center blue">General Data</h3>
                    <table class="general">
                        <tbody>
                        <tr>
                            <th>Id:</th>
                            <td>{{$data['product']['id']}}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{$data['product']['name']}}</td>
                        </tr>
                        <tr>
                            <th>Energy ( kJ ):</th>
                            <td>@if( $data['product']['kJ']) {{ $data['product']['kJ']}} @else N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Energy ( kCal )</th>
                            <td>@if( $data['product']['kCal']) {{ $data['product']['kCal']}} @else N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Fat:</th>
                            <td>@if( $data['product']['fat']) {{ $data['product']['fat']}} @else N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Fat of which Saturated fatty acids:</th>
                            <td>@if( $data['product']['saturated']) {{ $data['product']['saturated']}} @else
                                    N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Fat of which Cholesterol:</th>
                            <td>@if( $data['product']['cholesterot']) {{ $data['product']['cholesterot']}} @else
                                    N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Fat of which Monounsaturated fatty acids:</th>
                            <td>@if( $data['product']['monounsaturated']) {{ $data['product']['monounsaturated']}} @else
                                    N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Fat of which Polyunsaturated fatty acids:</th>
                            <td>@if( $data['product']['polyunsaturated']) {{ $data['product']['polyunsaturated']}} @else
                                    N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Fat of which Trans-fatty acids::</th>
                            <td>@if( $data['product']['trans']) {{ $data['product']['trans']}} @else N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Carbohydrates:</th>
                            <td>@if( $data['product']['carbohydrates']) {{ $data['product']['carbohydrates']}} @else
                                    N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Carbohydrates which Sugar:</th>
                            <td>@if( $data['product']['sugar']) {{ $data['product']['sugar']}} @else N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Carbohydrates of which Polyhydric alcohols:</th>
                            <td>@if( $data['product']['polyhydric']) {{ $data['product']['polyhydric']}} @else
                                    N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Carbohydrates of which Starch:</th>
                            <td>@if( $data['product']['starch']) {{ $data['product']['starch']}} @else N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Dietary Fibre(Fiber):</th>
                            <td>@if( $data['product']['fiber']) {{ $data['product']['fiber']}} @else N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Protein:</th>
                            <td>@if( $data['product']['protein']) {{ $data['product']['protein']}} @else N/A @endif</td>
                        </tr>
                        <tr>
                            <th>Salt:</th>
                            <td>@if( $data['product']['salt']) {{ $data['product']['salt']}} @else N/A @endif</td>
                        </tr>
                        </tbody>
                    </table>

                    @foreach($data['productDetails'] as $key => $row)
                        <h3 class="text-center blue">{{strtoupper($key)}} Data</h3>
                        <table class="languages">
                            <tbody>
                            <tr>
                                <th>Description:</th>
                                <td>@if($row['description']){{$row['description']}} @else N/A @endif</td>
                            </tr>
                            <tr>
                                <th>Ingredients:</th>
                                <td>@if($row['ingredients']){{$row['ingredients']}} @else N/A @endif</td>
                            </tr>
                            <tr>
                                <th>Allergens:</th>
                                <td>@if($row['allergens']){{$row['allergens']}} @else N/A @endif</td>
                            </tr>
                            </tbody>
                        </table>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="addProductClose" data-dismiss="modal">Close
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>