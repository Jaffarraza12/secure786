<?php use OneCRM\APIClient\Authentication;
    use OneCRM\APIClient;
class ControllerCatalogProductcsv extends Controller
{
    private $error = array();
    var $token ='';

    public function index()
    {
        $options = [
            'client_id' => '4b7f2ea5-87f1-fbeb-bf6a-5b604348c73a',
            'client_secret' => 'bargain2015',
            'redirect_uri' => 'https://secure80057861109.bargain.pk/index.php?route=catalog/productcsv/step2&user_token=oJl7FOkhMa0Y1VBTBgYrLRtf55DPRAAC',
            'scope' => 'read write profile',
        ];

    
       $flow = new APIClient\AuthorizationFlow('https://bargain.1crmcloud.com/api.php', $options);
       $flow->init('authorization_code', true);
       $access_token = $flow->finalize();
       $auth = new Authentication\OAuth($access_token);
       $client = new APIClient\Client('https://bargain.1crmcloud.com/api.php', $auth);

        exit();

        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        $this->getList();
    }


 

    public function step2(){
      $options = [
        'client_id' => '4b7f2ea5-87f1-fbeb-bf6a-5b604348c73a',
        'client_secret' => 'bargain2015',
        'scope' => 'read write profile',
        'username' => 'admin',
        'password' => 'admin',
      ];
        $flow = new APIClient\AuthorizationFlow('https://bargain.1crmcloud.com/api.php', $options);
        $access_token = $flow->init('password');
        $this->token = $access_token;

        $this->contacts();
        exit(); 
                

        $auth = new Authentication\OAuth($access_token);
        echo json_encode($access_token, JSON_PRETTY_PRINT);


    }


    public function contacts(){
     $token = $this->token;
     $auth = new Authentication\OAuth($token);
     $client = new APIClient\Client('https://bargain.1crmcloud.com/api.php', $auth);
     /*$model = $client->model('Quote');
     $fields = ['position'];  
     $data = [
        'full_number' => '110',
        'name' => 'Order No . 110',
        'quote_stage' => 'Draft',
        'shipping_address_countrycode' => 'PK',
        'billing_account_id' => 'c753c141-b1b5-e52e-f109-5b6eaf71b4d9',
        'shipping_account_id' => 'c753c141-b1b5-e52e-f109-5b6eaf71b4d9',
        'amount' => '350', 
        'default_terms' => 'Jazz Cash',
        'industry' =>  'Retail' 
    
     ];
           
            

     $result = $model->create($data);
     //fetch no more than 3 records, starting from the beginning
     //printf("There are %d leads in total\n", $result->totalResults());
     echo json_encode($result, JSON_PRETTY_PRINT), "\n";*/
  
     
    /* META  
     $model = $client->model('Quote');
     $fields = ['position'];
     $result = $model->metadata(); // fetch no more than 3 records, starting from the beginning
     //printf("There are %d leads in total\n", $result->totalResults());
     echo json_encode($result, JSON_PRETTY_PRINT), "\n";*/

     /*LIST*/
     $model = $client->model('Contact');
     $filters = ['last_name' => 'Raza'];
     $result = $model->getList(['filters' => $filters ], 0, 3); // fetch no more than 3 records, starting from the beginning
      printf("There are %d contacts in total\n", $result->totalResults());
     echo json_encode($result->getRecords(), JSON_PRETTY_PRINT), "\n";

    }

     
    public function loadTshirt(){
        $row = 1;
        $products = array();
        $field_set = array(
            '0' => 'model',
            '1' => 'name',
            '2' => 'cost',
            '3' => 'price',
            '4' => 'special',
            '5' => 'meta_title',
            '6' => 'product_seo_url',
            '7'=> 'old_name',
            '8'=> 'directory',
            '9'=> 'category'


        );
        $colors = array(
            '49' => 'RE.jpg',
            '50' => 'BL.jpg',
            '51' => 'BLK.jpg',
            '52' => 'WH.jpg',
            '54' => 'NV.jpg',
            '57' => 'PU.jpg',
            '56' => 'CY.jpg',
            '55' => 'MA.jpg'
        );
        $sizes = array(
            '58' => 'Small',
            '59' => 'Medium',
            '60' => 'Large',
            '61' => 'X Large',

    );

        $product_option_value_data = array();
        if (($handle = fopen("Powerwords.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $directory = 'tshirt/';
                $num = count($data);
                $row++;
                for ($c=0; $c < $num; $c++) {
                    if($field_set[$c] == 'name'){
                        $products[$row]['product_description'][1]['name'] = $data[$c];
                    } else if($field_set[$c] == 'meta_title') {
                        $products[$row]['product_description'][1]['meta_title'] = $data[$c];
                    } else if($field_set[$c] == 'special') {
                        $products[$row]['product_special'][0]['price'] = $data[$c];
                    } else if($field_set[$c] == 'product_seo_url'){
                        $products[$row]['product_seo_url'][0][1] = $data[$c];
                    } else if($field_set[$c] == 'old_name'){
                        $old_name = $data[$c];
                        $products[$row]['product_description'][1]['meta_description'] = 'Shop 100% cotton '.$old_name.' printed T-Shirt at Bargain.Pk. We offer original and authentic designs, created by local Pakistani artist. All t-shirts are custom made using advance digital printing technology. Many sizes, colors and design to choose from. Buy Now!';
                    }  else if($field_set[$c] == 'directory'){
                        $directory .= $data[$c]."/";
                    }  else if($field_set[$c] == 'category'){
                        $products[$row]['product_category'][1]= $data[$c];
                    } else {
                        $products[$row][$field_set[$c]] = $data[$c];
                    }

                }

                foreach($colors as $key => $val){
                    $product_option_value_data[$row][] = array(
                        'option_value_id'         => $key,
                        'quantity'                => 10,
                        'subtract'                => 0,
                        'price'                   => 0,
                        'image'                   => $directory.$products[$row]['model'].'/image/'.$products[$row]['model'].$val,
                    );
                }

                $products[$row]['product_description'][1]['description'] = '<p>Authentic and Original Design - 100% cotton '.$old_name.' printed T-Shirt.</p><h5>T-Shirt Description</h5><ul>
                                    <li>Unisex roundneck T-shirt.</li>
                                    <li>100% fine cotton fabric.</li>
                                    <li>High quality double stitching.</li>
                                    <li>Comes in multiple colors.</li>
                                    <li>Available in sizes small to extra-large.</li>
                                    <li>Be sure to check the size chart before making any purchase.</li>
                                    </ul>
                                    <h5>Design</h5>
                                    <ul>
                                    <li>Original designs by Pakistani artists.</li>
                                    <li>Designs only available on Bargain.pk.</li>
                                    <li>This T-Shirt is printed by advance printing technology.</li>
                                    <li>The print last longer than screen & other printing methods.</li>
                                    </ul>
                                    <h5>Ordering</h5>
                                    <ul>
                                    <li>Order takes between 2 to 5 days before shipping depending on the rush & complexity of the design.</li>
                                    <li>Bulk discount available on large quantity order, please contact us at info@bargain.pk or call us at 0314-2006655.</li>
                                    <li>Custom designs are also available please contact us at info@bargain.pk or call us at 0314-2006655.</li>
                                    </ul>
                                    <h5>Return & Exchange</h5>
                                    <ul>
                                    <li>You cannot return or exchange if original labels and tags are removed.</li>
                                    <li>Used products cannot be returned, refund or exchanged.</li>
                                    <li>if you find any defective product kindly do not use it & immediately call us at 0314-2006655 for replacement.</li>
                                    </ul>
                                    <h5>Care Instructions</h5>
                                    <ul>
                                    <li>Wash after 24 hours transferring.</li>
                                    <li>Wash in warm water or cold water.</li>
                                    <li>Turn garment \'Inside-out\' wash.</li>
                                    <li>Don’t use bleach.</li>
                                    <li>Machine soft wash.</li>
                                    <li>Don’t dry-clean.</li>
                                    <li>Please do not iron directly on design.</li>
                                    <li>Turn the T-shirt inside out and then iron.</li>
                                    <li>Do not use too much hot iron.</li><ul>';
                $products[$row]['product_description'][1]['return_policy'] = 'You can return or exchange this item within 15 days after receiving of your order. We don`t except open box, open container and used products';
                $products[$row]['product_description'][1]['delivery_time'] = 'Delivery time takes around 5-7 days after verification of your order. Once we ship your order we are going to email you the tracking code.</p>';
                $products[$row]['product_description'][1]['payment_method'] = 'We do accept Jazzcash (credit and debit card), Bank transfers and COD for this item. For COD orders, we will verify the order shipping address and contact person before processing it.This might take an extra day.</>';
                $products[$row]['image'] = $directory.$products[$row]['model'].'/image/'.$products[$row]['model'].'BLK.jpg';
                $products[$row]['product_special'][0]['customer_group_id'] = 1;
                $products[$row]['nature'] = 0;
                $products[$row]['status'] = 1;
                $products[$row]['quantity'] = 50;
                $products[$row]['stock_status_id'] = 7;
                $products[$row]['minimum'] = 1;
                $products[$row]['shipping'] = 1;
                $products[$row]['depart_id'] = 3;


                $products[$row]['product_option'][] = array(
                    'product_option_id' => '',
                    'name' => 'TSHIRT COLOR',
                    'option_id'            => 13,
                    'type'                 => 'select',
                    'required'             => 1,
                    'product_option_value' => $product_option_value_data[$row],
                );

                foreach($sizes as $key => $val){
                    $product_option_value_sizes[$row][] = array(
                        'option_value_id'         => $key,
                        'quantity'                => 10,
                        'subtract'                => 0,
                        'price'                   => 0
                    );
                }

                $products[$row]['product_option'][] = array(
                    'product_option_id' => '',
                    'name' =>     'TSHIRT SIZE',
                    'option_id'            => 14,
                    'type'                 => 'select',
                    'required'             => 1,
                    'product_option_value' => $product_option_value_sizes[$row],
                );


                foreach($colors as $key => $val){
                    $products[$row]['product_image'][] =array(
                        'image' => $directory.$products[$row]['model'].'/image/'.$products[$row]['model'].$val,
                        'sort_order' => 0
                    );
                }

                //$products[$row]['product_category'][0]= 153;
                $products[$row]['product_store']= array(
                    '0' => 0,

                );
            }
            fclose($handle);
        }

        

        return $products;
    }

    public function load(){
        $this->load->model('catalog/operation');

        $row = 1;
        if (($handle = fopen("sup.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $this->model_catalog_operation->setProductName($data[0],$data[1]);
            }
            fclose($handle);
        }
    }

    public function setUrl(){
        $this->load->model('catalog/operation');

        $results = $this->model_catalog_operation->getDeptartmentProducts(1);

        $search = array(' ','&','amp;');
        $replace = array('_','','');

        foreach ($results as $result){
            $data = array();
            $data['product_id'] = $result['product_id'];
            $data['store_id'] = 0;
            $data['language_id'] = 1;
            $data['keyword'] =  str_replace($search,$replace,strtolower($result['name']));
            $setURL = $this->model_catalog_operation->saveURLProduct($data);
        }
    }


    public function tag(){
        $this->load->model('catalog/operation');

        $row = 1;
        if (($handle = fopen("gp.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $this->model_catalog_operation->setProductTag($data[0],$data[1]);
            }
            fclose($handle);
        }

    }



    public function setUrlCategory(){
        $this->load->model('catalog/operation');
        $this->load->model('catalog/category');

        $results = $this->model_catalog_operation->getAllCategories();

        $search = array(' ','&','amp;',',');
        $replace = array('_','','','');

        foreach ($results as $result){
            $data = array();
            $data['category_id'] = $result['category_id'];
            $data['store_id'] = 0;
            $data['language_id'] = 1;
            $data['keyword'] =  str_replace($search,$replace,strtolower($result['name']));
            $setURL = $this->model_catalog_operation->saveURLCategory($data);

        }
    }


      public function add()
    {
        $this->load->language('catalog/product');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/product');
        $products = $this->loadTshirt();

        foreach ($products as $product){
            $this->model_catalog_product->addProduct($product);
        }



    }

    public function edit()
    {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }

            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $product_id) {
                $this->model_catalog_product->deleteProduct($product_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }

            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    public function copy()
    {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        if (isset($this->request->post['selected']) && $this->validateCopy()) {
            foreach ($this->request->post['selected'] as $product_id) {
                $this->model_catalog_product->copyProduct($product_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }

            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    protected function getList()
    {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = '';
        }

        if (isset($this->request->get['filter_price'])) {
            $filter_price = $this->request->get['filter_price'];
        } else {
            $filter_price = '';
        }

        if (isset($this->request->get['filter_quantity'])) {
            $filter_quantity = $this->request->get['filter_quantity'];
        } else {
            $filter_quantity = '';
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'pd.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['add'] = $this->url->link('catalog/product/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['copy'] = $this->url->link('catalog/product/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('catalog/product/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['products'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_model' => $filter_model,
            'filter_price' => $filter_price,
            'filter_quantity' => $filter_quantity,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $this->load->model('tool/image');

        $product_total = $this->model_catalog_product->getTotalProducts($filter_data);

        $results = $this->model_catalog_product->getProducts($filter_data);

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
            }

            $special = false;

            $product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

            foreach ($product_specials as $product_special) {
                if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
                    $special = $this->currency->format($product_special['price'], $this->config->get('config_currency'));

                    break;
                }
            }

            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'image' => $image,
                'name' => $result['name'],
                'model' => $result['model'],
                'price' => $this->currency->format($result['price'], $this->config->get('config_currency')),
                'special' => $special,
                'quantity' => $result['quantity'],
                'status' => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'edit' => $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $result['product_id'] . $url, true)
            );
        }

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . '&sort=pd.name' . $url, true);
        $data['sort_model'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.model' . $url, true);
        $data['sort_price'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.price' . $url, true);
        $data['sort_quantity'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.quantity' . $url, true);
        $data['sort_status'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.status' . $url, true);
        $data['sort_order'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.sort_order' . $url, true);

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_model'] = $filter_model;
        $data['filter_price'] = $filter_price;
        $data['filter_quantity'] = $filter_quantity;
        $data['filter_status'] = $filter_status;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/product_list', $data));
    }

}