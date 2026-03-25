import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["table"]

    toggle(event) {
        const button = event.currentTarget

        const rows = this.tableTarget.querySelectorAll("tr")

        rows.forEach((row) => {
            Array.from(row.children).forEach((cell) => {
                if (cell.dataset.hiddable === "true") {
                    cell.classList.toggle("hidden-column")
                }
            })
        })
        button.classList.toggle("hidden-column")

        button.textContent = button.classList.contains("hidden-column") ? "Zobraziť stĺpce" : "Skryť stĺpce"
    }
}
