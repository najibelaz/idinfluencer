<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class analytics_model extends MY_Model {
	public function __construct(){
		parent::__construct();
	}

	public function get_stats($ids = ""){
		$date_from=get('date_from');
		$date_to=get('date_to');
		$where_date_from = "";
		$where_date_to = "";
		
		if(post('date_from')==""){
			$date_from="01/".date("m/Y");	
		}
		if(post('date_to')==""){
			$date_to=date("d/m/Y");	
		}
		if(isset($date_from) && !empty($date_from)){
			$date_from = strtotime(str_replace('/', '-', $date_from ));
			$newDate = date("Y-m-d", $date_from);
			$where_date_from = "AND date >= '".$newDate."' ";
		}
		if(isset($date_to) && !empty($date_to)){
			$date_to = strtotime(str_replace('/', '-', $date_to ));
			$newDate = date("Y-m-d", $date_to);
			$where_date_to = "AND date <= '".$newDate."' ";
		}
		$result = $this->db->select("a.*")
				->from("facebook_analytics_stats as a")
				->join("facebook_accounts as b", "a.account = b.id")
				->where("b.ids = '{$ids}' ".$where_date_from.$where_date_to)
				->order_by("a.date","desc")
				->limit(15,0)
				->get()
				->result();

		$userinfo = $this->db->select("a.*")
				->from("facebook_analytics as a")
				->join("facebook_accounts as b", "a.account = b.id")
				->where("b.ids = '{$ids}'")
				->get()
				->row();
		
		$result_tmp = array_reverse($result);	
		$list = array();
		$followers_tmp = -1;
		$posts_tmp = -1;
		
		$followers_value_string = "";
		$date_string = "";
		$count_date = 0;

		if(!empty($result_tmp)){
			foreach ($result_tmp as $key => $row) {
				//List summary
				$data = json_decode($row->data);
				
				$followers_count = $data->followers_count;
				$media_count = $data->media_count;

				$list[$row->date] = (object)array(
					"followers" => $followers_count,
					"posts" => $media_count,
					"followers_sumary" => ($followers_tmp == -1)?"":($followers_count-$followers_tmp),
					"posts_sumary" => ($posts_tmp == -1)?"":($media_count-$posts_tmp),
					"date" => $row->date
				);

				$followers_tmp = $followers_count;
				$posts_tmp = $media_count;

				//Followers chart

				//Followers chart
				$followers_value_string .= "{$followers_count},";

				//Followers chart
				$date = DateTime::createFromFormat('Y-m-d', $row->date);
				//Date chart
				$date_string .= "'{$date->format('d-m-Y')}',";
			}

			//Cound Date
			$start_date = strtotime($result_tmp[0]->date);
			$end_date = strtotime($result_tmp[count($result_tmp) - 1]->date);
			$datediff = $end_date - $start_date;
			$count_date = round($datediff / (60 * 60 * 24));

			$followers_value_string = "[".substr($followers_value_string, 0, -1)."]";
			$date_string  = "[".substr($date_string, 0, -1)."]";

			//followers chart
			$result = (object)array(
				"list_summary" => $list,
				"followers_chart" => $followers_value_string,
				"date_chart" => $date_string,
				"data" => isset($userinfo->data)?json_decode($userinfo->data):"",
				"total_days" => $count_date
			);

			return $result;
		}

		return false;
	}


}
