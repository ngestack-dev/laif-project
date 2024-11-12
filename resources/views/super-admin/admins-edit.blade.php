@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit Admin</h3>
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
                            <div class="text-tiny">Admins</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit Admin</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form class="tf-section-1 form-add-product" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.admins.update') }}">
                @csrf
                @method('PUT')
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Name <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="hidden" name="id" value="{{ $admin->id }}">
                        <input class="mb-10" type="text" placeholder="Enter name" name="name" tabindex="0"
                            value="{{ $admin->name }}" aria-required="true" required="">
                        <div class="text-tiny">Do not exceed 100 characters when entering the
                            name.</div>
                    </fieldset>
                    @error('name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="email">
                        <div class="body-title mb-10">Email <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="email" placeholder="Enter email" name="email" tabindex="0"
                            value=" {{ $admin->email }}" aria-required="true" required="">
                        <div class="text-tiny">Do not exceed 100 characters when entering the
                            email.</div>
                    </fieldset>
                    @error('email')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="position">
                        <div class="body-title mb-10">Position</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter position" name="position" tabindex="0"
                            value=" {{ $admin->position }}" aria-required="true" required="">
                        <div class="text-tiny">Do not exceed 100 characters when entering the
                            position.</div>
                    </fieldset>
                    @error('position')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="position">
                        <div class="body-title mb-10">New Password <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="password" placeholder="Enter password" name="password" tabindex="0"
                            value="" aria-required="true" required="">
                        <div class="text-tiny">New Password require min 8 characters.</div>
                    </fieldset>
                    @error('password')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="position">
                        <div class="body-title mb-10">Confirm New Password <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="password" placeholder="Enter password" name="password_confirmation"
                            tabindex="0" value="" aria-required="true" required="">
                    </fieldset>
                    @error('password')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Update admin</button>
                    </div>
                </div>
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
