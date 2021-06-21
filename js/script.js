let deleteBook = async (id) => {
    let booksTable = document.getElementById("books-table")
    let response = await fetch(`./delete.php?id=${id}`)

    if (response.ok) {
        let text = await response.text()

        booksTable.innerHTML =
            `<div class=\"terminal-alert terminal-alert-primary\">${text}</div>` +
            booksTable.innerHTML

        setTimeout(() => document.location.reload(), 2000)
    } else {
        let text = await response.text()

        booksTable.innerHTML =
            `<div class=\"terminal-alert terminal-alert-error\">${text}</div>` +
            booksTable.innerHTML
    }
}