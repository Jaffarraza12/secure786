<?php
class ControllerExtensionPaymentJazzCash extends Controller
{
    private $error = array();
    
    public function index()
    {
        $this->load->language('extension/payment/jazzcash');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_setting_setting->editSetting('payment_jazzcash', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
        }
        
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_edit']          = $this->language->get('text_edit');
        $data['text_enabled']       = $this->language->get('text_enabled');
        $data['text_disabled']      = $this->language->get('text_disabled');
        $data['text_all_zones']     = $this->language->get('text_all_zones');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_total']        = $this->language->get('entry_total');
        $data['entry_geo_zone']     = $this->language->get('entry_geo_zone');
        $data['entry_status']       = $this->language->get('entry_status');
        $data['entry_sort_order']   = $this->language->get('entry_sort_order');
        $data['help_total']         = $this->language->get('help_total');
        $data['button_save']        = $this->language->get('button_save');
        $data['button_cancel']      = $this->language->get('button_cancel');
        
        $data['lbl_merchantId']     = $this->language->get('lbl_merchantId');
        $data['lbl_password']       = $this->language->get('lbl_password');
        $data['lbl_language']       = $this->language->get('lbl_language');
        $data['lbl_version']        = $this->language->get('lbl_version');
        $data['lbl_actionUrl']      = $this->language->get('lbl_actionUrl');
        $data['lbl_returnUrl']      = $this->language->get('lbl_returnUrl');
        $data['lbl_currency']       = $this->language->get('lbl_currency');
        $data['lbl_txnExpiryHours'] = $this->language->get('lbl_txnExpiryHours');
        $data['lbl_integritySalt']  = $this->language->get('lbl_integritySalt');
        $data['lbl_validateHash']   = $this->language->get('lbl_validateHash');
        
        
        $data['breadcrumbs']   = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/jazzcash', 'user_token=' . $this->session->data['user_token'], 'SSL')
        );
        
        $data['action'] = $this->url->link('extension/payment/jazzcash', 'user_token=' . $this->session->data['user_token'], 'SSL');
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');
        
        if (isset($this->request->post['payment_jazzcash_merchantId'])) {
            $data['payment_jazzcash_merchantId'] = $this->request->post['payment_jazzcash_merchantId'];
        } else {
            $data['payment_jazzcash_merchantId'] = $this->config->get('payment_jazzcash_merchantId');
        }
        
        if (isset($this->request->post['payment_jazzcash_actionUrl'])) {
            $data['payment_jazzcash_actionUrl'] = $this->request->post['payment_jazzcash_actionUrl'];
        } else {
            $data['payment_jazzcash_actionUrl'] = $this->config->get('payment_jazzcash_actionUrl');
        }
        
        if (isset($this->request->post['payment_jazzcash_returnUrl'])) {
            $data['payment_jazzcash_returnUrl'] = $this->request->post['payment_jazzcash_returnUrl'];
        } else {
            $data['payment_jazzcash_returnUrl'] = $this->config->get('payment_jazzcash_returnUrl');
        }
        
        if (isset($this->request->post['payment_jazzcash_password'])) {
            $data['payment_jazzcash_password'] = $this->request->post['payment_jazzcash_password'];
        } else {
            $data['payment_jazzcash_password'] = $this->config->get('payment_jazzcash_password');
        }
        
        if (isset($this->request->post['payment_jazzcash_txnExpiryHours'])) {
            $data['payment_jazzcash_txnExpiryHours'] = $this->request->post['payment_jazzcash_txnExpiryHours'];
        } else {
            $data['payment_jazzcash_txnExpiryHours'] = $this->config->get('payment_jazzcash_txnExpiryHours');
        }
        
        if (isset($this->request->post['payment_jazzcash_integritySalt'])) {
            $data['payment_jazzcash_integritySalt'] = $this->request->post['payment_jazzcash_integritySalt'];
        } else {
            $data['payment_jazzcash_integritySalt'] = $this->config->get('payment_jazzcash_integritySalt');
        }
        
        if (isset($this->request->post['payment_jazzcash_total'])) {
            $data['payment_jazzcash_total'] = $this->request->post['payment_jazzcash_total'];
        } else {
            $data['payment_jazzcash_total'] = $this->config->get('payment_jazzcash_total');
        }
        
        if (isset($this->request->post['payment_jazzcash_order_status_id'])) {
            $data['payment_jazzcash_order_status_id'] = $this->request->post['payment_jazzcash_order_status_id'];
        } else {
            $data['payment_jazzcash_order_status_id'] = $this->config->get('payment_jazzcash_order_status_id');
        }
        
        $this->load->model('localisation/order_status');
        
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        
        if (isset($this->request->post['payment_jazzcash_geo_zone_id'])) {
            $data['payment_jazzcash_geo_zone_id'] = $this->request->post['payment_jazzcash_geo_zone_id'];
        } else {
            $data['payment_jazzcash_geo_zone_id'] = $this->config->get('payment_jazzcash_geo_zone_id');
        }
        
        $this->load->model('localisation/geo_zone');
        
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        
        if (isset($this->request->post['payment_jazzcash_status'])) {
            $data['payment_jazzcash_status'] = $this->request->post['payment_jazzcash_status'];
        } else {
            $data['payment_jazzcash_status'] = $this->config->get('payment_jazzcash_status');
        }
        
        if (isset($this->request->post['payment_jazzcash_validateHash'])) {
            $data['payment_jazzcash_validateHash'] = $this->request->post['payment_jazzcash_validateHash'];
        } else {
            $data['payment_jazzcash_validateHash'] = $this->config->get('payment_jazzcash_validateHash');
        }
        
        if (isset($this->request->post['payment_jazzcash_sort_order'])) {
            $data['payment_jazzcash_sort_order'] = $this->request->post['payment_jazzcash_sort_order'];
        } else {
            $data['payment_jazzcash_sort_order'] = $this->config->get('payment_jazzcash_sort_order');
        }
        
        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');
        
        
        $this->response->setOutput($this->load->view('extension/payment/jazzcash', $data));
    }
}
