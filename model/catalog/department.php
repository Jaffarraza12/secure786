<?php
class ModelCatalogDepartment extends Model {


	public function getDepartment($dept_id) {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX . "department WHERE id='".$dept_id."'");

		return $query->row;
	}

	public function getDepartments($data = array()) {
        $query = $this->db->query("SELECT * FROM ". DB_PREFIX . "department ");

		return $query->rows;
	}


}
