ALTER TABLE Products
    ADD COLUMN visibility varchar(5) default 'true',
    ADD COLUMN category varchar(100) default '';
