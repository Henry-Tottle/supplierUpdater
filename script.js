const mysql = require('promise-mysql')

const getISBNS = async () => {
    const connection = await mysql.createConnection({
        host: '127.0.0.1',
        user: 'root',
        password: 'password',
        database: 'publishers'
    })

    try {

    }
    const isbns = await connection.query('SELECT `isbn` FROM `books`')


}

