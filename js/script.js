let alertDiv = document.getElementsByClassName("terminal-alert")

if (typeof(alertDiv) != 'undefined' && alertDiv != null) {
    setTimeout(
        () => {
            for (let i of alertDiv) {
                i.style.display = "none"
            }
        },
        3000
    )
}