                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
<div id="editProductModal" class="modal inmodal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Product</h4>
            </div>
            <form method="post" id="editProductForm">
                <div class="modal-body">
                    <div class="row row-eq-height">
                        <div class="col-md-8">
                            <form id="editProfilePictureForm" role="form" action="#" class="form-horizontal"
                                  enctype="multipart/form-data">
                                <div class="ibox-content">
                                    <p class="m-b-20">

                                    </p>

                                    <div class=" text-center cropper-btn">
                                        <div class="img-preview img-preview-sm"></div>

                                        <div class="m-t-30">
                                            <label title="Upload image file" for="inputImage"
                                                   class="btn btn-primary font-weight-700">
                                                <input type="file" accept="image/*" name="file" id="inputImage"
                                                       class="hide">
                                                Upload*
                                            </label>

                                            <label title="Reset crop" id="resetCrop"
                                                   class="btn btn-primary font-weight-700">Reset</label>

                                        </div>
                                        <div class="btn-group  m-t-20">
                                            <button class="btn btn-info font-weight-600" id="zoomIn"
                                                    type="button">Zoom in
                                            </button>
                                            <button class="btn btn-info font-weight-600" id="zoomOut"
                                                    type="button">Zoom out
                                            </button>
                                            <button class="btn btn-info font-weight-600 m-t-10"
                                                    id="rotateLeft"
                                                    type="button">Rotate left
                                            </button>
                                            <button class="btn btn-info font-weight-600 m-t-10"
                                                    id="rotateRight"
                                                    type="button">Rotate Right
                                            </button>
                                        </div>
                                        <div class="img-upload-limit">
                                        </div>
                                    </div>
                                    <div class="">

                                        <div class="image-crop m-b-20 ">
                                            <img id="image" name='profile-picture'
                                                 src="" style="max-width:100%"
                                            >
                                        </div>
                                    </div>


                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 border-tabs">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#generalContainer">General</a></li>
                                @foreach($data['productDetails'] as $key => $row)
                                    <li><a data-toggle="tab" href="#{{$key}}">{{strtoupper($key)}}</a></li>
                                @endforeach
                            </ul>

                            <div class="tab-content content-collapse">
                                <div id="generalContainer" class="tab-pane fade in active">
                                    @include('productGeneral', ['data'=> $data['product']])
                                </div>
                                @foreach($data['productDetails'] as $key => $row)
                                    <div id="{{$key}}" class="tab-pane fade">
                                        @include('productDetail', ['data'=> $row, 'language' => $key])
                                    </div>
                                @endforeach

                            </div>

                        </div>
                    </div>
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" id="id" value="{{ $data['product']['id'] }}">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="editProduct" data-dismiss="modal">Edit</button>
                    <button type="button" class="btn btn-default" id="editProductClose" data-dismiss="modal">Cancel
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>