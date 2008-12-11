drop table if exists hoge_queue;
create table hoge_queue(
    id int not null,
    name varchar(25) not null
) engine=queue;
