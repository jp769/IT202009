CREATE TABLE IF NOT EXISTS Ratings
(
    id         int auto_increment,
    product_id  int NOT NUL,
    user_id    int NOT NULL,
    rating  int NOT NULL CHECK (rating between 1 and 5),
    comment varchar(255),
    created     TIMESTAMP       default current_timestamp,
    primary key (id),
    foreign key (user_id) references Users (id) ON DELETE CASCADE
)
