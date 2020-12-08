CREATE TABLE IF NOT EXISTS OrderItems
(
    id         int auto_increment,
    order_id    int,
    product_id  int,
    quantity    int,
    unit_price  decimal(12, 2),
    primary key (id),
    foreign key (product_id) references Products (id) ON DELETE CASCADE ,
    foreign key (order_id) references Orders (id) ON DELETE CASCADE ,
    foreign key (quantity) references Cart (quantity) ON DELETE CASCADE
--     foreign key (quantity) references Cart (user_id.product_id.quantity) ON DELETE CASCADE ,
)
