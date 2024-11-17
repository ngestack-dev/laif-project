@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit About</h3>
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
                            <div class="text-tiny">About</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit About</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form action="{{ route('about.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group mb-4">
                    <label for="address">Address</label>
                    <textarea class="form-control fs-3" id="address" name="address" rows="3">{{ old('address', $about->address) }}</textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="our_story">Our Story</label>
                    <textarea class="form-control fs-4" id="our_story" name="story" rows="5">{{ old('our_story', $about->story) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="vision">Our Vision</label>
                            <textarea class="form-control fs-4" id="vision" name="vision" rows="3">{{ old('vision', $about->vision) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="mission">Our Mission</label>
                            <textarea class="form-control fs-4" id="mission" name="mission" rows="3">{{ old('mission', $about->mission) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="laif_essential">Laif Essential</label>
                    <textarea class="form-control fs-4" id="laif_essential" name="about_laif" rows="4">{{ old('laif_essential', $about->about_laif) }}</textarea>
                </div>

                <div class="form-group mb-4">
                    <label for="image">Change Image (max 2mb)</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <small>Current image: <img src="{{ asset('assets/images/about/' . $about->image) }}" width="100">
                    </small>
                    @error('image')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
            <!-- /form-add-product -->
        </div>
        <!-- /main-content-wrap -->
    </div>
@endsection

{{-- @push('scripts')
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
@endpush --}}
