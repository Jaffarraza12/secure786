<?php
class ModelCatalogQuestion extends Model {
	public function addReview($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "question SET name = '" . $this->db->escape($data['author']) . "',email = '" . $this->db->escape($data['email']) . "', product_id = '" . (int)$data['product_id'] . "', question = '" . $this->db->escape(strip_tags($data['text'])) . "', status = '" . (int)$data['status'] . "', sts = NOW(), mts = NOW()");

		$question_id = $this->db->getLastId();

		$this->cache->delete('product');


		return $question_id;
	}

	public function editReview($question_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "question SET name = '" . $this->db->escape($data['author']) . "',email= '" . $this->db->escape($data['email']) . "', product_id = '" . (int)$data['product_id'] . "', question = '" . $this->db->escape(strip_tags($data['text'])) . "',  status = '" . (int)$data['status'] . "', mts = NOW() WHERE id = '" . (int)$question_id . "'");

		$this->cache->delete('product');

	  //	$this->event->trigger('post.admin.review.edit', $review_id);
	}

	public function deleteQuestion($review_id) {
		//$this->event->trigger('pre.admin.review.delete', $review_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "question WHERE id = '" . (int)$review_id . "'");

		$this->cache->delete('product');

		//$this->event->trigger('post.admin.review.delete', $review_id);
	}

	public function getQuestion($review_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS product FROM " . DB_PREFIX . "question r WHERE r.id = '" . (int)$review_id . "'");

		return $query->row;
	}

	public function getQuestions($data = array()) {
		$sql = "SELECT r.*,pd.name as 'product' FROM " . DB_PREFIX . "question r LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) ";

		if (!empty($data['filter_product'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND r.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$sort_data = array(
			'pd.name',
			'r.author',
			'r.rating',
			'r.sts'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.sts";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		/*echo $sql;
		exit();*/

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalQuestions($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "question r LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_product'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND r.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalQuestionsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "question WHERE status = '0'");

		return $query->row['total'];
	}
}