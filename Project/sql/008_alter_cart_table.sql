ALTER TABLE Cart
    DROP FOREIGN KEY (product_id),
    DROP FOREIGN KEY (user_id);

ALTER TABLE Cart
    ADD FOREIGN KEY (product_id) references Products (id) ON DELETE CASCADE,
    ADD FOREIGN KEY (user_id references Users (id) ON DELETE CASCADE,
    UNIQUE KEY (product_id, user_id)
