<?php

if (!defined('_PS_VERSION_')) {
    exit;
}
class mymodule extends Module
{

public function __construct()
    {
            $this-> name = 'mymodule';
            $this->tab = 'dashboard';
            $this->author = 'ForzenWillow';
            $this->version = '1.0.0';
            $this->description = 'This module will display top selling products on the homepage';

            parent:: __construct();
            $this->description = $this->trans('Showing Top Selling products created by Dainius,Vilius and Linas', [], 'Modules.mymodule.Admin');

            
    
    }

    public function install()
    {
        return parent::install() && $this->registerHook('displayExpressCheckout');
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->unregisterHook('displayExpressCheckout');
    }





    // public function install()
    // {
    //     return parent::install() && $this->registerHook('displayHome');
    // }

    // public function uninstall()
    // {
    //     return parent::uninstall() && $this->unregisterHook('displayHome');
    // }

     public function hookDisplayExpressCheckout($params)
     {
         $topSellingProducts = $this->getTopSellingProducts();

         $this->context->smarty->assign('topSellingProducts', $topSellingProducts);

         return $this->display(__FILE__, 'mymodule.tpl');
        
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

