// Delete book by id
let deleteBook = async (id) => {
    let response = await fetch(`./delete/${id}`)

    if (response.ok) {
        let json = await response.json()
        alert(json["status"])
    } else {
        let json = await response.json()
        alert(json["message"])
    }

    location.reload();
}

// Get all books from server
let getAllBooks = async () => {
    let response = await fetch(`./getAll`)

    let booksTableBody = document.getElementById("books-table-body")

    if (response.ok) {
        let json = await response.json()

        let body = ""
        let books = json["books"]

        for (let i = 0; i < books.length; i++) {
            body += `<tr id='row-${books[i]["id"]}'>`
            body += `<td><a href='./get/${books[i]["id"]}'>${books[i]["title"]}</a></td>`
            body += `<td>${books[i]["author"]}</td>`
            body += `<td>${books[i]["pub_date"]}</td>`
            body += `<th><a onclick='deleteBook(${books[i]["id"]})'>Delete</a></th>`
            body += `</tr>`
        }

        booksTableBody.innerHTML = body
    } else {
        let json = await response.json()
        alert(json["message"])
    }
}

document.onload = getAllBooks()