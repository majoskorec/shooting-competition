import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [
        'club',
        'teamName',
    ]

    setTeamFromClub(event) {
        event.preventDefault();
        this.teamNameTarget.value = this.clubTarget.value;
        this.teamNameTarget.dispatchEvent(new Event('input', { bubbles: true }));
        this.teamNameTarget.dispatchEvent(new Event('change', { bubbles: true }));
    }
}
