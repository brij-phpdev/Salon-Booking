<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Coupons extends CI_Controller {

	private $admin_user;
	private $page_data;
	
	public function __construct() {
        parent::__construct();
		
		$this->load->database();
		$this->load->model('AdminModel');
		$this->load->model('CouponsModel');
		
		$this->page_data 	        = $this->MainModel->pageData();
            $this->page_data['update']  = $this->MainModel->updates_settings();
            $this->admin_user 	        = $this->AdminModel->adminDetails();
            $this->all_coupons           = $this->CouponsModel->couponList();
            $this->coupon_types           = array('voucher','discount','sale');

            if(!$this->admin_user) {
                redirect(base_url(AUTH_CONTROLLER . '/login?redirect='.urlencode(current_url())));
            }
	}
	
	public function index(){

        $data = array(
            'page_data'     => $this->page_data,
            'page_title'    => 'All Coupons',
            'user'          => $this->admin_user,
            'coupons'        => $this->all_coupons
        );
		$this->load->view('admin/coupons/coupons', $data);
	}

	public function addcoupon(){

		$data = array(
            'page_data'     => $this->page_data,
            'page_title'    => 'Add Coupon',
            'user'          => $this->admin_user,
            'coupon_types'          => $this->coupon_types
		);

        if($this->input->post('submit') && !$data['user']['disabled']) {
			$name		    = $this->security->xss_clean($this->input->post('name'));
            $code         = $this->security->xss_clean($this->input->post('code'));
            $description         = $this->security->xss_clean($this->input->post('description'));
            $max_uses      = $this->security->xss_clean($this->input->post('max_uses'));
            $max_uses_user      = $this->security->xss_clean($this->input->post('max_uses_user'));
            $type      = $this->security->xss_clean($this->input->post('type'));
            $discount_amount      = $this->security->xss_clean($this->input->post('discount_amount'));
            $is_fixed      = $this->security->xss_clean($this->input->post('is_fixed'));
            $starts_at      = $this->security->xss_clean($this->input->post('starts_at'));
            $expires_at        = $this->security->xss_clean($this->input->post('expires_at'));

            $rules = array(
                array(
                    'field'     => 'name',
                    'label'     => 'Coupon Name',
                    'rules'     => 'required'
                ),
                array(
                    'field'     => 'description',
                    'label'     => 'Description',
                    'rules'     => 'required'
                ),
//                array(
//                    'field'     => 'code',
//                    'label'     => 'Experience',
//                    'rules'     => 'required|numeric'
//                ),
//                array(
//                    'field'     => 'totalBookings',
//                    'label'     => 'Total Bookings',
//                    'rules'     => 'required|numeric'
//				)
			);
			
            $this->form_validation->set_rules($rules);
			$validation = $this->form_validation->run();
			
			

			
			
				if($validation) {
					$new_added = array(
						'name'         => htmlentities($name),
						'description'       => htmlentities($description),
						'code'        => $code,
						'max_uses'        => $max_uses,
						'max_uses_user'        => $max_uses_user,
						'type'        => $type,
						'discount_amount'        => $discount_amount,
						'is_fixed'        => $is_fixed,
						'starts_at'        => $starts_at,
						'expires_at'     => $expires_at,
						'created_by'     => $this->admin_user['id'],
						'created_at'     => date('Y-m-d H:i:s'),
						'updated_at'     => date('Y-m-d H:i:s'),
                                        );

					$this->CouponsModel->addCoupon($new_added);
					$data['alert'] = array('type' => 'alert alert-success', 'msg' => 'Coupon Added Successfully.');
				}
				else{
					$data['logo_error'] = $this->upload->display_errors();
				}
        }
		$this->load->view('admin/coupons/addcoupon', $data);
	}

	public function editcoupon($id = null) {
        if($coupon = $this->CouponsModel->getCoupon($id)) {
            $data = array(
                'page_data'     => $this->page_data,
                'page_title'    => 'Editing: ' . html_entity_decode($coupon['name']),
                'user'          => $this->admin_user,
                'coupon'         => $coupon
            );

            if($this->input->post('submit') && !$data['user']['disabled']) {
				
				$name		    = $this->security->xss_clean($this->input->post('name'));
                $code         = $this->security->xss_clean($this->input->post('code'));
                $totalBookings      = $this->security->xss_clean($this->input->post('totalBookings'));
                $couponDetail        = $this->security->xss_clean($this->input->post('description'));

                $rules = array(
                    array(
                        'field'     => 'name',
                        'label'     => 'Coupon Name',
                        'rules'     => 'required'
                    ),
                    array(
                        'field'     => 'description',
                        'label'     => 'Description',
                        'rules'     => 'required'
                    ),
                    array(
                        'field'     => 'code',
                        'label'     => 'Experience',
                        'rules'     => 'required|numeric'
                    ),
                    array(
                        'field'     => 'totalBookings',
                        'label'     => 'Total Bookings',
                        'rules'     => 'required|numeric'
                    )
                );

                $this->form_validation->set_rules($rules);
                $validation = $this->form_validation->run();

                if($validation) {
                    $to_update = array(
                        'name'         => htmlentities($name),
						'couponDetail'       => htmlentities($couponDetail),
						'code'        => $code,
						'totalBookings'     => $totalBookings
					);
					
					if(file_exists($_FILES['site-logo']['tmp_name'])) {

						$this->load->library('upload', array(
							'upload_path' => APPPATH.'uploads/img/coupons',
							'allowed_types' => 'gif|jpg|png|jpeg|svg',
							'overwrite' => false,
						));
		
						if(file_exists($_FILES['site-logo']['tmp_name'])) {
							$success = $this->upload->do_upload('site-logo');
							if($success) {
								$res = $this->upload->data();
								$name = $res['file_name'];
								$to_update['couponImage'] = $name;
							}
						}
							
					}

					$this->CouponsModel->updateCoupon($id, $to_update);
                    $data['coupon'] = $this->CouponsModel->getCoupon($coupon['id']);
                    $this->session->set_flashdata('alert', array('type' => 'alert alert-success', 'msg'  => 'Successfully updated coupon.'));

                    redirect(AGENTS_CONTROLLER . '/editcoupon/' . $id);
                }
            }

            $this->load->view('admin/coupons/editcoupon', $data);
        } else
            redirect(base_url(AGENTS_CONTROLLER));
	}

	public function deleteCoupon($id = null, $confirm = false) {
		
		$data = array(
			'page_data' 	=> $this->page_data,
            'page_title' 	=> 'All Coupons',
            'user' 			=> $this->admin_user,
            'coupons'        => $this->all_coupons,
		);

		if($confirm && !$data['user']['disabled']) {
			$this->CouponsModel->deleteCoupon($id);
			$this->session->set_flashdata('alert', array('type' => 'alert alert-success', 'msg'  => 'Successfully delete coupon.'));
		}
		return redirect(AGENTS_CONTROLLER);
	}
}
