import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [
        'club',
        'teamName',
        'shooter',
        'firstName',
        'lastName',
        'competitionTeam',
        'useClubButton'
    ]

    connect() {
        this.toggleShooterNameFields();
    }

    toggleShooterNameFields() {
        const selectedShooter = this.shooterTarget.querySelector('input[type="radio"]:checked');
        const hasSelectedShooter = selectedShooter !== null && selectedShooter.value.trim() !== '';

        this.firstNameTarget.disabled = hasSelectedShooter;
        this.lastNameTarget.disabled = hasSelectedShooter;
    }

    toggleTeamNameFields() {
        const selectedTeam = this.competitionTeamTarget.querySelector('input[type="radio"]:checked');
        const hasSelectedTeam = selectedTeam !== null && selectedTeam.value.trim() !== '';

        this.teamNameTarget.disabled = hasSelectedTeam;
        this.useClubButtonTarget.disabled = hasSelectedTeam;
    }

    setTeamFromClub(event) {
        event.preventDefault();
        this.teamNameTarget.value = this.clubTarget.value;
        this.teamNameTarget.dispatchEvent(new Event('input', { bubbles: true }));
        this.teamNameTarget.dispatchEvent(new Event('change', { bubbles: true }));
    }
}
