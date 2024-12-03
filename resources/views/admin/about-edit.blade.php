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
            @if (session('success'))
                <div class="alert alert-success fs-3">
                    {{ session('success') }}
                </div>
            @endif
            <!-- form-add-product -->
            <form action="{{ route('about.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group mb-20 fs-3">
                    <label for="address">Address</label>
                    <textarea class="form-control fs-3" id="address" name="address" rows="3">{{ old('address', $about->address) }}</textarea>
                </div>
                <div class="form-group mb-20 fs-3">
                    <label for="story">Our Story</label>
                    <textarea class="form-control fs-4" id="story" name="story" rows="5">{{ old('story', $about->story) }}</textarea>
                </div>

                <div class="row fs-3 mb-20">
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

                <div class="form-group mb-20 fs-3">
                    <label for="about_laif">Laif Essential</label>
                    <textarea class="form-control fs-4" id="about_laif" name="about_laif" rows="4">{{ old('about_laif', $about->about_laif) }}</textarea>
                </div>

                <div class="row fs-3 mb-20">
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label for="email_laif">Email</label>
                            <textarea class="form-control fs-4" id="email_laif" name="email_laif" rows="3">{{ old('email_laif', $about->email_laif) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label for="phone_laif">Phone</label>
                            <textarea class="form-control fs-4" id="phone_laif" name="phone_laif" rows="3">{{ old('phone_laif', $about->phone_laif) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label for="instagram">Instagram</label>
                            <textarea class="form-control fs-4" id="instagram" name="instagram" rows="3">{{ old('instagram', $about->instagram) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="image">Change Image (max 2mb)</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <small class="fs-5">Current image: <img src="{{ asset('assets/images/about/' . $about->image) }}"
                            width="100">
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
