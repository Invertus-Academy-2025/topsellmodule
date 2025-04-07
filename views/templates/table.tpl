<div class="panel">
    <div class="panel-heading">
        <i class="icon-bar-chart"></i> Top Selling Products
    </div>
    <div class="panel-body">
        <p class="lead">
            <i class="icon-truck"></i><strong>Would you like to send these products?</strong>
        </p>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr class="info">
                    <th><i class="icon-tag"></i> Product ID</th>
                    <th><i class="icon-cube"></i> Name</th>
                    <th><i class="icon-shopping-cart"></i> Total Sold</th>
                </tr>
                </thead>
                <tbody>
                {foreach $products as $product}
                    <tr>
                        <td>{$product.id_product}</td>
                        <td>{$product.name}</td>
                        <td>
                                <span class="label label-success">
                                    {$product.total_sales}
                                </span>
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
