@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>View Product</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.products') }}">
                            <div class="text-tiny">Products</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">View Product</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{ route('admin.product.update')  }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $product->id }}">
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Product name <span class="tf-color-1"></span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter product name" name="name" tabindex="0"
                            value=" {{ $product->name }}" aria-required="true" required="" readonly>
                        {{-- <div class="text-tiny">Do not exceed 100 characters when entering the
                            product name.</div> --}}
                    </fieldset>
                    @error('name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">Slug <span class="tf-color-1"></span></div>
                        <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" tabindex="0"
                            value="{{ $product->slug }}" aria-required="true" required="" readonly>
                        {{-- <div class="text-tiny">Do not exceed 100 characters when entering the
                            product name.</div> --}}
                    </fieldset>
                    @error('slug')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    {{-- <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Category <span class="tf-color-1">*</span>
                            </div>
                            <div class="select">
                                <select class="" name="category_id">
                                    <option>Choose category</option>
                                    <option value="1">Category1</option>
                                    <option value="2">Category2</option>
                                    <option value="3">Category3</option>
                                    <option value="4">Category4</option>

                                </select>
                            </div>
                        </fieldset>
                        <fieldset class="brand">
                            <div class="body-title mb-10">Brand <span class="tf-color-1">*</span>
                            </div>
                            <div class="select">
                                <select class="" name="brand_id">
                                    <option>Choose Brand</option>
                                    <option value="1">Brand1</option>
                                    <option value="2">Brand2</option>
                                    <option value="3">Brand3</option>
                                    <option value="4">Brand4</option>

                                </select>
                            </div>
                        </fieldset>
                    </div> --}}

                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Short Description <span class="tf-color-1"></span></div>
                        <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" tabindex="0" value=""
                            aria-required="true" required="" readonly>{{ $product->short_description }}</textarea>
                        {{-- <div class="text-tiny">Do not exceed 100 characters when entering the
                            product name.</div> --}}
                    </fieldset>
                    @error('short_description')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="description">
                        <div class="body-title mb-10">Description <span class="tf-color-1"></span>
                        </div>
                        <textarea class="mb-10" name="description" placeholder="Description" tabindex="0" aria-required="true" value=""
                            required="" readonly>{{ $product->description }}</textarea>
                        {{-- <div class="text-tiny">Do not exceed 100 characters when entering the
                            product name.</div> --}}
                    </fieldset>
                    @error('description')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </div>
                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Images <span class="tf-color-1"></span>
                        </div>
                        <div class="upload-image flex-grow">
                            @if ($product->image)
                            <div class="item" id="imgpreview">
                                <img src="{{ asset('uploads/products') }}/{{ $product->image }}" class="effect8"
                                    alt="{{ $product->name }}">
                            </div>
                            @endif
                            {{-- <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click
                                            to browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div> --}}
                        </div>
                    </fieldset>
                    @error('image')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset>
                        <div class="body-title mb-10">Gallery Images</div>
                        <div class="upload-image mb-16">
                            @if ($product->images)
                                @foreach (explode(',', $product->images) as $img)
                                <div class="item gitems">
                                    <img src="{{ asset('uploads/products') }}/{{$img}}" alt="">
                                </div>
                                @endforeach
                            @endif
                            {{-- <div id="galUpload" class="item up-load">
                                <label class="uploadfile" for="gFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="text-tiny">Drop your images here or select <span class="tf-color">click
                                            to browse</span></span>
                                    <input type="file" id="gFile" name="images[]" accept="image/*"
                                        multiple="">
                                </label>
                            </div> --}}
                        </div>
                    </fieldset>
                    @error('images')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Regular Price <span class="tf-color-1"></span></div>
                            <input class="mb-10" type="text" placeholder="Enter regular price" name="regular_price"
                                tabindex="0" value="Rp{{ number_format($product->regular_price, 3, '.', '.') }}" aria-required="true"
                                required="" readonly>
                        </fieldset>
                        @error('regular_price')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        @role('super-admin')
                        <fieldset class="name">
                            <div class="body-title mb-10">Sale Price </div>
                            <input class="mb-10" type="text" placeholder="" name="sale_price"
                                tabindex="0" value="" aria-required="true" readonly>
                        </fieldset>
                        @error('sale_price')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        @endrole
                    </div>


                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">SKU <span class="tf-color-1"></span>
                            </div>
                            <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" tabindex="0"
                                value="{{ $product->SKU }}" aria-required="true" required="" readonly>
                        </fieldset>
                        @error('SKU')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        @role('super-admin')
                        <fieldset class="name">
                            <div class="body-title mb-10">Quantity <span class="tf-color-1"></span>
                            </div>
                            <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity"
                                tabindex="0" value="{{ $product->quantity }}" aria-required="true" required="" readonly>
                        </fieldset>
                        @error('quantity')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        @endrole
                    </div>
                    @role('super-admin')
                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Stock</div>
                            <div class="select mb-10">
                                <select class="" name="stock_status" disabled>
                                    <option value="instock" {{ $product->stock_status == 'instock' ? 'selected' : '' }}>
                                        InStock</option>
                                    <option value="outofstock"
                                        {{ $product->stock_status == 'outofstock' ? 'selected' : '' }}>Out of Stock
                                    </option>
                                </select>
                            </div>
                        </fieldset>
                        @error('stock_status')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="name">
                            <div class="body-title mb-10">Featured</div>
                            <div class="select mb-10">
                                <select class="" name="featured" disabled>
                                    <option value="0" {{ $product->featured == '0' ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $product->featured == '1' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('featured')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>
                    @endrole
                    {{-- <div class="cols gap10">
                        <a href="{{ route('admin.products') }}">
                            <button class="tf-button w-full" type="">Back to Products</button>
                        </a>
                    </div> --}}
                </div>
            </form>
            <!-- /form-add-product -->
        </div>
        <!-- /main-content-wrap -->
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $("#myFile").on("change", function(e) {
                const photoInp = $("#myFile");
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr("src", URL.createObjectURL(file));
                    $("#imgpreview").show();

                }
            });

            $("#gFile").on("change", function(e) {
                const photoInp = $("#gFile");
                const gphotos = this.files;
                $.each(gphotos, function(key, val) {
                    $("#galUpload").prepend(
                        `<div class="item gitems"><img src="${URL.createObjectURL(val)}" /></div>`
                    );
                });
            });

            $("input[name='name']").on("change", function(e) {
                $("input[name='slug']").val(stringToSlug($(this).val()));
            });

        });

        function stringToSlug(text) {
            return text.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }
    </script>
@endpush
