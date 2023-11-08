<?php   
    
    class CouponsModel extends CI_Model{
       
        public function couponList(){
            $res = $this->db->select()->order_by('id','desc')->get('coupons')->result_array();
            return $res;
        }

        public function addCoupon($array) {

            if($this->db->insert('coupons', $array))
                return true;
            return false;
        }

        public function getCoupon($id) {
            $res = $this->db->limit(1)->where('id', $id)->get('coupons')->row_array();
            return $res;
        }

        public function updateCoupon($id, Array $fields){
            $query = $this->db->where(['id'=>$id])->update('coupons',$fields);
            return $query;
        }

        public function deleteCoupon($id) {
            $this->db->delete('coupons',['id'=>$id]);
        }
    }
    
?>