<div class="container mt-5">
    <h2 class="mb-4">Would you like to send those products?
    </h2>
<table class="table table-striped table-bordered">
    <thead class="table-dark">
    <tr>
        <th>Product ID</th>
        <th>Name</th>
        <th>Total Sold</th>
    </tr>
    </thead>
    <tbody>

{foreach $products as $product }
    <tr>
        <td>{$product['id_product']}</td>
        <td>{$product['name']}</td>
        <td>{$product['total_sales']}</td>
    </tr>
{/foreach}
    </tbody>
</table>
</div>