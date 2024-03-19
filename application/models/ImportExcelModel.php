<?php

require_once(BASEPATH . '../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Reader\Xls as ExcelReader;

class ImportExcelModel extends CI_Model {
    

    protected $service_file_path = APPPATH .'uploads/services/Service_Price.xls';
    
    public function readLatestServiceImportFile() {

        $filename = $this->service_file_path;

        if(file_exists($filename)){

            // read excel..
            try {
                
                $reader = new ExcelReader();
                $spreadsheet = $reader->load($filename);
                
//                print_r($spreadsheet);die;
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                if(count($rows)>4){
                    // now reset previous IDs..
//                    get the highest row in database .. & disable all Ids from 101 to the last..
                    $this->disableStatus();
                }
                
                $total_exe = 0;
//                print_r($highestRow);die;
                foreach ($rows as $r => $row) {

                    // skipping first 4 records..
                    if($r<4){                        continue;}
//print_r($row);die;
                        // first of all check category id 
                    $catName = $row[1];
                    
                    $catId = $this->getServiceCatId($catName);
                    
                    $serviceArray = array(
                                                'service_id'         => $row[0],
						'service_code'         => $row[9],
						'category_id'         => $catId,
						'sub_category'         => $row[2],
						'title'         => htmlentities($row[3]),
						'description'   => htmlentities($row[3]),
						'search_name'   => $row[4],
						'price'       	=> $row[7],
						'member_price'       	=> $row[6],
						'servSpace'     => 1,
						'servStart'     => '03:00 PM',
						'servEnd'       => '07:00 PM',
						'servDuration'  => $row[8],
						'agentIds'      => 'NULL',
//						'service_code'      => $row[9],
						'status'      => strtolower($row[5])
					);
                    
                    $x = $this->addService($serviceArray);
                    if($x){
                        $total_exe++;
                    }
                    
                }
                
                
            

            } catch (Exception $exc) {
                echo $exc->getMessage();
                echo $exc->getTraceAsString();
            }
        }
        return $total_exe;
//        return file_exists(APPPATH.'') ? json_decode(file_get_contents(APPPATH.'views/themes/manifest.json'), true) : false;
    }
    
    
    public function getServiceCatId($catName) {
        $catId = null;
            $res = $this->db->limit(1)->where('cName', htmlentities($catName))->get('service_cat_table')->row_array();
            
            if(is_null($res)){
//            if not present then we need to create one
                $catAdd = array(
					'cName'         => htmlentities($catName)
				);
                $this->db->insert('service_cat_table', $catAdd);
                $insert_id = $this->db->insert_id();

                $catId = $insert_id;
            }else{
                $catId = $res['id'];
            }
            return $catId;
    }
    
    public function addService($array) {
        if($this->db->insert('servicetable', $array))
            return true;
        return false;
    }
    
    public function disableStatus() {
        
        $startId = 101;
        $lastId = $this->getLastServiceId();
        $idArrays = range($startId,$lastId);
        print_r($idArrays);
        var_dump($lastId);die;
        $fields = array('status','inactive');
        $query = $this->db->where_in(['id'=>$idArrays])->update('service_cat_table',$fields);
        var_dump($query);
        return false;
    }
    
    
    public function getLastServiceId() {
        $res = $this->db->order_by('id','desc')->get('service_cat_table')->row_array();
        var_dump($res);
            return $res['id'];
    }
    
}