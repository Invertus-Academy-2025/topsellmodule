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
        $this->context->smarty->assign('products', $topSellingProducts);
        return $this->display(__FILE__, 'views/templates/table.tpl');
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

