import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = [
        'partial',
        'grandTotal',
    ];

    connect() {
        this.observePartials();
        console.log(this.grandTotalTarget)
    }

    disconnect() {
        if (this.observer) {
            this.observer.disconnect();
        }
    }

    calculate() {
        const total = this.partialTargets.reduce((sum, element) => {
            const value = parseInt(element.textContent.trim(), 10);
            return sum + (Number.isNaN(value) ? 0 : value);
        }, 0);

        this.grandTotalTarget.textContent = total;
    }

    observePartials() {
        console.log('observePartials');
        this.observer = new MutationObserver(() => {
            this.calculate();
        });

        this.partialTargets.forEach((element) => {
            this.observer.observe(element, {
                childList: true,
                characterData: true,
                subtree: true,
            });
        });
    }
}
