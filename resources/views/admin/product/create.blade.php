@extends('admin.layout.master')

@section('title','Manage Product')
@section('body')


                <!-- Main -->
                <div class="app-main__inner">

                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-ticket icon-gradient bg-mean-fruit"></i>
                                </div>
                                <div>
                                    Product
                                    <div class="page-title-subheading">
                                        View, create, update, delete and manage.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <form method="post" action="admin/product"  enctype="multipart/form-data">
                                       @csrf

                                        <div class="position-relative row form-group">
                                            <label for="image"
                                                   class="col-md-3 text-md-right col-form-label">Product Image</label>
                                            <div class="col-md-9 col-xl-8">
                                                <img style="height: 200px; cursor: pointer;"
                                                     class="thumbnail rounded-circle" data-toggle="tooltip"
                                                     title="Click to change the image" data-placement="bottom"
                                                     src="dashboard/assets/images/add-image-icon.jpg" alt="Avatar">
                                                <input name="image" type="file" onchange="changeImg(this)"
                                                       class="image form-control-file" style="display: none;" value="">
                                                <input type="hidden" name="image_old" value="">
                                                <small class="form-text text-muted">
                                                    Click on the image to change (required)
                                                </small>
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="brand_id"
                                                class="col-md-3 text-md-right col-form-label">Brand</label>
                                            <div class="col-md-9 col-xl-8">
                                                <select required name="brand_id" id="brand_id" class="form-control">
                                                    <option value="">-- Brand --</option>
                                                    @foreach($brands as $brand)
                                                        <option value={{$brand->id}}>
                                                            {{$brand->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="product_category_id"
                                                class="col-md-3 text-md-right col-form-label">Category</label>
                                            <div class="col-md-9 col-xl-8">
                                                <select required name="product_category_id" id="product_category_id" class="form-control">
                                                    <option value="">-- Category --</option>
                                                   @foreach($productCategories as $productCategory)
                                                        <option value={{$productCategory->id}}>
                                                            {{$productCategory->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="color" class="col-md-3 text-md-right col-form-label">Color</label>
                                            <div class="col-md-9 col-xl-8">
                                                <input required name="color" id="color" placeholder="Color" type="text"
                                                       class="form-control" value="">
                                            </div>
                                        </div>
                                    <div class="position-relative row form-group">
                                        <label for="size" class="col-md-3 text-md-right col-form-label">Size / Qty </label>
                                        <span class="col-md-1">
                                            <input disabled  type="text"
                                                   class="form-control" value="S">

                                            <input required name="sizeS" id="size" placeholder="Qty S" type="number"
                                                   class="form-control" value="">
                                        </span>
                                        <span class="col-md-1">
                                            <input disabled  type="text"
                                                   class="form-control" value="XS">

                                            <input required name="sizeXS" id="size" placeholder="Qty XS" type="number"
                                                   class="form-control" value="">
                                        </span>
                                        <span class="col-md-1">
                                            <input disabled  type="text"
                                                   class="form-control" value="M">

                                            <input required name="sizeM" id="size" placeholder="Qty M" type="number"
                                                   class="form-control" value="">
                                        </span>
                                        <span class="col-md-1">
                                            <input disabled  type="text"
                                                   class="form-control" value="L">

                                            <input required name="sizeL" id="size" placeholder="Qty L" type="number"
                                                   class="form-control" value="">
                                        </span>
                                        <span class="col-md-1">
                                            <input disabled  type="text"
                                                   class="form-control" value="XL">

                                            <input required name="sizeXL" id="size" placeholder="Qty XL" type="number"
                                                   class="form-control" value="">
                                        </span>
                                    </div>

                                        <div class="position-relative row form-group">
                                            <label for="name" class="col-md-3 text-md-right col-form-label">Product Name</label>
                                            <div class="col-md-9 col-xl-8">
                                                <input required name="name" id="name" placeholder="Name" type="text"
                                                    class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="content"
                                                class="col-md-3 text-md-right col-form-label">Content</label>
                                            <div class="col-md-9 col-xl-8">
                                                <input required name="content" id="content"
                                                    placeholder="Content" type="text" class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="price"
                                                class="col-md-3 text-md-right col-form-label">Price</label>
                                            <div class="col-md-9 col-xl-8">
                                                <input required name="price" id="price"
                                                    placeholder="Price" type="text" class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="discount"
                                                class="col-md-3 text-md-right col-form-label">Discount</label>
                                            <div class="col-md-9 col-xl-8">
                                                <input required name="discount" id="discount"
                                                    placeholder="Discount" type="text" class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="weight"
                                                class="col-md-3 text-md-right col-form-label">Weight</label>
                                            <div class="col-md-9 col-xl-8">
                                                <input required name="weight" id="weight"
                                                    placeholder="Weight" type="text" class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="sku"
                                                class="col-md-3 text-md-right col-form-label">SKU</label>
                                            <div class="col-md-9 col-xl-8">
                                                <input required name="sku" id="sku"
                                                    placeholder="SKU" type="text" class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="tag"
                                                class="col-md-3 text-md-right col-form-label">Tag</label>
                                            <div class="col-md-9 col-xl-8">
                                                <input required name="tag" id="tag"
                                                    placeholder="Tag" type="text" class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="featured"
                                                class="col-md-3 text-md-right col-form-label">Featured</label>
                                            <div class="col-md-9 col-xl-8">
                                                <div class="position-relative form-check pt-sm-2">
                                                    <input name="featured" id="featured" type="checkbox" value="1" class="form-check-input">
                                                    <label for="featured" class="form-check-label">Featured</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="description"
                                                class="col-md-3 text-md-right col-form-label">Description</label>
                                            <div class="col-md-9 col-xl-8">
                                                <textarea class="form-control" name="description" id="description" placeholder="Description"></textarea>
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group mb-1">
                                            <div class="col-md-9 col-xl-8 offset-md-5">
                                                <a href="#" class="border-0 btn btn-outline-danger mr-1">
                                                    <span class="btn-icon-wrapper pr-1 opacity-8">
                                                        <i class="fa fa-times fa-w-20"></i>
                                                    </span>
                                                    <span>Cancel</span>
                                                </a>

                                                <button type="submit"
                                                    class="btn-shadow btn-hover-shine btn btn-primary">
                                                    <span class="btn-icon-wrapper pr-2 opacity-8">
                                                        <i class="fa fa-download fa-w-20"></i>
                                                    </span>
                                                    <span>Save</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- End Main -->

@endsection
