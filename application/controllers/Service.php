<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {

	private $admin_user;
	private $page_data;
	
	public function __construct() {
        parent::__construct();
		
		$this->load->database();
		$this->load->model('AdminModel');
		$this->load->model('AgentsModel');
		$this->load->model('ImportExcelModel');
                $this->load->library("pagination");
		
		$this->page_data 				= $this->MainModel->pageData();
            $this->page_data['update']  	= $this->MainModel->updates_settings();
            $this->admin_user 				= $this->AdminModel->adminDetails();
//            $this->all_services 			= $this->ServiceModel->serviceList();
            $this->all_cat_services 			= $this->ServiceModel->serviceCategoryList();
            $this->agent_List_By_Service 	= $this->ServiceModel->agentListByService();
            $this->all_agents   			= $this->AgentsModel->agentList();

            if(!$this->admin_user) {
                redirect(base_url(AUTH_CONTROLLER . '/login?redirect='.urlencode(current_url())));
            }
	}
	
	public function index(){
		redirect(base_url(SERVICE_CONTROLLER . '/services'));
	}

        
        /** 
         * 
         * packages starts here
         * 
         */
        
        	public function packages() {
                    
		$data = array(
            'page_data' 				=> $this->page_data,
            'page_title' 				=> 'Services Package',
            'user' 						=> $this->admin_user,
            'services' 					=> $this->all_cat_services,
		);
		$this->load->view('admin/service/packages', $data);
	}
        
        public function catAdd(){
		$data = array(
            'page_data'     => $this->page_data,
            'page_title'    => 'Add Package',
            'user'          => $this->admin_user
		);

        if($this->input->post('submit') && !$data['user']['disabled']) {

			$cName		= $this->security->xss_clean($this->input->post('category-name'));
            $rules = array(
                array(
                    'field'     => 'category-name',
                    'label'     => 'Package Name',
                    'rules'     => 'required'
                )
			);
			
            $this->form_validation->set_rules($rules);
			$validation = $this->form_validation->run();
			
			
			if($validation) {
				$catAdd = array(
					'cName'         => htmlentities($cName)
				);
				
				$this->ServiceModel->addServiceCategory($catAdd);
				$data['alert'] = array('type' => 'alert alert-success', 'msg' => 'Package Added Successfully.');
			}
        }
		$this->load->view('admin/service/addPackage', $data);
	}//add category
        
        public function catEdit($id = null){
		if($categories = $this->ServiceModel->getserviceCategory($id)) {
            $data = array(
                'page_data'     => $this->page_data,
                'page_title'    => 'Editing: ' . html_entity_decode($categories['cName']),
                'user'          => $this->admin_user,
                'categories'      => $categories
			);

            if($this->input->post('submit') && !$data['user']['disabled']) {
				
				$cName		= $this->security->xss_clean($this->input->post('category-name'));

				$rules = array(
					array(
						'field'     => 'category-name',
						'label'     => 'Package Name',
						'rules'     => 'required'
					)
				);

                $this->form_validation->set_rules($rules);
                $validation = $this->form_validation->run();

                if($validation) {
                    $to_update = array(
                        'cName'      => htmlentities($cName)
					);

					$this->ServiceModel->updateServiceCategory($id, $to_update);
					$data['categories'] = $this->ServiceModel->getserviceCategory($categories['id']);
                    $this->session->set_flashdata('alert', array('type' => 'alert alert-success', 'msg'  => 'Successfully updated package.'));

                    redirect(SERVICE_CONTROLLER . '/catEdit/' . $id);
                }
            }

            $this->load->view('admin/service/editCat', $data);
		}
		else{
			redirect(base_url(SERVICE_CONTROLLER . '/packages'));
		}
	}//edit & update category

	public function catDelete($id = null, $confirm = false){
		$data = array(
			'page_data' 	=> $this->page_data,
            'page_title' 	=> 'All Clients',
            'user' 			=> $this->admin_user,
			'categories' 	=> $this->all_cats
		);

		if($confirm && !$data['user']['disabled']) {
			$this->ServiceModel->deleteServiceCategory($id);
			$this->session->set_flashdata('alert', array('type' => 'alert alert-success', 'msg'  => 'Successfully delete package.'));
		}
		return redirect(SERVICE_CONTROLLER.'/packages');
	}//delete category
        
        /** 
         * packages ends here
         * 
         */
        
        
        
        
        
	public function services() {

            $config = array();
                $config['base_url'] = base_url() . 'service/services/';
                $config['total_rows'] = $this->ServiceModel->record_count();
                $config['per_page'] = 20;
                $config["uri_segment"] = 3;
                $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
                

                $this->pagination->initialize($config);
                
		$data = array(
            'page_data' 				=> $this->page_data,
            'page_title' 				=> 'All Services',
            'user' 						=> $this->admin_user,
            'services' 					=> $this->ServiceModel->serviceList($config['per_page'],$page),
            'agent_List_By_Service' 	=> $this->ServiceModel->serviceList($config['per_page'],$page),
            'packages' 	=> array_values($this->all_cat_services)
		);
                $data["links"] = $this->pagination->create_links();

		$this->load->view('admin/service/service', $data);
	}
        
	public function addservice(){

		$data = array(
            'page_data'     => $this->page_data,
            'page_title'    => 'Add Service',
            'user'          => $this->admin_user,
            'agents'        => $this->all_agents,
            'packages'        => $this->all_cat_services
		);

        if($this->input->post('submit') && !$data['user']['disabled']) {
			$title		= $this->security->xss_clean($this->input->post('service-title'));
            $content    = $this->security->xss_clean($this->input->post('service-content'));
            $price    	= $this->input->post('service-price');
            $member_price    	= $this->input->post('member_price');
            $space    	= $this->input->post('service-space');
            $starts    	= $this->input->post('service-starts');
            $ends    	= $this->input->post('service-ends');
			$duration   = $this->input->post('service-duration');
			$agent   	= $this->input->post('agent[]');
			$category_id   	= $this->input->post('category_id');

			if($agent){
				$agentArray	= implode (",", $agent);
			}

            $rules = array(
                array(
                    'field'     => 'category_id',
                    'label'     => 'Package',
                    'rules'     => 'required'
                ),
                array(
                    'field'     => 'service-title',
                    'label'     => 'Service Title',
                    'rules'     => 'required'
                ),
                array(
                    'field'     => 'service-content',
                    'label'     => 'Content',
                    'rules'     => 'required'
                ),
                array(
                    'field'     => 'service-price',
                    'label'     => 'Price',
                    'rules'     => 'required|numeric|greater_than[0.99]'
                ),
                array(
                    'field'     => 'service-space',
                    'label'     => 'Space',
                    'rules'     => 'required'
				),
                array(
                    'field'     => 'service-starts',
                    'label'     => 'Starts',
                    'rules'     => 'required'
				),
                array(
                    'field'     => 'service-ends',
                    'label'     => 'Ends',
                    'rules'     => 'required'
				),
                array(
                    'field'     => 'service-duration',
                    'label'     => 'Duration',
                    'rules'     => 'required'
				),
                array(
                    'field'     => 'agent[]',
                    'label'     => 'Agent',
                    'rules'     => 'required'
                )
			);
			
            $this->form_validation->set_rules($rules);
			$validation = $this->form_validation->run();
			
			$this->load->library('upload', array(
				'upload_path' => APPPATH.'uploads/img/',
				'allowed_types' => 'gif|jpg|png|jpeg|svg',
				'overwrite' => true,
			));

			if(file_exists($_FILES['site-logo']['tmp_name']) == ''){
				$data['logo_error'] = 'Please must select image file for service.';
			}
			else{
				if($validation && $this->upload->do_upload('site-logo')) {
					$new_page = array(
						'category_id'         => $category_id,
						'title'         => htmlentities($title),
						'description'   => htmlentities($content),
						'price'       	=> $price,
                                                'member_price'      	=> $member_price,
						'servSpace'     => $space,
						'servStart'     => $starts,
						'servEnd'       => $ends,
						'servDuration'  => $duration,
						'agentIds'      => $agentArray
					);

					if(file_exists($_FILES['site-logo']['tmp_name'])) {
						$success = $this->upload->do_upload('site-logo');
						if($success) {
							$res = $this->upload->data();
							$name = $res['file_name'];
							$new_page['image'] = $name;
						} else {
							$data['logo_error'] = $this->upload->display_errors();
						}
					}
					
					$this->ServiceModel->addService($new_page);
					$data['alert'] = array('type' => 'alert alert-success', 'msg' => 'Service Added Successfully.');
				}
				else{
					$data['logo_error'] = $this->upload->display_errors();
				}
			}
        }
		$this->load->view('admin/service/addservice', $data);
	}
        
        
	public function importservice() {

            // read file..
            
            $this->ImportExcelModel->getLastServiceId();
            
            
            $data = array(
                'page_data' => $this->page_data,
                'page_title' => 'Import Service',
                'user' => $this->admin_user,
            );

            if ($this->input->post('submit') && !$data['user']['disabled']) {

                $rules = array(
                    array(
                        'field' => 'service-import',
                        'label' => 'Import Services',
                        'rules' => 'required'
                    )
                );

                $this->form_validation->set_rules($rules);
//                $validation = $this->form_validation->run();

                $this->load->library('upload', array(
                    'upload_path' => APPPATH . 'uploads/services/',
                    'allowed_types' => 'xls|xlsx',
                    'overwrite' => true,
                ));

                if (file_exists($_FILES['service-import']['tmp_name']) == '') {
                    $data['service_import_error'] = 'Please must select excel file for service.';
                } else {
//                    var_dump($validation);die;
                    if ($this->upload->do_upload('service-import')) {

                        if (file_exists($_FILES['service-import']['tmp_name'])) {

                            $success = $this->upload->do_upload('service-import');
                            if ($success) {
                                
                                $this->read_service_phpexcel_io   = $this->ImportExcelModel->readLatestServiceImportFile();
                                

                            } else {
                                $data['service_import_error'] = $this->upload->display_errors();
                            }
                        }

    //					$this->Import->addService($new_page);
                        $data['alert'] = array('type' => 'alert alert-success', 'msg' => ' [ '.$this->read_service_phpexcel_io.' ] Service(s) Imported Successfully.');
                    } else {
                        $data['service_import_error'] = $this->upload->display_errors();
                    }
                }
            }
            $this->load->view('admin/service/importservice', $data);
        }
        
        
        public function realServiceImport($filename) {
//            $read = $thi
        }

        public function editservice($id = null) {
        $this->load->model('ServiceModel');
        if($service = $this->ServiceModel->getservice($id)) {
            $data = array(
                'page_data'     => $this->page_data,
                'page_title'    => 'Editing: ' . html_entity_decode($service['title']),
                'user'          => $this->admin_user,
                'service'      => $service,
				'agents'        => $this->all_agents,
                'packages'        => $this->all_cat_services
			);
			if($data['service']['agentIds']){
				$data['service']['agentIds'] = explode (",", $data['service']['agentIds']);
			}

            if($this->input->post('submit') && !$data['user']['disabled']) {
				
				$title		= $this->security->xss_clean($this->input->post('service-title'));
                                $category_id   	= $this->input->post('category_id');
				$content    = $this->security->xss_clean($this->input->post('service-content'));
				$price    	= $this->input->post('service-price');
				$member_price    	= $this->input->post('member_price');
				$space    	= $this->input->post('service-space');
				$starts    	= $this->input->post('service-starts');
				$ends    	= $this->input->post('service-ends');
				$duration   = $this->input->post('service-duration');
				$agent   	= $this->input->post('agent[]');
	
				if($agent){
					$agentArray	= implode (",", $agent);
				}

				$rules = array(
					array(
						'field'     => 'service-title',
						'label'     => 'Service Title',
						'rules'     => 'required'
					),
					array(
						'field'     => 'service-content',
						'label'     => 'Content',
						'rules'     => 'required'
					),
					array(
						'field'     => 'service-price',
						'label'     => 'Price',
						'rules'     => 'required|numeric|greater_than[0.99]'
					),
					array(
						'field'     => 'service-space',
						'label'     => 'Space',
						'rules'     => 'required'
					),
					array(
						'field'     => 'service-starts',
						'label'     => 'Starts',
						'rules'     => 'required'
					),
					array(
						'field'     => 'service-ends',
						'label'     => 'Ends',
						'rules'     => 'required'
					),
					array(
						'field'     => 'service-duration',
						'label'     => 'Duration',
						'rules'     => 'required'
					),
					array(
						'field'     => 'agent[]',
						'label'     => 'Agent',
						'rules'     => 'required'
					)
				);

                $this->form_validation->set_rules($rules);
                $validation = $this->form_validation->run();

                if($validation) {
                    $to_update = array(
                        'title'         => htmlentities($title),
                        'category_id' => $category_id,
						'description'   => htmlentities($content),
						'price'      	=> $price,
						'member_price'      	=> $member_price,
						'servSpace'     => $space,
						'servStart'     => $starts,
						'servEnd'       => $ends,
						'servDuration'  => $duration,
						'agentIds'      => $agentArray
					);
					
					if(file_exists($_FILES['site-logo']['tmp_name'])) {

						$this->load->library('upload', array(
							'upload_path' => APPPATH.'uploads/img/',
							'allowed_types' => 'gif|jpg|png|jpeg|svg',
							'overwrite' => false,
						));
		
						if(file_exists($_FILES['site-logo']['tmp_name'])) {
							$success = $this->upload->do_upload('site-logo');
							if($success) {
								$res = $this->upload->data();
								$name = $res['file_name'];
								$to_update['image'] = $name;
							}
						}
							
					}

					$this->ServiceModel->updateService($id, $to_update);
					$data['service'] = $this->ServiceModel->getservice($service['id']);
                    $this->session->set_flashdata('alert', array('type' => 'alert alert-success', 'msg'  => 'Successfully updated service.'));

                    redirect(SERVICE_CONTROLLER . '/editservice/' . $id);
                }
            }

            $this->load->view('admin/service/editservice', $data);
        } else
            redirect(base_url(SERVICE_CONTROLLER));
	}

	public function deleteService($id = null, $confirm = false) {
		$this->load->model('ServiceModel');
		
		$data = array(
			'page_data' 	=> $this->page_data,
            'page_title' 	=> 'All Services',
            'user' 			=> $this->admin_user,
            'services' 		=> $this->all_services,
		);
		if($confirm && !$data['user']['disabled']) {
			$this->ServiceModel->deleteService($id);
			$this->session->set_flashdata('alert', array('type' => 'alert alert-success', 'msg'  => 'Successfully delete service.'));
		}
		return redirect(SERVICE_CONTROLLER);
	}
}
