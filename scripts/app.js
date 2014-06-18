var sqlite3 = require('sqlite3').verbose(),
    here = require('here').here;

var db = new sqlite3.Database('todos.db');

var sql1 = here(/*
create table todos (
    id integer primary key autoincrement,
    user_id integer,
    body text,
    done boolean,
    create_at datetime,
    update_at datetime
);
*/).valueOf();

var sql2 = here(/*
create table users (
    id integer primary key autoincrement,
    mail text unique,
    password text,
    last_login datetime,
    create_at datetime,
    update_at datetime
);
*/).valueOf();


db.serialize(function() {
  db.run(sql1);
  db.run(sql2);
});

db.close();