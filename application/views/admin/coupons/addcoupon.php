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
                        <a href="<?php anchor_to(COUPONS_CONTROLLER) ?>">All Coupons</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-home">
                        <?php echo esc($page_title) ?>
                    </li>
                </ul>
            </div>
            <?php $this->load->view('admin/includes/alert'); ?>
            <div class="row">
                <div class="col-md-12">
                    <form enctype="multipart/form-data"  method="POST" action="<?php anchor_to(COUPONS_CONTROLLER . '/addcoupon') ?>">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Coupon Name <span class="text-danger">*</span></label>
                                    <?php echo form_error('name', '<br><span class="text-danger">', '</span>'); ?>
                                    <input class="form-control" type="text" id="name" name="name" placeholder="Coupon Name" value="<?php echo set_value('name')?>">
                                </div>
                                <div class="form-group">
                                    <label for="code">Coupon Code <span class="text-danger">*</span></label>
                                    <?php echo form_error('code', '<br><span class="text-danger">', '</span>'); ?>
                                    <input class="form-control" type="text" id="code" name="code" placeholder="Coupon Code" value="<?php echo set_value('code')?>">
                                </div>
                                <div class="form-group">
                                    <label for="description">Coupon Description <span class="text-danger">*</span></label>
                                    <?php echo form_error('description', '<br><span class="text-danger">', '</span>'); ?>
                                    <textarea id="coupon-content" name="description" class="form-control"><?php echo set_value('description')?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                       
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="max_uses">Max Uses <span class="text-danger">*</span></label>
                                                    <?php echo form_error('max_uses', '<br><span class="text-danger">', '</span>'); ?>
                                                    <input value="0" min="0" step="1" class="form-control" type="number" id="max_uses" name="max_uses" placeholder="The max uses this voucher has" value="<?php echo set_value('max_uses')?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="max_uses_user">Max Uses User <span class="text-danger">*</span></label>
                                                    <?php echo form_error('max_uses_user', '<br><span class="text-danger">', '</span>'); ?>
                                                    <input value="0" min="0" step="1" class="form-control" type="number" id="max_uses_user" name="max_uses_user" placeholder="How many times a user can use this voucher." value="<?php echo set_value('max_uses_user')?>">
                                                </div>
                                            </div>

                                            
                                        </div>
                                        <div class="row">
                                        <div class="col-12">
                                            <small>Leave it to zero '0' for no rule</small>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        
                                                <div class="form-group">
                                                    <label for="type">Type of Coupon<span class="text-danger">*</span></label>
                                                    <?php echo form_error('type', '<br><span class="text-danger">', '</span>'); ?>
                                                    
                                                    <select name="type" id="" class="form-control custom-select">
                                                <!--<option value="">Choose Category</option>-->
                                                <?php foreach($coupon_types as $coupon_type){ ?>
                                                    <option value="<?php echo esc($coupon_type, true)?>"><?php echo ucfirst(esc($coupon_type, true))?></option>
                                                <?php } ?>
                                            </select>
                                                </div>
                                    </div>
                                    <div class="col-md-6">
                                        
                                                <div class="form-group">
                                                    <label for="discount_amount">Discount Amount<span class="text-danger">*</span></label>
                                                    <?php echo form_error('discount_amount', '<br><span class="text-danger">', '</span>'); ?>
                                                    
                                                   <input class="form-control" type="text" id="discount_amount" name="discount_amount" placeholder="Discount Amount" value="<?php echo set_value('discount_amount')?>">
                                                </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        
                                                <div class="form-group">
                                                    <label for="type">Is Fixed<span class="text-danger">*</span></label>
                                                    <?php echo form_error('is_fixed', '<br><span class="text-danger">', '</span>'); ?>
                                                    <select name="is_fixed" id="is_fixed" class="form-control custom-select">
                                                <option value="">Choose Category</option>
                                                <option value="1">Price</option>
                                                <option value="0">Percentage</option>
                                            </select>
                                                </div>
                                    </div>
                                    <div class="col-md-6">
                                        
                                                <div class="form-group">
                                                    <label for="starts_at">Starts At<span class="text-danger">*</span></label>
                                                    <?php echo form_error('starts_at', '<br><span class="text-danger">', '</span>'); ?>
                                                    
                                                    <input class="form-control" type="datetime-local" id="starts_at" name="starts_at" placeholder="" value="<?php echo set_value('discount_amount')?>">
                                                </div>
                                    </div>

                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        
                                                <div class="form-group">
                                                    <label for="expires_at">Expires At<span class="text-danger">*</span></label>
                                                    <?php echo form_error('expires_at', '<br><span class="text-danger">', '</span>'); ?>
                                                    
                                                    <input class="form-control" type="datetime-local" id="expires_at" name="expires_at" placeholder="" value="<?php echo set_value('expires_at')?>">
                                                </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="form-group text-right">
                                    <input type="hidden" name="submit" value="Submit">
                                    <a href="<?php anchor_to(COUPONS_CONTROLLER . '/addcoupon'); ?>" class="btn btn-danger text-white mr-4"><i class="fas fa-arrow-left mr-1"></i> Back</a>
                                    <button class="btn btn-success"><i class="fas fa-plus mr-1"></i> Create Coupon</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End Page Content -->

</div>
<?php $this->load->view('admin/includes/foot'); ?>
    <script src="<?php admin_assets("js/bootstrap-input-spinner.js"); ?>"></script>
    <script src="<?php admin_assets("js/plugin/moment/moment.min.js"); ?>"></script>
    <script src="<?php admin_assets("js/plugin/datepicker/bootstrap-datetimepicker.min.js"); ?>"></script>
    <script src="<?php admin_assets("js/includes/services.js"); ?>"></script>
    <script src="<?php admin_assets("js/includes/inputImageShow.js"); ?>"></script>
    <script type="text/javascript" src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript" src="<?php admin_assets('js/includes/editor.js') ?>"></script>
<?php $this->load->view('admin/includes/footEnd'); ?>