@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>All Offline Products</h3>
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
                        <div class="text-tiny">Offline Products</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search" action="{{ route('admin.search.product') }}" method="GET">
                            <fieldset class="name">
                                <input type="text" placeholder="Search here..." class="" name="query"
                                    id="search-input" tabindex="2" value="" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.add.offline.product') }}"><i
                            class="icon-plus"></i>Edit Stock</a>
                </div>
                @if (session('status'))
                    <div class="alert alert-success fs-3">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>SKU</th>
                                <th>Featured</th>
                                <th>Stock</th>
                                <th>Quantity</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="pname">
                                        <div class="image">
                                            <img src="{{ asset('uploads/products/thumbnails') }}/{{ $product->image }}"
                                                alt="{{ $product->name }}" class="image">
                                        </div>
                                        <div class="name">
                                            <a href="#" class="body-title-2">{{ $product->name }}</a>
                                            <div class="text-tiny mt-3">{{ $product->name }}</div>
                                        </div>
                                    </td>
                                    <td>Rp{{ number_format($product->regular_price, 3, '.', '.') }}</td>
                                    <td>{{ $product->SKU }}</td>
                                    <td>{{ $product->featured == 1 ? 'Yes' : 'No' }}</td>
                                    <td>
                                        @if (isset($oproducts[$product->id]) && $oproducts[$product->id] > 0)
                                            Instock
                                        @else
                                            Out of Stock
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($oproducts[$product->id]))
                                            {{ $oproducts[$product->id] }}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    {{-- <td>
                                        <div class="list-icon-function">
                                            <a href="{{ route('admin.product.view', ['id' => $product->id]) }}"
                                                target="_blank">
                                                <div class="item eye">
                                                    <i class="icon-eye"></i>
                                                </div>
                                            </a>

                                            <a href="{{ route('admin.product.edit', ['id' => $product->id]) }}">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <form action="{{ route('admin.product.delete', ['id' => $product->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="delete item text-danger">
                                                    <i class="icon-trash-2"></i>
                                                </div>
                                            </form>
                                        </div>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="9">
                                        Product not found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                    {{-- {{ $products->links('pagination::bootstrap-5') }} --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title: "Are you sure?",
                    text: "You want to delete this product?",
                    type: "warning",
                    buttons: ["Cancel", "Yes!"],
                    confirmButtonColor: "#dc3545",
                }).then(function(result) {
                    if (result) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
