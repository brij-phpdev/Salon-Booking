<?php   
    
    class ServiceModel extends CI_Model{
       
        public function __construct() {
            parent::__construct();
        }
        
        public function record_count() {
            return $this->db->count_all("servicetable");
        }
        
        public function serviceList($limit, $start){
            $this->db->limit($limit, $start);
            $res = $this->db->select()->order_by('id','desc')->get('servicetable')->result_array();
//            print_r($res);
            return $res;
        }
       
        public function serviceListWdAgent() {
			$res = $this->db->select('*, servicetable.id as id, agents.id as agents_id')
							->from('servicetable')
							->join('agents', 'servicetable.agentId = agents.id', 'left')
							->order_by('servicetable.id','desc')
							->limit($num)
							->get();
			if($res->num_rows()) {
				return $res->result_array();
			}
			else{
				return false;
			}
		}
        public function agentListByService() {
            $serviceResult = $this->db->select()->order_by('id','desc')->get('servicetable')->result_array();
            
            foreach($serviceResult as $i => $agentId) {
                $agentIds = explode(",", $agentId['agentIds']);
                $agentId['agentIds'] = [];
                foreach($agentIds as $id) {
                    $agent = $this->db->select()->where('id', $id)->limit(1)->get('agents')->row_array();
                    array_push($agentId['agentIds'], $agent);
                }
                $serviceResult[$i] = $agentId;
            }
            return $serviceResult;
        }

        public function addService($array) {
            if($this->db->insert('servicetable', $array))
                return true;
            return false;
        }

        public function serviceCategoryList(){
            $res = $this->db->select()->order_by('id','desc')->get('service_cat_table')->result_array();
            return $res;
        }

        
        public function addServiceCategory($array) {
            if($this->db->insert('service_cat_table', $array))
                return true;
            return false;
        }

        public function getserviceCategory($id) {
            $res = $this->db->limit(1)->where('id', $id)->get('service_cat_table')->row_array();
            return $res;
        }

        public function updateServiceCategory($id, Array $fields){
            $query = $this->db->where(['id'=>$id])->update('service_cat_table',$fields);
            return $query;
        }

        public function deleteServiceCategory($id) {
            $this->db->delete('service_cat_table',['id'=>$id]);
        }
        
        public function getservice($id) {
            $res = $this->db->limit(1)->where('id', $id)->get('servicetable')->row_array();
            return $res;
        }

        public function updateService($id, Array $fields){
            $query = $this->db->where(['id'=>$id])->update('servicetable',$fields);
            return $query;
        }

        public function deleteService($id) {
            $this->db->delete('servicetable',['id'=>$id]);
        }

        public function selectFormDatabyId($dpto){
            $query = $this->db->get_where('servicetable', array('id' => $dpto));
            return $query->result();
        }
        public function servicedataById($dpto){
            $query = $this->db->get_where('servicetable', array('id' => $dpto));
            return $query->row_array();
        }
        public function selectAgents($agentIds){
            $agentIdArray = explode(',',$agentIds);
            $query = $this->db->select()->where_in('id', $agentIdArray)->get('agents');
            return $query->result();
        }
    }
    
?>