SELECT * FROM products
INNER JOIN
(SELECT product_id, type, size FROM product_type_size
INNER JOIN sizes
ON product_type_size.size_id = sizes.id
INNER JOIN types 
ON product_type_size.type_id = types.id) AS a
ON products.id = a.product_id