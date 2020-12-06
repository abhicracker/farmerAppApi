<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor">Riders</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Riders</li>
                </ol>
            </div>
    </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
  


        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Riders</h4>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>User id</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Vehical Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                {riders}
                                <td>{id}</td>
                                <td>{name}</td>
                                <td>{phone_number}</td>
                                <td>{vehicle_number}</td>
                                <td><button type="button" class="btn btn-info btn-circle"><i class="fa fa-check"></i> </button>
                                <button type="button" class="btn btn-danger btn-circle"><i class="fa fa-link"></i> </button></td>    
                                </td></tr>
                                {/riders}
                            </tbody>
                    </table>
                </div>
            </div>
        </div>