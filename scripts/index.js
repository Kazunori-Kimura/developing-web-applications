var sqlite3 = require('sqlite3').verbose();
var db = new sqlite3.Database('../lib/todos.db');

db.serialize(function() {
    console.log('ID, MAIL, PASSWORD');

    db.each("SELECT id, mail, password FROM Users ORDER BY id", function(err, row) {
        console.log('%d, %s, %s', row.id, row.mail, row.password);
    });
});

db.close();


