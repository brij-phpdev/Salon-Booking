<?php $this->load->view('admin/includes/head'); ?>
<div class="wrapper fullheight-side">
<?php $this->load->view('admin/includes/header');
$this->load->view('admin/includes/sidebar'); 
$this->load->view('admin/includes/navbar'); ?>

<!-- Page Content -->

<div class="main-panel">
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title"><?php echo esc($page_title) ?></h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="<?php anchor_to(GENERAL_CONTROLLER . '/dashboard') ?>">
                            <i class="flaticon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-home">
                        <a href="<?php anchor_to(COUPONS_CONTROLLER) ?>">
                        <?php echo esc($page_title) ?>
                        </a>
                    </li>
                </ul>
            </div>
            <?php $this->load->view('admin/includes/alert'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title float-left">Coupons</div>
                            <a href="<?php anchor_to(COUPONS_CONTROLLER . '/addcoupon') ?>" class="btn btn-primary float-right"><i class="fas fa-plus mr-2"></i> Add Coupon</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped mt-3">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Code</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Valid From</th>
                                            <th scope="col">Valid To</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!$coupons){?>
                                            <tr>
                                                <td colspan="6" class="text-center"><h4 class="text-muted">No Coupon Found</h4></td>
                                            </tr>
                                        <?php } else{?>
                                        <?php foreach ($coupons as $coupon ){ ?>
                                        <tr>
                                            <td><?php echo esc($coupon['id'], true) ?></td>
                                            <td><div class="couponDetails"><?php echo esc($coupon['name'], true) ?></div></td>
                                            <td><?php echo esc($coupon['code'], true) ?> Years</td>
                                            <td><?php echo esc($coupon['description'], true) ?> Years</td>
                                            <td><?php echo esc($coupon['starts_at'], true) ?></td>
                                            <td><?php echo esc($coupon['expires_at'], true) ?></td>
                                            <td>
                                                <a href="<?php anchor_to(COUPONS_CONTROLLER . '/editcoupon/' . $coupon['id']) ?>" data-toggle="tooltip" data-placement="top" title="Edit Coupon" class="btn btn-link btn-primary btn-lg">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-link btn-danger deleteCoupon" data-toggle="tooltip" data-placement="top" title="Delete" value="<?php echo esc($coupon['id'], true) ?>"><i class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                        <?php } }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End Page Content -->

</div>
<?php $this->load->view('admin/includes/foot'); ?>
<script type="text/javascript" src="<?php admin_assets('js/plugin/sweetalert/sweetalert.min.js') ?>"></script>
<script type="text/javascript" src="<?php admin_assets('js/includes/alerts.js') ?>"></script>
<?php $this->load->view('admin/includes/footEnd'); ?>