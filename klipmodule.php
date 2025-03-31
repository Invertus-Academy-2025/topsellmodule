<?php

if (!defined('_PS_VERSION_')) {
    exit;
}
class klipmodule extends Module
{

public function __construct()
    {
            $this-> name = 'klipmodule';
            $this->tab = 'dashboard';
            $this->author = 'Vilius, Dainius, Linas';
            $this->version = '1.0.0';
            $this->description = 'This module will display top selling products on the homepage';
            $this->bootstrap = true;
            parent:: __construct();
            $this->description = $this->trans('Showing Top Selling products created by Dainius,Vilius and Linas', [], 'Modules.klipmodule.Admin');

    }

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('send_top_selling')) {
            $output .= $this->sendTopSellingToApi();
        }

        $output .= $this->renderTable();
        $output .= $this->sendButton();

        return $output;
    }


    public function renderTable()
    {
        $topSellingProducts = $this->getTopSellingProducts();
        $this->context->smarty->assign('products', $topSellingProducts);

        return $this->display(__FILE__, 'views/templates/table.tpl');
    }

    public function sendButton()
    {
        return $this->display(__FILE__, 'views/templates/button.tpl');
    }

     public function getTopSellingProducts($limit = 5)
    {
        // SQL query to get top-selling products
         $sql = 'SELECT p.id_product, pl.name, p.reference, SUM(od.product_quantity) AS total_sales
               FROM ' . _DB_PREFIX_ . 'orders o
                 INNER JOIN ' . _DB_PREFIX_ . 'order_detail od ON o.id_order = od.id_order
                 INNER JOIN ' . _DB_PREFIX_ . 'product p ON od.product_id = p.id_product
                INNER JOIN ' . _DB_PREFIX_ . 'product_lang pl ON p.id_product = pl.id_product
                 WHERE o.valid = 1 AND pl.id_lang = ' . (int)$this->context->language->id . '
                 GROUP BY p.id_product
                 ORDER BY total_sales DESC
                 LIMIT ' . (int)$limit;

        // Return the result of the query
         return Db::getInstance()->executeS($sql);
     }

    public function sendTopSellingToApi()
    {
        $topProducts = $this->getTopSellingProducts();
        $apiUrl = 'https://localhost/api/products/save';

        $formattedProducts = [];

        foreach ($topProducts as $product) {
            $formattedProducts[] = [
                'productId' => (int) $product['id_product'],
                'name' => $product['name'],
                'totalSold' => (int) $product['total_sales'],
            ];
        }

        $payload = json_encode($formattedProducts);

        // Initialize cURL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // disable SSL for development
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        if ($httpCode >= 200 && $httpCode < 300) {
            return '<div class="alert alert-success">All products sent successfully!</div>';
        } else {
            return '<div class="alert alert-danger">Failed to send products. HTTP Status: ' . $httpCode . '</div>';
        }
    }

}

