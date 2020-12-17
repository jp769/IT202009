CREATE TABLE IF NOT EXISTS Orders
(
    id         int auto_increment,
    user_id    int,
    total_price     decimal(12, 2) default 0.00,
    address     varchar(120) NOT NULL,
    payment_method  varchar(40) NOT NULL,
    created     TIMESTAMP       default current_timestamp,
    primary key (id),
    foreign key (user_id) references Users (id) ON DELETE CASCADE
)
