<?php use OneCRM\APIClient\Authentication;
    use OneCRM\APIClient;
class ControllerCatalogProductcsvstock extends Controller
{
    private $error = array();
    var $product = '';
   public function __construct($registry)
   {
       parent::__construct($registry);
       $this->load->model('catalog/product');
       $this->product = $this->model_catalog_product;
   }

    public function index()
    {
       $filter = array();

       $filter['filter_category_id'] = 142;
       $products = $this->product->getProductsFront($filter);
        $colors = array(
            '49' => 'RE',
            '50' => 'BL',
            '51' => 'BLK',
            '52' => 'WH',
            '54' => 'NV',
            '57' => 'PU',
            '56' => 'CY',
            '55' => 'MA'
        );

        $colors_name = array(
            '49' => 'RED',
            '50' => 'BLUE',
            '51' => 'BLACK',
            '52' => 'WHITE',
            '54' => 'NAVY BLUE',
            '57' => 'PUROLE',
            '56' => 'CYAN',
            '55' => 'MAROON'
        );
        $sizes = array(
            '58' => 'S',
            '59' => 'M',
            '60' => 'L',
            '61' => 'XL');

       echo '<table border="1" cellspacing="1" cellpadding="1" style="width: 150%">';
       echo '<tr>';
       echo '<th>id</th>';
       echo '<th>title</th>';
       echo '<th>description</th>';
       echo '<th>condition</th>';
       echo '<th>sale_​​price</th>';
       echo '<th>price</th>';
       echo '<th>availability</th>';
       echo '<th>link</th>';
       echo '<th>image_link</th>';
       echo '<th>brand</th>';
       echo '<th>gender</th>';
       echo '<th>age group</th>';
       echo '<th>google_product_category</th>';
       echo '<th>size</th>';
       echo '<th>color</th>';
       echo '<th>material</th>';
       echo '<th>item_group_id</th>';
       echo '<th>product_type</th>';
       echo '<th>type</th>';
       echo '<th>identifier_​exists</th>';
       echo '</tr>';

       foreach ($products as $pro){
           $options = $this->product->getProductOptions($pro['product_id']);
           $product_seo_url = $this->product->getProductSeoUrls($pro['product_id']);
           $product_specials = $this->model_catalog_product->getProductSpecials($pro['product_id']);

            if($options[0]['option_id'] == 13 ) {
               $color_option = $options[0];
               $size_option = $options[1];
            } else {
                $color_option = $options[1];
                $size_option = $options[0];
            }

           foreach ($sizes as $size) {
               foreach ( $color_option['product_option_value'] as $color){
                   echo '<tr>';
                   echo '<td>'.$pro['model'].'-'.$colors[$color['option_value_id']].'-'.$size.'</td>';
                   echo '<td>'.$pro['name'].'</td>';
                   echo '<td>Authentic and Original Design - 100% cotton '.$pro['name'].'</td>';
                   echo '<td>New</td>';
                   echo '<td>'.$product_specials[0]['price'].'</td>';
                   echo '<td>'.$pro['price'].'</td>';
                   echo '<td>In Stock</td>';
                   echo '<td>https://bargain.pk/'.$product_seo_url[0][1].'</td>';
                   echo '<td>https://bargain.pk/image/'.$color['image'].'</td>';
                   echo '<td>Carve</td>';
                   echo '<td>Unisex</td>';
                   echo '<td>Adult</td>';
                   echo '<td>Clothing & Accessories > Clothing > Shirts & Tops</td>';
                   echo '<td>'.$size.'</td>';
                   echo '<td>'.$colors_name[$color['option_value_id']].'</td>';
                   echo '<td>100% cotton</td>';
                   echo '<td>'.$pro['model'].'</td>';
                   echo '<td>Clothing & Accessories > Clothing > Shirts & Tops</td>';
                   echo '<td>Regular</td>';
                   echo '<td>no</td>';
                   echo '</tr>';

               }
           }

       }
       echo '</table>';



        exit();

    }


}