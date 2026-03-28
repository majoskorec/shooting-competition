import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [
        'modal',
    ]

    connect() {
        this.modalInstance = null
    }

    async open(event) {
        event.preventDefault()

        const url = event.currentTarget.dataset.url
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })

        this.modalTarget.innerHTML = await response.text()
        this.ensureModal().show()
    }

    async submit(event) {
        event.preventDefault()

        const form = event.target
        const response = await fetch(form.action, {
            method: form.method,
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            redirect: 'follow',
        })

        if (response.redirected) {
            window.location.href = response.url
            return
        }

        this.modalTarget.innerHTML = await response.text()
        this.ensureModal().show()
    }

    ensureModal() {
        if (this.modalInstance === null) {
            this.modalInstance = new window.bootstrap.Modal(this.modalTarget)
        }

        return this.modalInstance
    }
}
