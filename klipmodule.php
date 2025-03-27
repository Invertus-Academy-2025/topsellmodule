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
            parent:: __construct();
            $this->description = $this->trans('Showing Top Selling products created by Dainius,Vilius and Linas', [], 'Modules.klipmodule.Admin');

    }

    public function install()
    {
        return parent::install();
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function getContent()
    {
        return $this->renderTable();
    }

    public function renderTable()
    {
        $topSellingProducts = $this->getTopSellingProducts();

        $html = '<h3>' . $this->l('Top Selling Products') . '</h3>';
        $html .= '<table class="table">';
        $html .= '<thead><tr><th>ID</th><th>Name</th><th>TotalSold</th></tr></thead><tbody>';

        foreach ($topSellingProducts as $product) {
            $html .= '<tr>';
            $html .= '<td>' . (int) $product['id_product'] . '</td>';
            $html .= '<td>' . htmlspecialchars($product['name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($product['total_sales']) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return $html;
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

}

