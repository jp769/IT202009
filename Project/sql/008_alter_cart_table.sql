ALTER TABLE Cart
    foreign key (product_id) references Products (id) on delete cascade ,
    foreign key (user_id) references Users (id) on delete cascade
