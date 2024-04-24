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
                        <a href="<?php anchor_to(BOOKINGS_CONTROLLER) ?>">All Bookings</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-home">
                        <?php echo esc($page_title) ?>
                    </li>
                </ul>
            </div>
            <?php $this->load->view('admin/includes/alert');
//            print_r($booking);
//            print_r($serviceId_data);
//            print_r($other_services_data);
//            print_r($booking_usr_details);
            ?>
            <div class="row">
                <div class="col-md-12">
                    <form enctype="multipart/form-data" method="POST" action="<?php anchor_to(BOOKINGS_CONTROLLER . '/bookingReschedule/' . $booking['id']) ?>">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Client Name</label>
                                    <?php echo form_error('name', '<br><span class="text-danger">', '</span>'); ?>
                                    <input class="form-control" type="text" id="name" name="name" readonly="readonly" value="<?php echo $booking_usr_details['fullName'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="name">Client Email</label>
                                    <?php echo form_error('email', '<br><span class="text-danger">', '</span>'); ?>
                                    <input class="form-control" type="text" id="email" name="email" readonly="readonly" value="<?php echo $booking_usr_details['email'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="name">Phone</label>
                                    <?php echo form_error('phone', '<br><span class="text-danger">', '</span>'); ?>
                                    <input class="form-control" type="text" id="phone" name="phone" readonly="readonly" value="<?php echo $booking_usr_details['phone'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="code">No. of Adults </label>
                                    <?php echo form_error('adults', '<br><span class="text-danger">', '</span>'); ?>
                                    <input class="form-control" type="text" id="adults" name="adults" placeholder="adults" value="<?php echo $booking['adults']?>" >
                                </div>
                                <div class="form-group">
                                    <label for="code">No. of children </label>
                                    <?php echo form_error('childrens', '<br><span class="text-danger">', '</span>'); ?>
                                    <input class="form-control" type="text" id="childrens" name="childrens" placeholder="childrens" value="<?php echo $booking['childrens']?>" >
                                </div>
                                
                                
                                
                                
                                <div class="row">
                                    
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                        <table class="table table-striped-bg-warning table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Serial #</th>
                                                    <th>Service</th>
                                                    <th>Member Price</th>
                                                    <th>Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $e=1;
                                                foreach ($other_services_data as $al => $service): ?>
                                                <tr>
                                                    <td><?php echo $e++ ?></td>
                                                    <td><?php echo $service['title'] ?></td>
                                                    <td><?php echo '₹'. $service['member_price'] ?></td>
                                                    <td><?php echo '₹'. $service['price'] ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        
                                                <div class="form-group">
                                                    <label for="starts_at">Booking Date<span class="text-danger">*</span></label>
                                                    <?php echo form_error('date', '<br><span class="text-danger">', '</span>'); ?>
                                                    <input class="form-control" type="date" id="date" name="date" placeholder="" value="<?php echo set_value('date', date("Y-m-d", strtotime($booking['date'])))?>">
                                                </div>
                                    </div>

                                
                                    
                                    <div class="col-md-6">
                                        
                                                <div class="form-group">
                                                    <label for="expires_at">Booking Time<span class="text-danger">*</span></label>
                                                    <?php echo form_error('timing', '<br><span class="text-danger">', '</span>'); ?>
                                                    <?php
//                                                    echo $booking['timing'];
                                                    echo date("h:i A", strtotime($booking['timing']))
                                                    ?>
                                                    <input class="form-control" type="time" min="10:00" max="20:00" id="timing" name="timing" value="<?php echo $booking['timing']?>">
                                                </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="form-group text-right">
                                    <input type="hidden" name="submit" value="Submit">
                                    <a href="<?php anchor_to(BOOKINGS_CONTROLLER ); ?>" class="btn btn-danger text-white mr-4"><i class="fas fa-arrow-left mr-1"></i> Back</a>
                                    <button class="btn btn-success"><i class="fas fa-plus mr-1"></i> Update Booking</button>
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