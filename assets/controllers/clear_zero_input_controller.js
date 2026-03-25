import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    clearZeroOnFocus(event) {
        const input = event.target;

        if (input.value === '0') {
            input.value = '';
        }
    }
}
